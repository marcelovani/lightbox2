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
 * This actually also handles filefields + imagecache images too.
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
  $image_data = $item['data'];
  if (is_string($image_data)) {
    $image_data = unserialize($item['data']);
  }
  $image_title = (!empty($image_data['title']) ? $image_data['title'] : $image_data['alt']);
  $image_title = (!empty($title) ? $title : $image_data['description']); // If filefield, we need the description field text instead.
  if (variable_get('lightbox2_imagefield_use_node_title', FALSE)) {
    $node = node_load($node->nid);
    $image_title = $node->title;
  }
  $caption = $image_title . $node_link;


  // Set up the rel attribute.
  $imagefield_grouping = variable_get('lightbox2_imagefield_group_node_id', 1);
  if ($imagefield_grouping == 1) {
    $rel = $rel .'['. $field .']['. $caption .']';
  }
  elseif ($imagefield_grouping == 2 && !empty($item['nid'])) {
    $rel = $rel .'['. $item['nid'] .']['. $caption .']';
  }
  elseif ($imagefield_grouping == 3 && !empty($item['nid'])) {
    $rel = $rel .'['. $field . $item['nid'] .']['. $caption .']';
  }
  else {
    $rel = $rel .'[]['. $caption .']';
  }


  $link_attributes = array(
    'rel' => $rel,
  );

  if ($view_preset == 'original') {
    $image = theme('lightbox2_image', $item['filepath'], $image_data['alt'], $image_data['title'], $attributes);
  }
  else {
    $image = theme('imagecache', $view_preset, $item['filepath'], $image_data['alt'], $image_data['title'], $attributes);
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
  $description = $file->data['description'];

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

/**
 * Generate the HTML output to open embedded cck images in a lightbox.
 */
function theme_lightbox2_formatter_emimage($element) {
  $field = content_fields($element['#field_name'], $element['#type_name']);
  $item = $element['#item'];
  $formatter = $element['#formatter'];
  $node = node_load($element['#node']->nid);

  list($tmp, $lightbox_type) = explode('_', $formatter, 2);
  $field['lightbox_type'] = $lightbox_type;
  $formatter = "emimage";

  return module_invoke('emfield', 'emfield_field_formatter', $field, $item, $formatter, $node, 'lightbox2');
}

/**
 * Generate the HTML output to open embedded cck images in a lightbox.
 */
function theme_lightbox2_emimage($field, $item, $formatter, $node) {

  $lightbox_type = $field['lightbox_type'];

  if ($item['value'] && $item['provider']) {
    $rel = 'lightbox';
    if ($lightbox_type == 'lightshow2') {
      $rel = 'lightshow';
    }
    elseif ($lightbox_type == 'lightframe2') {
      $rel = 'lightframe';
    }
    if ($rel == 'lightframe' && arg(0) == 'node' && arg(1) == $node->nid) {
      $rel = 'lightbox';
    }
    $orig_rel = $rel;

    $code = $item['value'];
    $width = $field['widget']['thumbnail_width'] == '' ? variable_get('emimage_default_full_width', EMIMAGE_DEFAULT_FULL_WIDTH) : $field['widget']['thumbnail_width'];
    $height = $field['widget']['thumbnail_height'] == '' ? variable_get('emimage_default_full_height', EMIMAGE_DEFAULT_FULL_HEIGHT) : $field['widget']['thumbnail_height'];
    $link = $field['widget']['thumbnail_link'] ? $field['widget']['thumbnail_link'] : variable_get('emimage_default_thumbnail_link', EMIMAGE_DEFAULT_THUMBNAIL_LINK);
    if ($link == EMIMAGE_LINK_CONTENT) {
      $link = 'node/'. $node->nid;
    }
    elseif ($link == EMIMAGE_LINK_PROVIDER) {
      $link = module_invoke('emfield', 'include_invoke', 'emimage', $item['provider'], 'embedded_link', $code, $item['data']);
    }
    else {
      $link = NULL;
    }
    $attributes = array();
    if ($width) {
      $attributes['width'] = $width;
    }
    if ($height) {
      $attributes['height'] = $height;
    }
    $target = variable_get('lightbox2_node_link_target', FALSE);
    if (!empty($target)) {
      $attributes['target'] = $target;
    }

    // Set up the caption.
    $node_link_text = variable_get('lightbox2_node_link_text', 'View Image Details');
    if (!empty($node_link_text) && !empty($link)) {
      $node_link = '<br /><br />'. l($node_link_text, $link, array('attributes' => $attributes));
    }
    $title = module_invoke('emfield', 'include_invoke', 'emimage', $item['provider'], 'image_title', $code, $item['data']);
    $caption = $title . $node_link;

    // Set up url and image.
    $url = module_invoke('emfield', 'include_invoke', 'emimage', $item['provider'], 'image_url', $code, $width, $height, "emimage", $field, $item, $node);
    $image = theme('image', $url, $title, $title, $attributes, FALSE);

    // Set up rel attribute.
    $image_grouping = variable_get('lightbox2_emimage_group_node_id', 1);
    if ($image_grouping == 1) {
      $rel = $rel .'['. $field['field_name'] .']['. $caption .']';
    }
    elseif ($image_grouping == 2 && !empty($node->nid)) {
      $rel = $rel .'['. $node->nid .']['. $caption .']';
    }
    elseif ($image_grouping == 3 && !empty($node->nid)) {
      $rel = $rel .'['. $field['field_name'] . $node->nid .']['. $caption .']';
    }
    else {
      $rel = $rel .'[]['. $caption .']';
    }

    if ($orig_rel != 'lightframe') {
      $link_attributes = array('rel' => $rel);
      $full_width = $field['widget']['full_width'] == '' ? variable_get('emimage_default_full_width', EMIMAGE_DEFAULT_FULL_WIDTH) : $field['widget']['full_width'];
      $full_height = $field['widget']['full_height'] == '' ? variable_get('emimage_default_full_height', EMIMAGE_DEFAULT_FULL_HEIGHT) : $field['widget']['full_height'];
      $full_image_url = module_invoke('emfield', 'include_invoke', 'emimage', $item['provider'], 'image_url', $code, $full_width, $full_height, "emimage", $field, $item, $node);
      $output = l($image, $full_image_url, array('attributes' => $link_attributes, 'html' => TRUE));
    }
    else {
      $frame_width = variable_get('lightbox2_default_frame_width', 600);
      $frame_height = variable_get('lightbox2_default_frame_height', 400);
      $frame_size = 'width:'. $frame_width .'px; height:'. $frame_height .'px;';
      $rel = preg_replace('/\]\[/', "|$frame_size][", $rel);
      $link_attributes = array('rel' => $rel);
      $output = l($image, $link .'/lightbox2', array('attributes' => $link_attributes, 'html' => TRUE));
    }
  }

  return $output;
}

/**
 * Generate the HTML output to open embedded cck videos in a lightbox.
 */
function theme_lightbox2_formatter_emvideo_lightvideo($element) {
  if (!variable_get('lightbox2_enable_video', FALSE)) {
    return;
  }
  $field = content_fields($element['#field_name'], $element['#type_name']);
  $item = $element['#item'];
  $formatter = "emvideo";
  $node = node_load($element['#node']->nid);
  $options = array(); // TODO?

  return module_invoke('emfield', 'emfield_field_formatter', $field, $item, $formatter, $node, 'lightbox2');
}

/**
 * Generate the HTML output to open embedded cck videos in a lightbox.
 */
function theme_lightbox2_emvideo($field, $item, $formatter, $node) {

  $thumbnail = theme('emvideo_video_thumbnail', $field, $item, 'video_thumbnail', $node, TRUE, $options);

  $destination = 'video-cck/lightbox2/'. $node->nid .'/'. $field['widget']['video_width'] .'/'. $field['widget']['video_height'] .'/'. $field['field_name'] .'/'. $item['provider'] .'/'. $item['value'];
  $width = $field['widget']['video_width'] + 20;
  $height = $field['widget']['video_height'] + 20;
  if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)) {
    $width = $field['widget']['video_width'] + 40;
    $height = $field['widget']['video_height'] + 40;
  }
  $rel = 'lightframe[|width:'. $width .'px; height:'. $height .'px; overflow:visible;]';

  $output = l($thumbnail, $destination, array('attributes' => array('rel' => $rel, 'class' => $field['type_name']), 'html' => TRUE));
  return $output;
}

