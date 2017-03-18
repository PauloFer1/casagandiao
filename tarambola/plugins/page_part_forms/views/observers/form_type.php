<?php require_once(SERVER_URL . "tarambola/app/classes/Page_Plug.php") ?>
<div class="page-metadata-row">
    <label for="<?php echo $css_id_prefix; ?>selection"><?php echo __('Page part form'); ?></label>
    <div class="page-metadata-column">
        <?php $page_id ?>
        <input type="hidden" name="page_metadata[<?php echo $plugin_id; ?>][name]" value="<?php echo $plugin_id; ?>" />
        <input type="hidden" name="page_metadata[<?php echo $plugin_id; ?>][visible]" value="0" />
        <select id="<?php echo $css_id_prefix; ?>selection" name="page_metadata[<?php echo $plugin_id; ?>][value]" class="page-metadata-value">
            <option value="">&#8212; <?php echo __('none'); ?> &#8212;</option>    
            <?php
            $text = explode("/", $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
            $pageId = $text[sizeOf($text) - 1];
            $part = $text[sizeOf($text) - 2];
            if ($part == "add") {
                
                require_once(SERVER_URL . 'tarambola/app/classes/Modulos.php');
                $mod = new Modulos();
                $mods = $mod->getMods();
                
                $inc=0;
                $parents = array();
                array_push($parents, Page::findById($pageId));
                while(Page::findById($parents[$inc]->parent_id)!=null && $flag=$mod->isModulo($parents[$inc]->id)==0)
                {
                    array_push($parents, Page::findById($parents[$inc]->parent_id));
                    $inc++;
                }
                
            }
            
            $forms = array();
            $selForm;
            foreach ($page_part_forms as $form) {
                if($part != "add")
                { 
                    echo '<option value="' . $form->id . '"' . ($form->id == $selected ? ' selected="selected"' : '') . '>' . $form->name . '</option>';
                }
                else{
                      //  for($k=0;$k<sizeof($mods); $k++)
                        {
                        foreach ($mods[$inc-$flag] as $key => $value) {
                          //  echo("#### key=". $key." - " .$value . "<\br>");
                            foreach($parents as $p)
                            {
                            //    echo("***** p=".$p->id." - ".$key."<\br>");
                                if($p->id==((string)$key)){
                                    if ($form->name == $value) {
                                        $selForm = $form;
                                    } 
                                }
                            }
                        }
                        }
                }
            }
            if ($part == "add") {
                 echo '<option value="' . $selForm->id . '" selected="selected">' . $selForm->name . '</option>';
            }
            ?>
        </select>
    </div>
</div>
