<?php

/**

/**
 * The FileManager allows users to upload and manipulate files.
 *
 * @package tarambola
 * @subpackage plugin.file_manager
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.0.0
 * @since tarambola version 0.9.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault & Martijn van der Kleijn, 2008
 */

  $out = '';
  $progres_path = '';
  $paths = explode('/', $filename); 
  $nb_path = count($paths);
  foreach ($paths as $i => $path) {
    if ($i+1 == $nb_path) {
      $out .= $path;
    } else {
      $progres_path .= $path.'/';
      $out .= '<a href="'.get_url('plugin/file_manager/browse/'.rtrim($progres_path, '/')).'">'.$path.'</a>/';
    }
  }
?>
<h1><a href="<?php echo get_url('plugin/file_manager'); ?>">public</a>/<?php echo $out; ?></h1>
<?php if ($is_image) { ?>
  <img src="<?php echo BASE_FILES_DIR.'/'.$filename; ?>" />
<?php } else { ?>
<form method="post" action="<?php echo get_url('plugin/file_manager/save'); ?>">
    <div class="form-area">
        <p class="content">
            <input type="hidden" name="file[name]" value="<?php echo $filename; ?>" />
            <textarea class="textarea" id="file_content" name="file[content]" style="width: 100%; height: 400px;" onkeydown="return allowTab(event, this);" rows="20" cols="40"><?php echo htmlentities($content, ENT_COMPAT, 'UTF-8'); ?></textarea><br />
        </p>
    </div>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
        <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
        <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/file_manager/browse/'.$progres_path); ?>"><?php echo __('Cancel'); ?></a>
    </p>
</form>
<p>Prima F8 para aumentar a àrea de texto e ESC para voltar ao normal.</p>
<p>Prima CONTROL+SPACE para auto-complete.</p>
<?php } ?>

  <script type="text/javascript">
var editor = CodeMirror.fromTextArea(document.getElementById("file_content"), {
        lineNumbers: true,
        extraKeys: {"Ctrl-Space"    : "autocomplete",
                    "F8"            : function(cm) {
                                                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                                                    },
                    "Esc"           : function(cm) {
                                                        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                                                    } 
        }
      });
</script>