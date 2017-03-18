<ul id="<?php echo $css_id_prefix; ?>structure">
<?php 
$has_body = false;
foreach ($structure as $element) : ?>
  <?php
    $class = "";
    $type = "Page part";
    switch($element[PagePartFormsController::PROPERTY_TYPE]) {
      case PagePartFormsController::TYPE_PAGE_PART:
        $class = $css_class_prefix.'type-page-part';
        $type = "Page part";
        break;
      case PagePartFormsController::TYPE_TEXT:
        $class = $css_class_prefix.'type-text';
        $type = "Text";
        break;
      case PagePartFormsController::TYPE_SELECT:
        $class = $css_class_prefix.'type-select';
        $type = "Select";
        break; 
      case PagePartFormsController::TYPE_DATE:
        $class = $css_class_prefix.'type-date';
        $type = "Date";
        break;
      case PagePartFormsController::TYPE_IMAGE:
        $class = $css_class_prefix.'type-image';
        $type = "Image";
        break;
    case PagePartFormsController::TYPE_ATTRIBUTES:
        $class = $css_class_prefix.'type-attributes';
        $type = "Attributes";
        break;
    case PagePartFormsController::TYPE_DRAW:
        $class = $css_class_prefix.'type-draw';
        $type = "Draw";
        break;
    case PagePartFormsController::TYPE_COLORS:
        $class = $css_class_prefix.'type-colors';
        $type = "Colors";
        break;
      case PagePartFormsController::TYPE_POPULATE:
        $class = $css_class_prefix.'type-populate';
        $type = "Populate";
        break;
      case PagePartFormsController::TYPE_FILE:
        $class = $css_class_prefix.'type-file';
        $type = "File";
        break;
    case PagePartFormsController::TYPE_HIDDEN:
        $class = $css_class_prefix.'type-hidden';
        $type = "Hidden";
        break;
    case PagePartFormsController::TYPE_FORM:
        $class = $css_class_prefix.'type-form';
        $type = "Form";
        break;
    case PagePartFormsController::TYPE_SPACE:
        $class = $css_class_prefix.'type-form';
        $type = "Form";
        break;
    case PagePartFormsController::TYPE_SEQUENCE:
        $class = $css_class_prefix.'type-sequence';
        $type = "Sequence";
        break;
    case PagePartFormsController::TYPE_TABLE:
        $class = $css_class_prefix.'type-table';
        $type = "Table";
        break;
    }
    
    if ($element[PagePartFormsController::PROPERTY_NAME] == 'body') {
      $has_body = true;
    }
  ?>
  <li class="<?php echo $class; ?>"><?php echo $element[PagePartFormsController::PROPERTY_TITLE]; ?> (<?php echo __('Type: '.$type); ?><?php echo isset($element[PagePartFormsController::PROPERTY_LIMIT]) ? ", ".__('Limit').": ".$element[PagePartFormsController::PROPERTY_LIMIT] : ""; ?><?php echo isset($element[PagePartFormsController::PROPERTY_ORDER]) ? ", ".__('Order').": ".$element[PagePartFormsController::PROPERTY_ORDER] : ""; ?>)
    <?php
      if ($element[PagePartFormsController::PROPERTY_TYPE] == PagePartFormsController::TYPE_SELECT) {
        echo '<ul class="'.$css_class_prefix.'type-select-items">'.PHP_EOL;
        foreach ($element[PagePartFormsController::PROPERTY_VALUES] as $value) {
          echo '<li>'.$value.'</li>'.PHP_EOL;
        }
        
        echo '</ul>'.PHP_EOL;
      }
      if ($element[PagePartFormsController::PROPERTY_TYPE] == PagePartFormsController::TYPE_POPULATE) {
        echo '<ul class="'.$css_class_prefix.'type-select-items">'.PHP_EOL;
        foreach ($element[PagePartFormsController::PROPERTY_VALUES] as $value) {
          echo '<li>'.$value.'</li>'.PHP_EOL;
        }
        
        echo '</ul>'.PHP_EOL;
      }
    ?>
  </li>
<?php endforeach; ?>
</li>
<?php
if (!$has_body) {
 echo '<div id="'.$css_id_prefix.'nomain-note">'.__("Your page part definition does not contain an entry with the name 'body'.").'</div>';
}
?>