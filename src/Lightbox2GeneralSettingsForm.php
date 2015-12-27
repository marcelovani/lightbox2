<?php
namespace Drupal\lightbox2;

class Lightbox2GeneralSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lightbox2_general_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('lightbox2.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['lightbox2.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

    // Add the javascript which disables / enables form elements.
    drupal_add_js(drupal_get_path('module', 'lightbox2') . '/js/lightbox2.js', 'module');

    // Enable translation of default strings for potx.
    $default_strings = [
      t('View Image Details'),
      t('Image !current of !total'),
      t('Page !current of !total'),
      t('Video !current of !total'),
      t('Download Original'),
    ];

    // Define Lightbox2 layout fieldset.
  /* --------------------------------- */
    $form['layout_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => t('Layout settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    // Define Lightbox2 Lite fieldset.
  /* ------------------------------- */
    $use_lite = variable_get('lightbox2_lite', FALSE);
    $form['layout_fieldset']['lightbox2_lite_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Lightbox2 lite'),
      '#collapsible' => TRUE,
      '#collapsed' => !$use_lite,
    ];

    // Add checkbox for Lightbox2 Lite.
    $form['layout_fieldset']['lightbox2_lite_options']['lightbox2_lite'] = [
      '#type' => 'checkbox',
      '#title' => t('Use lightbox2 lite'),
      '#description' => t('Checking this box will enable Lightbox2 Lite and will disable all of the automatic image URL re-formatting features.  It also disables all grouping features.'),
      '#default_value' => $use_lite,
    ];


    // Add checkbox for alternative layout.
    $form['layout_fieldset']['lightbox2_use_alt_layout'] = [
      '#type' => 'checkbox',
      '#title' => t('Use alternative layout'),
      '#description' => t('Enabling this option alters the layout of the lightbox elements. In the alternative layout the navigational links appear under the image with the caption text, instead of being overlayed on the image itself.  This doesn\'t apply when using Lightbox Lite.'),
      '#default_value' => variable_get('lightbox2_use_alt_layout', FALSE),
    ];

    // Add checkbox for force navigation display.
    $form['layout_fieldset']['lightbox2_force_show_nav'] = [
      '#type' => 'checkbox',
      '#title' => t('Force visibility of navigation links'),
      '#description' => t('When viewing grouped images, the navigational links to the next and previous images are only displayed when you hover over the image.  Checking this box forces these links to be displayed all the time.'),
      '#default_value' => variable_get('lightbox2_force_show_nav', FALSE),
    ];

    // Show caption
    $form['layout_fieldset']['lightbox2_show_caption'] = [
      '#type' => 'checkbox',
      '#title' => t('Show image caption'),
      '#description' => t('Unset this to always hide the image caption (usually the title).'),
      '#default_value' => variable_get('lightbox2_show_caption', TRUE),
    ];

    // Add checkbox for "looping through images'.
    $form['layout_fieldset']['lightbox2_loop_items'] = [
      '#type' => 'checkbox',
      '#title' => t('Continuous galleries'),
      '#description' => t('When viewing grouped images, the Next button on the last image will display the first image, while the Previous button on the first image will display the last image.'),
      '#default_value' => variable_get('lightbox2_loop_items', FALSE),
    ];

    // Add checkbox for node link target.
    $form['layout_fieldset']['lightbox2_node_link_target'] = [
      '#type' => 'checkbox',
      '#title' => t('Open image page in new window'),
      '#description' => t('This controls whether the link to the image page underneath the image is opened in a new window or the current window.'),
      '#default_value' => variable_get('lightbox2_node_link_target', FALSE),
      '#return_value' => '_blank',
    ];

    // Define Lightbox2 text settings.
  /* ------------------------------- */
    $form['lightbox2_text_settings'] = [
      '#type' => 'fieldset',
      '#title' => t('Text settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    // Add text box for link text to node.
    $form['lightbox2_text_settings']['lightbox2_node_link_text'] = [
      '#type' => 'textfield',
      '#title' => t('Text for image page link'),
      '#description' => t('This is the text that will appear as the link to the image page underneath the image in the lightbox.  Leave this blank for the link not to appear.'),
      '#default_value' => variable_get('lightbox2_node_link_text', 'View Image Details'),
    ];

    // Add text box for link text to node.
    $form['lightbox2_text_settings']['lightbox2_download_link_text'] = [
      '#type' => 'textfield',
      '#title' => t('Text for image original link'),
      '#description' => t('This is the text that will appear as the link to the original file underneath the image in the lightbox.  Leave this blank for the link not to appear.  It will only appear for images uploaded via the "image" or "imagefield" modules.  Users will need the "download original image" permission, but also the "view original images" permission if using the "image" module.'),
      '#default_value' => variable_get('lightbox2_download_link_text', 'Download Original'),
    ];

    // Add text box for image count for grouping.
    $form['lightbox2_text_settings']['lightbox2_image_count_str'] = [
      '#type' => 'textfield',
      '#title' => t('Image count text'),
      '#description' => t('This text is used to display the image count underneath the image in the lightbox when image grouping is enabled.  Use !current as a placeholder for the number of the current image and !total for the total number of images in the group.  For example, "Image !current of !total".  Leave blank for text not to appear.'),
      '#default_value' => variable_get('lightbox2_image_count_str', 'Image !current of !total'),
    ];

    // Add text box for page count for grouping.
    $form['lightbox2_text_settings']['lightbox2_page_count_str'] = [
      '#type' => 'textfield',
      '#title' => t('Page count text'),
      '#description' => t('This text is used to display the page count underneath HTML content displayed in the lightbox when using groups.  Use !current as a placeholder for the number of the current page and !total for the total number of pages in the group.  For example, "Page !current of !total".  Leave blank for text not to appear.'),
      '#default_value' => variable_get('lightbox2_page_count_str', 'Page !current of !total'),
    ];

    // Add text box for video count for grouping.
    $form['lightbox2_text_settings']['lightbox2_video_count_str'] = [
      '#type' => 'textfield',
      '#title' => t('Video count text'),
      '#description' => t('This text is used to display the count underneath video content displayed in the lightbox when using groups.  Use !current as a placeholder for the number of the current video and !total for the total number of videos in the group.  For example, "Video !current of !total".  Leave blank for text not to appear.'),
      '#default_value' => variable_get('lightbox2_video_count_str', 'Video !current of !total'),
    ];

    // Add text box for video count for grouping.
    $form['lightbox2_text_settings']['lightbox2_filter_xss_allowed_tags'] = [
      '#type' => 'textfield',
      '#title' => t('Allowed HTML tags'),
      '#description' => t('A list of comma separated HTML tags which are allowed to be used in the caption text area.'),
      '#default_value' => variable_get('lightbox2_filter_xss_allowed_tags', 'p, br, a, em, strong, cite, code, ul, ol, li, dl, dt, dd'),
    ];


    // Define Lightbox2 zoom fieldset.
  /* -------------------------------- */
    $form['zoom_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => t('Image resize settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    // Add checkbox for resize image.
    $form['zoom_fieldset']['lightbox2_disable_resize'] = [
      '#type' => 'checkbox',
      '#title' => t('Disable resizing feature'),
      '#description' => t('By default, when the image being displayed in the lightbox is larger than the browser window, it is resized to fit within the window and a zoom button is provided for users who wish to view the image in its original size.  Checking this box will disable this feature and all images will be displayed without any resizing.'),
      '#default_value' => variable_get('lightbox2_disable_resize', FALSE),
    ];

    // Add checkbox for zoom image.
    $form['zoom_fieldset']['lightbox2_disable_zoom'] = [
      '#type' => 'checkbox',
      '#title' => t('Disable zoom in / out feature'),
      '#description' => t('When the image being displayed in the lightbox is resized to fit in the browser window, a "zoom in" button is shown.  This allows the user to zoom in to see the original full size image.  They will then see a "zoom out" button which will allow them to see the smaller resized version.  Checking this box will prevent these buttons from appearing.'),
      '#default_value' => variable_get('lightbox2_disable_zoom', FALSE),
    ];

    // Define Lightbox2 modal form fieldset.
  /* -------------------------------------- */
    $form['modal_form_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => t('Modal form settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    // Add checkbox for login support.
    $form['modal_form_fieldset']['lightbox2_enable_login'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable login support'),
      '#description' => t('Enabling this option will modify all login links so that the login form appears in a lightbox.'),
      '#default_value' => variable_get('lightbox2_enable_login', FALSE),
    ];

    // Add checkbox for contact form support.
    $form['modal_form_fieldset']['lightbox2_enable_contact'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable contact form support'),
      '#description' => t('Enabling this option will modify all contact links so that the contact form appears in a lightbox.'),
      '#default_value' => variable_get('lightbox2_enable_contact', FALSE),
    ];

    // Define Lightbox2 video fieldset.
  /* -------------------------------- */
    $form['video_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => t('Video settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    // Add checkbox for video support.
    $form['video_fieldset']['lightbox2_enable_video'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable video support'),
      '#description' => t('By default, video support is disabled in order to reduce the amount of javascript needed.  Checking this box will enable it.'),
      '#default_value' => variable_get('lightbox2_enable_video', FALSE),
    ];

    // Add textfield for FLV Player path.
    $form['video_fieldset']['lightbox2_flv_player_path'] = [
      '#type' => 'textfield',
      '#title' => t('Path to FLV Player'),
      '#description' => t('The path to the FLV player, relative to Drupal root directory. No leading slashes.'),
      '#default_value' => variable_get('lightbox2_flv_player_path', 'flvplayer.swf'),
    ];

    // Add textfield for FLV Player flashvars.
    $form['video_fieldset']['lightbox2_flv_player_flashvars'] = [
      '#type' => 'textfield',
      '#title' => t('FLV Player flashvars'),
      '#description' => t('Flashvars for the FLV Player where supported, e.g. "autoplay=1&playerMode=normal".'),
      '#default_value' => variable_get('lightbox2_flv_player_flashvars', ''),
    ];

    // Define Lightbox2 page specific settings fieldset.
  /* ------------------------------------------------ */
    $form['lightbox2_page_init'] = [
      '#type' => 'fieldset',
      '#title' => t('Page specific lightbox2 settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Add radio buttons for the actions to take for the listed pages, i.e.
    // disable or enable the lightbox functionality.
    $page_options = [
      'page_enable' => t('Load only on the listed pages.'),
      'page_disable' => t('Load on every page except the listed pages.'),
    ];
    $form['lightbox2_page_init']['lightbox2_page_init_action'] = [
      '#type' => 'radios',
      '#options' => $page_options,
      '#title' => t('Enable lightbox2 on specific pages'),
      '#default_value' => variable_get('lightbox2_page_init_action', 'page_disable'),
    ];
    // Add text input for list of pages to take specific action on.
    $form['lightbox2_page_init']['lightbox2_page_list'] = [
      '#type' => 'textarea',
      '#title' => t('Pages'),
      '#description' => t('List one page per line as Drupal paths.  The * character is a wildcard.  Example paths are "node/add/page" and "node/add/*".  Use &lt;front&gt; to match the front page.'),
      '#default_value' => variable_get('lightbox2_page_list', ''),
    ];

    // Define grouping settings fieldset.
  /* ------------------------------------ */
    $form['group_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Group display settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $group_options = [
      0 => t('No grouping'),
      1 => t('Group by field name'),
      2 => t('Group by node id'),
      3 => t('Group by field name and node id'),
      4 => t('Group all nodes and fields'),
    ];

    $form['group_options']['lightbox2_image_group_node_id'] = [
      '#type' => 'select',
      '#title' => t('Select Imagefield grouping in Views'),
      '#description' => t('By default, imagefields in views are grouped by the field name they appear in the view in.  You can override that grouping here.'),
      '#options' => $group_options,
      '#default_value' => variable_get('lightbox2_image_group_node_id', 1),
    ];


    // Define advanced settings fieldset.
  /* ---------------------------------- */
    $form['advanced_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Advanced settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    // Allow users to decide where javascript should be loaded - header or footer.
    // Header is recommended so user can click on images before page has finished
    // loading, but footer is needed for sites in IE which use SWFObject.
    $form['advanced_options']['lightbox2_js_location'] = [
      '#type' => 'select',
      '#title' => t('Location of javscript'),
      '#options' => [
        'header' => t('Header'),
        'footer' => t('Footer'),
      ],
      '#description' => t('By default, the lightbox javascript files are loaded in the HTML header.  However, for sites using SWFObject to load their Flash content, the footer setting is recommended to prevent "Operation Aborted" errors in IE.  If using the footer setting, please note that not all themes correctly implement the footer region and may require a small change.'),
      '#default_value' => variable_get('lightbox2_js_location', 'header'),
    ];

    $form['advanced_options']['lightbox2_disable_close_click'] = [
      '#type' => 'checkbox',
      '#title' => t('Click on overlay or lightbox to close it'),
      '#description' => t('Enable user to close lightbox by clicking on the lightbox itself or the overlay background.'),
      '#default_value' => variable_get('lightbox2_disable_close_click', TRUE),
    ];

    // Define keyboard shortcuts fieldset.
  /* ---------------------------------- */
    $form['advanced_options']['keyboard_shortcuts'] = [
      '#type' => 'fieldset',
      '#title' => t('Keyboard shortcuts'),
      '#description' => t('Configure the keyboard shortcuts for controlling the lightbox.  These options do not apply to the Lightbox2 Lite version, which uses the default "Close keys" (c, x and esc) to close the lightbox.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['advanced_options']['keyboard_shortcuts']['lightbox2_keys_close'] = [
      '#type' => 'textfield',
      '#title' => t('Close keys'),
      '#description' => t("A list of keys (or key codes) that a user may use to close the lightbox. Values should be separated by a space. Defaults to 'c x 27' (c, x, or esc)."),
      '#default_value' => variable_get('lightbox2_keys_close', 'c x 27'),
    ];

    $form['advanced_options']['keyboard_shortcuts']['lightbox2_keys_previous'] = [
      '#type' => 'textfield',
      '#title' => t('Previous keys'),
      '#description' => t("A list of keys (or key codes) that a user may use to navigate to the previous item in the lightbox. Values should be separated by a space. Defaults to 'p 37' (p or left arrow)."),
      '#default_value' => variable_get('lightbox2_keys_previous', 'p 37'),
    ];

    $form['advanced_options']['keyboard_shortcuts']['lightbox2_keys_next'] = [
      '#type' => 'textfield',
      '#title' => t('Next keys'),
      '#description' => t("A list of keys (or key codes) that a user may use to navigate to the next item in the lightbox. Values should be separated by a space. Defaults to 'n 39' (n or right arrow)."),
      '#default_value' => variable_get('lightbox2_keys_next', 'n 39'),
    ];

    $form['advanced_options']['keyboard_shortcuts']['lightbox2_keys_zoom'] = [
      '#type' => 'textfield',
      '#title' => t('Zoom keys'),
      '#description' => t("A list of keys (or key codes) that a user may use to zoom in / out of images in the lightbox. Values should be separated by a space. Defaults to 'z'."),
      '#default_value' => variable_get('lightbox2_keys_zoom', 'z'),
    ];

    $form['advanced_options']['keyboard_shortcuts']['lightbox2_keys_play_pause'] = [
      '#type' => 'textfield',
      '#title' => t('Pause / play keys'),
      '#description' => t("A list of keys (or key codes) that a user may use to pause / play the lightbox. Values should be separated by a space. Defaults to '32' (spacebar)."),
      '#default_value' => variable_get('lightbox2_keys_play_pause', '32'),
    ];




    // Define border settings fieldset.
  /* ---------------------------------- */
    $form['advanced_options']['skin_settings'] = [
      '#type' => 'fieldset',
      '#title' => t('Skin settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['advanced_options']['skin_settings']['lightbox2_border_size'] = [
      '#type' => 'textfield',
      '#title' => t('Border size'),
      '#size' => 6,
      '#maxlength' => 6,
      '#description' => t('Enter the size of the border in pixels to display around the image.'),
      '#default_value' => variable_get('lightbox2_border_size', 10),
    ];

    $form['advanced_options']['skin_settings']['lightbox2_box_color'] = [
      '#type' => 'textfield',
      '#title' => t('Lightbox color'),
      '#field_prefix' => '#',
      '#size' => 6,
      '#maxlength' => 6,
      '#description' => t('Enter a hexadecimal color value for the border.  For example <code>fff</code> or <code>ffffff</code> for white).'),
      '#default_value' => variable_get('lightbox2_box_color', 'fff'),
    ];

    $form['advanced_options']['skin_settings']['lightbox2_font_color'] = [
      '#type' => 'textfield',
      '#title' => t('Font color'),
      '#field_prefix' => '#',
      '#size' => 6,
      '#maxlength' => 6,
      '#description' => t('Enter a hexadecimal color value for the font.  For example <code>000</code> or <code>000000</code> for black).'),
      '#default_value' => variable_get('lightbox2_font_color', '000'),
    ];

    $form['advanced_options']['skin_settings']['lightbox2_top_position'] = [
      '#type' => 'textfield',
      '#title' => t('Distance from top'),
      '#size' => 6,
      '#maxlength' => 6,
      '#description' => t('Enter the position of the top of the lightbox in pixels.  Leave blank for automatic calculation.'),
      '#default_value' => variable_get('lightbox2_top_position', ''),
    ];



    // Define overlay settings fieldset.
  /* ---------------------------------- */
    $form['advanced_options']['overlay_settings'] = [
      '#type' => 'fieldset',
      '#title' => t('Overlay settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Add checkbox for overlay opacity.
    for ($i = 0; $i < 10; $i++) {
      $opacity_options["0.$i"] = "0.$i";
    }
    $opacity_options["1.0"] = "1.0";
    $form['advanced_options']['overlay_settings']['lightbox2_overlay_opacity'] = [
      '#type' => 'select',
      '#title' => t('Overlay opacity'),
      '#options' => $opacity_options,
      '#description' => t('The overlay opacity setting determines how visible the background page is behind the lightbox.  The opacity value can range from 0.0 to 1.0 where 0.0 is 100% transparent and 1.0 is 100% opaque.'),
      '#default_value' => variable_get('lightbox2_overlay_opacity', 0.8),
    ];

    $form['advanced_options']['overlay_settings']['lightbox2_overlay_color'] = [
      '#type' => 'textfield',
      '#title' => t('Overlay color'),
      '#field_prefix' => '#',
      '#size' => 6,
      '#maxlength' => 6,
      '#description' => t('Enter a hexadecimal color value for the overlay.  For example <code>000</code> or <code>000000</code> for black).'),
      '#default_value' => variable_get('lightbox2_overlay_color', '000'),
    ];


    // Define animation settings fieldset.
  /* ----------------------------------- */
    $form['advanced_options']['animation_settings'] = [
      '#type' => 'fieldset',
      '#title' => t('Animation settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Set animation help text.
    $form['advanced_options']['animation_settings']['animation_options_help'] = [
      '#value' => '<p>' . t("These options aren't available when using Lightbox2 Lite.") . '</p>'
      ];
    $resize_sequence_options = [
      t('Simultaneous'),
      t('Width then height'),
      t('Height then width'),
    ];
    $form['advanced_options']['animation_settings']['lightbox2_resize_sequence'] = [
      '#type' => 'select',
      '#title' => t('Resize sequence'),
      '#options' => $resize_sequence_options,
      '#description' => t('The sequence to use for the resizing animation.'),
      '#default_value' => variable_get('lightbox2_resize_sequence', 0),
    ];
    $form['advanced_options']['animation_settings']['lightbox2_resize_speed'] = [
      '#type' => 'textfield',
      '#title' => t('Resize duration'),
      '#size' => 5,
      '#maxlength' => 5,
      '#description' => t('The duration (in seconds) of the resizing animation.  Enter a value between 0 and 10.'),
      '#default_value' => variable_get('lightbox2_resize_speed', 0.4),
    ];
    $form['advanced_options']['animation_settings']['lightbox2_fadein_speed'] = [
      '#type' => 'textfield',
      '#title' => t('Appearance duration'),
      '#size' => 5,
      '#maxlength' => 5,
      '#description' => t('The duration (in seconds) of the lightbox appearance animation.  Enter a positive number less than 10.'),
      '#default_value' => variable_get('lightbox2_fadein_speed', 0.4),
    ];


    $form['advanced_options']['animation_settings']['lightbox2_slidedown_speed'] = [
      '#type' => 'textfield',
      '#title' => t('Caption slide down duration'),
      '#size' => 5,
      '#maxlength' => 5,
      '#description' => t('The duration (in seconds) of the caption sliding-down animation.  Enter a value between 0 and 10.'),
      '#default_value' => variable_get('lightbox2_slidedown_speed', 0.6),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if ($form_state->getValue(['op']) == t('Save configuration')) {
      $border_size = $form_state->getValue(['lightbox2_border_size']);
      $box_hex_colour = $form_state->getValue(['lightbox2_box_color']);
      $font_hex_colour = $form_state->getValue(['lightbox2_font_color']);
      $top_position = $form_state->getValue(['lightbox2_top_position']);
      $overlay_hex_colour = $form_state->getValue(['lightbox2_overlay_color']);
      $resize_speed = $form_state->getValue(['lightbox2_resize_speed']);
      $fadein_speed = $form_state->getValue(['lightbox2_fadein_speed']);
      $slide_down_speed = $form_state->getValue(['lightbox2_slidedown_speed']);
      $flv_player_path = $form_state->getValue(['lightbox2_flv_player_path']);

      if (!empty($flv_player_path) && $form_state->getValue([
        'lightbox2_enable_video'
        ])) {
        if (strpos($flv_player_path, base_path()) === 0) {
          $flv_player_path = drupal_substr($flv_player_path, drupal_strlen(base_path()));
        }
        if (!file_exists($flv_player_path)) {
          $form_state->setErrorByName('lightbox2_flv_player_path', t("FLV player path doesn't exist."));
        }
      }

      if (!_lightbox2_validate_hex_color($overlay_hex_colour)) {
        $form_state->setErrorByName('lightbox2_overlay_color', t('You must enter a properly formed hex value.'));
      }

      if (!$form_state->getValue(['lightbox2_lite'])) {
        if (!is_numeric($border_size) || $border_size < 0) {
          $form_state->setErrorByName('lightbox2_border_size', t('You must enter a size greater than 0 pixels.'));
        }

        if (!_lightbox2_validate_hex_color($box_hex_colour)) {
          $form_state->setErrorByName('lightbox2_box_color', t('You must enter a properly formed hex value.'));
        }

        if (!_lightbox2_validate_hex_color($font_hex_colour)) {
          $form_state->setErrorByName('lightbox2_font_color', t('You must enter a properly formed hex value.'));
        }

        if (!empty($top_position) && (!is_numeric($top_position) || $top_position < 0)) {
          $form_state->setErrorByName('lightbox2_top_position', t('You must enter a size greater than 0 pixels.  Leave blank for default positioning.'));
        }

        if (!is_numeric($resize_speed) || $resize_speed <= 0 || $resize_speed >= 10) {
          $form_state->setErrorByName('lightbox2_resize_speed', t('You must enter a duration between 0 and 10 seconds.'));
        }

        if (!is_numeric($fadein_speed) || $fadein_speed < 0 || $fadein_speed >= 10) {
          $form_state->setErrorByName('lightbox2_fadein_speed', t('You must enter a duration between 0 and 10 seconds.'));
        }

        if (!is_numeric($slide_down_speed) || $slide_down_speed <= 0 || $slide_down_speed >= 10) {
          $form_state->setErrorByName('lightbox2_slidedown_speed', t('You must enter a duration between 0 and 10 seconds.'));
        }
      }
    }
  }

  public function _submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    cache_clear_all();
  }

}
