<?php

$out = '';
  $progres_path = '';
  $paths = explode('/', $dir); 
  $nb_path = count($paths)-1; // -1 to didn't display current dir as a link
  foreach ($paths as $i => $path) {
    if ($i+1 == $nb_path) {
      $out .= $path;
    } else if ($path != '') {
      $progres_path .= $path.'/';
      $out .= '<a href="'.get_url('plugin/documentos/browse/'.rtrim($progres_path, '/')).'">'.$path.'</a>/';
    }
  }
?>
<h1><a href="<?php echo get_url('plugin/documentos'); ?>">documentos</a>/<?php echo $out; ?></h1>
<table id="files-list" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="files"><?php echo __('File'); ?></th>
      <th class="categorias">Descrição</th>
      <th class="categorias">Categorias</th>
      <th class="assoc">Utilizadores</th>
      <th class="size"><?php echo __('Size'); ?></th>
      <th class="modify"><?php echo __('Action'); ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($files as $file): ?>
    <tr class="<?php echo odd_even(); ?>">
      <td>
        <?php if ($file->is_dir) { ?>
            <img src="../tarambola/plugins/file_manager/images/dir_16.png" align="top" alt="dir icon" />
        <?php } else { ?>
            <img src="../tarambola/plugins/file_manager/images/page_16.png" align="top" alt="page icon" />
        <?php } ?>
            
            <a href="<?php echo(URL_PUBLIC.'public/documents/'.$file->name); ?>" target="_blank"><?php echo($file->name); ?></a>
      </td>
      <!-- ********************************* DESCRIÇÃO ASSOC ***************************************** -->
        <td>
          <div class="page_part_form_option">
                        <? 
                            $opt=""; $sel='';
                            $idBox ="descCatBox";
                            $idSelect="catOptBox";
                            if($file->is_dir==true)
                            {
                                $idBox="descCatfile";
                                $idSelect="catOptBoxFile";
                            }
                            $idSelect .= $file->id;
                            $idBox = $idBox.$file->id;
                        ?>
              <? if($file->is_dir==false):?>
                    <div class="descricaoFormDiv" id="<? echo($idBox); ?>" >
                        <span class="nameDoc"><? echo($file->name);?></span>
                        <button class="closeSelectFormBtn" onclick="closeDescBox('<? echo($file->id); ?>')">  close </button>
                        <span>Descrição</span>
                        <input class="inputDesc" type="text" value="<? echo($file->descricao); ?>" id="descInput<? echo($file->id); ?>"/>
                        <button class="descFormBtn2"  onclick="saveDesc('<? echo($file->id); ?>')" > SALVAR </button>
                    </div>
                        <button class="descFormBtn"  onclick="showDescBox('<? echo($file->id); ?>')" > INSERIR </button>
             <? endif; ?>
          </div>
      </td>
<!-- ********************************* CATEGORIAS ASSOC ***************************************** -->
        <td>
          <div class="page_part_form_option">
                        <? 
                            $opt=""; $sel='';
                            $idBox ="assocCatBox";
                            $idSelect="catOptBox";
                            if($file->is_dir==true)
                            {
                                $idBox="assocCatfile";
                                $idSelect="catOptBoxFile";
                            }
                            $idSelect .= $file->id;
                            $idBox = $idBox.$file->id;
                        ?>
              <? if($file->is_dir==false):?>
                    <div class="categoriaFormDiv" id="<? echo($idBox); ?>" >
                        <span class="nameDoc"><? echo($file->name);?></span>
                        <button class="closeSelectFormBtn" onclick="closeCatsBox('<? echo($file->id); ?>')">  close </button>
                        <div class="selectContainer">
                            <span>categorias</span>
                            <select id="<? echo($idSelect); ?>" multiple>
                                <? foreach($cats as $c): ?> 
                                    <option value="<? echo($c->id); ?>"><? echo($c->id.'-'.$c->nome); ?> </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                        <div class="inOutContainer">
                            <button class="inButton" onclick="selectCat(<? echo("'".$file->id."'");  ?>)">&raquo;</button>
                            <button class="outButton" onclick="unselectCat(<? echo("'".$file->id."'"); ?>)">&laquo;</button>
                        </div>
                        <div class="selectContainer">
                            <span>Associadas</span>
                            <select id="2<? echo($idSelect); ?>" multiple>
                                <? foreach ($file->cats as $cat): ?>
                                    <option class="options2Cat<? echo($file->id); ?>" value='<? echo($cat->id);  ?>' class="unsaved"><? echo($cat->id.'-'.$cat->nome); ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                        <button class="selectFormBtn"  onclick="showCatsBox('<? echo($file->id); ?>')" > ASSOCIAR </button>
             <? endif; ?>
          </div>
      </td>
<!-- ********************************* UTILIZADORES ASSOC ***************************************** -->
      <td>
          <div class="page_part_form_option">
                        <? 
                            $opt=""; $sel='';
                            $idBox ="assocbox";
                            $idSelect="selectOptBox";
                            if($file->is_dir==true)
                            {
                                $idBox="assocboxfile";
                                $idSelect="selectOptBoxFile";
                            }
                            $idSelect .= $file->id;
                            $idBox = $idBox.$file->id;
                        ?>
              <? if($file->is_dir==false):?>
                    <div class="assocFormDiv" id="<? echo($idBox); ?>" >
                        <span class="nameDoc"><? echo($file->name);?></span>
                        <button class="closeSelectFormBtn" onclick="closeAssocBox('<? echo($file->id); ?>')">  close </button>
                        <div class="selectContainer">
                            <span>Utilizadores</span>
                            <select id="<? echo($idSelect); ?>" multiple>
                                <? foreach($users as $u): ?> 
                                    <option value="<? echo($u->id); ?>"><? echo($u->id.'-'.$u->nome); ?> </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                        <div class="inOutContainer">
                            <button class="inButton" onclick="selectUsers(<? echo("'".$file->id."',".$file->id);  ?>)">&raquo;</button>
                            <button class="outButton" onclick="unselectUsers(<? echo("'".$file->id."',".$file->id); ?>)">&laquo;</button>
                        </div>
                        <div class="selectContainer">
                            <span>Associados</span>
                            <select id="2<? echo($idSelect); ?>" multiple>
                                <? foreach ($file->users as $ass): ?>
                                    <option class="options2<? echo($file->id); ?>" value='<? echo($ass->id_user);  ?>' class="unsaved"><? echo($ass->id_user.'-'.$ass->nome); ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                        <button class="selectFormBtn"  onclick="showAssocBox('<? echo($file->id); ?>')" > ASSOCIAR </button>
             <? endif; ?>
          </div>
      </td>
      <td><code><?php echo $file->size; ?></code></td>
      <td>
        <a href="#" onclick="toggle_rename_popup('<?php echo $dir.$file->name; ?>', '<?php echo $file->name; ?>'); return false;" title="<?php echo __('Rename'); ?>"><img src="images/icon-rename.gif" alt="rename icon" /></a> 
        <a href="<?php echo get_url('plugin/documentos/delete/'.$dir.$file->name); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete'); ?> <?php echo $file->name; ?>?');"><img src="images/icon-remove.gif" alt="remove icon" /></a>
      </td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>

<div id="popups">
  <div class="popup" id="create-file-popup" style="display:none;">
    <h3><?php echo __('Create new file'); ?></h3>
    <form action="<?php echo get_url('plugin/file_manager/create_file'); ?>" method="post"> 
      <div>
        <input id="create_file_path" name="file[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="create_file_name" maxlength="255" name="file[name]" type="text" value="" /> 
        <input id="create_file_button" name="commit" type="submit" value="<?php echo __('Create'); ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('create-file-popup'); return false;"><?php echo __('Close'); ?></a></p>
    </form>
  </div>
  <div class="popup" id="create-directory-popup" style="display:none;">
    <h3><?php echo __('Create new directory'); ?></h3>
    <form action="<?php echo get_url('plugin/documentos/create_directory'); ?>" method="post">
      <div>
        <input id="create_directory_path" name="directory[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="create_directory_name" maxlength="255" name="directory[name]" type="text" value="" /> 
        <input id="file_button" name="commit" type="submit" value="<?php echo __('Create'); ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('create-directory-popup'); return false;"><?php echo __('Close'); ?></a></p>
    </form>
  </div>
     <div class="popup" id="create-category-popup" style="display:none;">
    <h3><?php echo __('Criar categoria'); ?></h3>
    <form action="<?php echo get_url('plugin/documentos/create_category'); ?>" method="post">
      <div>
        <input id="create_category_path" name="category[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="create_category_name" maxlength="255" name="category[name]" type="text" value="" /> 
        <input id="file_button" name="commit" type="submit" value="<?php echo __('Create'); ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('create-category-popup'); return false;"><?php echo __('Close'); ?></a></p>
    </form>
  </div>
  <div class="popup" id="upload-file-popup" style="display:none;">
    <form action="<?php echo get_url('plugin/file_manager/upload'); ?>" method="post" enctype="multipart/form-data"> 
      <h3><?php echo __('Upload file'); ?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input id="upload_overwrite" name="upload[overwrite]" type="checkbox" value="1" /><label for="upload_overwrite"><small><?php echo __('overwrite it?'); ?></small></label></h3>
      <div>
        <input id="upload_path" name="upload[path]" type="hidden" value="<?php echo ($dir == '') ? '/': $dir; ?>" />
        <input id="upload_file" name="upload_file" type="file" />
        <input id="upload_file_button" name="commit" type="submit" value="<?php echo __('Upload'); ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('upload-file-popup'); return false;"><?php echo __('Close'); ?></a></p>
    </form>
  </div>
  <div class="popup" id="chmod-popup" style="display:none;">
    <h3><?php echo __('Change mode'); ?></h3>
    <form action="<?php echo get_url('plugin/file_manager/chmod'); ?>" method="post"> 
      <div>
        <input id="chmod_file_name" name="file[name]" type="hidden" value="" />
        <input id="chmod_file_mode" maxlength="4" name="file[mode]" type="text" value="" /> 
        <input id="chmod_file_button" name="commit" type="submit" value="<?php echo __('Change mode'); ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('chmod-popup'); return false;"><?php echo __('Close'); ?></a></p>
    </form>
  </div>
  <div class="popup" id="rename-popup" style="display:none;">
      <h3><?php echo __('Rename'); ?></h3>
      <form action="<?php echo get_url('plugin/file_manager/rename'); ?>" method="post"> 
        <div>
          <input id="rename_file_current_name" name="file[current_name]" type="hidden" value="" />
          <input id="rename_file_new_name" maxlength="50" name="file[new_name]" type="text" value="" /> 
          <input id="rename_file_button" name="commit" type="submit" value="<?php echo __('Rename'); ?>" />
        </div>
        <p><a class="close-link" href="#" onclick="Element.hide('rename-popup'); return false;"><?php echo __('Close'); ?></a></p>
      </form>
    </div>
</div>
