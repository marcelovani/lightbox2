<?php
// $Id$

/**
 * @file
 * Lightbox2 formatter hooks and callbacks.
 */


/**
 * Theme function for displaying the lightbox2 trigger image in an imagefield.
 */
function theme_lightbox2_image($path, $alt = '', $title = '', $attributes = NULL) {
  $attributes = drupal_attributes($attributes);
  $image = '<img src="'. file_create_url($path) .'" alt="'.  check_plain($alt) .'" title="'. check_plain($title) .'" '. $attributes .' />';
  return $image;
}

/**
 * Handler for Lightbox2 display of imagecache + imagefield CCK fields.
 */
function theme_lightbox2_formatter_imagefield($element) {
  if (!module_exists("imagecache") || !module_exists("imagefield")) {
    return;
  }

  $field = $element['#field_name'];
  $item = $element['#item'];
  $formatter = $element['#formatter'];
  $node = node_load($element['#item']['nid']);

  if (strpos($formatter, '__lightbox2__') !== FALSE || strpos($formatter, '__lightshow2__') !== FALSE || strpos($formatter, '__lightframe2__') !== FALSE) {
    list($tmp, $lightbox_type, $view_preset, $lightbox_preset) = explode('__', $formatter, 4);
    return lightbox2_imagefield_image_imagecache($field, $item, $formatter, $node, $view_preset, $lightbox_preset);
  }
}

/**
 * Function to set up the data needed for
 * theme_imagefield_image_imagecache_lightbox2().
 *
 * @param $field
 *   The field the action is being performed on.
 * @param $item
 *   An array, keyed by column, of the data stored for this item in this field.
 * @param $formatter
 *   The formatter to use for the field.
 * @param $node
 *   The node object.
 * @param $view_preset
 *   The imagecache preset to be displayed on the node or in the view.
 * @param $lightbox_preset
 *   The imagecache preset to be displayed in the lightbox.
 */
function lightbox2_imagefield_image_imagecache($field, $item, $formatter, $node, $view_preset, $lightbox_preset) {
  if (isset($item['filepath'])) {
    $filepath = &$item['filepath'];
  }
  elseif (!empty($item['fid'])) {
    $file = _imagefield_file_load($item['fid']);
    $filepath = &$file['filepath'];
  }
  else {
    return '';
  }

  $item = array_merge($item, (array)$file);
  $item['filepath'] = $filepath;
  $item['lightbox_preset'] = $lightbox_preset;

  $rules = array();
  if (function_exists('imagecache_presets')) {
    $presets = imagecache_presets();
    foreach ($presets as $preset_id => $preset_info) {
      $rules[$preset_id] = $preset_info['presetname'];
    }
  }
  else {
    $rules = _imagecache_get_presets();
  }
  if ($view_preset == 'original' || in_array($view_preset, (array) $rules)) {
    $rel = 'lightbox';
    if (strpos($formatter, '__lightshow2__') !== FALSE) {
      $rel = 'lightshow';
    }
    elseif (strpos($formatter, '__lightframe2__') !== FALSE) {
      $rel = 'lightframe';
    }
    return theme('imagefield_image_imagecache_lightbox2', $view_preset, $field, $item, $node, $rel);
  }
}

/**
 * Generate the HTML output for imagefield + imagecache images so they can be
 * opened in a lightbox by clicking on the image on the node page or in a view.
 *
 * @param $view_preset
 *   The imagecache preset to be displayed on the node or in the view.
 * @param $field
 *   The field the action is being performed on.
 * @param $item
 *   An array, keyed by column, of the data stored for this item in this field.
 * @param $node
 *   The node object.
 * @param $rel
 *   The type of lightbox to open: lightbox, lightshow or lightframe.
 */
function theme_imagefield_image_imagecache_lightbox2($view_preset, $field, $item, $node, $rel = 'lightbox') {

  // Can't show current node page in a lightframe on the node page.
  // Switch instead to show it in a lightbox.
  if ($rel == 'lightframe' && arg(0) == 'node' && arg(1) == $node->nid) {
    $rel = 'lightbox';
    $item['lightbox_preset'] = 'original';
  }
  $orig_rel = $rel;

  // Set up the caption.
  $node_link = '';
  $attributes = array();
  if (!empty($item['nid'])) {
    $target = variable_get('lightbox2_node_link_target', FALSE);
    if (!empty($target)) {
      $attributes = array('target' => $target);
    }
    $node_link_text = variable_get('lightbox2_node_link_text', 'View Image Details');
    if (!empty($node_link_text)) {
      $node_link .= '<br /><br />'. l($node_link_text, 'node/'. $item['nid'], array('attributes' => $attributes));
    }
  }

  if ($orig_rel == 'lightframe') {
    $frame_width = variable_get('lightbox2_default_frame_width', 600);
    $frame_height = variable_get('lightbox2_default_frame_height', 400);
    $frame_size = 'width:'. $frame_width .'px; height:'. $frame_height .'px;';
    $rel = preg_replace('/\]$/', "|$frame_size]", $rel);
  }
  $image_title = (!empty($item['title']) ? $item['title'] : $item['alt']);
  if (variable_get('lightbox2_imagefield_use_node_title', FALSE)) {
    $node = node_load($node->nid);
    $image_title = $node->title;
  }
  $caption = $image_title . $node_link;


  // Set up the rel attribute.
  $imagefield_grouping = variable_get('lightbox2_imagefield_group_node_id', 1);
  if ($imagefield_grouping == 1) {
    $rel = $rel .'['. $field['field_name'] .']['. $caption .']';
  }
  elseif ($imagefield_grouping == 2 && !empty($item['nid'])) {
    $rel = $rel .'['. $item['nid'] .']['. $caption .']';
  }
  elseif ($imagefield_grouping == 3 && !empty($item['nid'])) {
    $rel = $rel .'['. $field['field_name'] . $item['nid'] .']['. $caption .']';
  }
  else {
    $rel = $rel .'[]['. $caption .']';
  }


  $link_attributes = array(
    'rel' => $rel,
  );

  if ($view_preset == 'original') {
    $image = theme('lightbox2_image', $item['filepath'], $item['alt'], $item['title'], $attributes);
  }
  else {
    $image = theme('imagecache', $view_preset, $item['filepath'], $item['alt'], $item['title'], $attributes);
  }
  if ($item['lightbox_preset'] == 'node') {
    $output = l($image, 'node/'. $node->nid .'/lightbox2', array('attributes' => $link_attributes, 'html' => TRUE));
  }
  elseif ($item['lightbox_preset'] == 'original') {
    $output = l($image, file_create_url($item['filepath']), array('attributes' => $link_attributes, 'html' => TRUE));
  }
  else {
    $output = l($image, imagecache_create_url($item['lightbox_preset'], $item['filepath']), array('attributes' => $link_attributes, 'html' => TRUE));
  }

  return $output;
}


/**
 * Theme function for the lightbox iframe filefield formatter.
 */
function theme_lightbox2_formatter_filefield_lightframe($element) {
  $file = $element['#item'];
  $field = content_fields($element['#field_name']);
  if (!filefield_view_access($field['field_name']) || empty($file['list'])) {
    return '';
  }
  if (empty($file['fid']) || !is_file($file['filepath'])) {
    return '';
  }
  $file = (object)$file;
  if (!lightbox2_check_filefield_extension($file, $field)) {
    return '';
  }
  drupal_add_css(drupal_get_path('module', 'filefield') .'/filefield.css');
  return '<div class="filefield-item">'. theme('lightbox2_file_formatter_lightbox2_iframe', $file, $field, NULL) .'</div>';
}

/**
 * Theme function for the lightbox iframe filefield formatter.
 */
function theme_lightbox2_file_formatter_lightbox2_iframe($file, $field, $file_formatter_settings) {
  $path = $file->filepath;
  $url = file_create_url($path);
  $icon = theme('filefield_icon', $file);
  $description = $file->description;

  $target = variable_get('lightbox2_node_link_target', FALSE);
  if (!empty($target)) {
    $attributes = array('target' => $target);
  }
  $node_link = '';
  $node_link_text = check_plain(variable_get('lightbox2_node_link_text', 'View Image Details'));
  if (!empty($node_link_text)) {
    $node_link .= '<br /><br />'. l($node_link_text, $url, array('attributes' => $attributes));
  }

  // Only files with supported extensions make it this far, so no need to check here.
  $link_attributes = array('rel' => 'lightframe[]['. $description . $node_link .']');

  return '<div class="filefield-formatter-lightbox2-iframe">'. $icon . l($description, $url, array('attributes' => $link_attributes)) .'</div>';
}
