<?php

/**
 * @file
 * Contains \Drupal\lightbox2\Form\Lightbox2AutoImageHandlingSettingsForm.
 */

namespace Drupal\lightbox2\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class Lightbox2AutoImageHandlingSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lightbox2_auto_image_handling_settings_form';
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

    // Set up a hidden variable.
    $form['lightbox2_lite'] = [
      '#type' => 'hidden',
      '#value' => variable_get('lightbox2_lite', FALSE),
    ];

    $automatic_options = [
      0 => t('Disabled'),
      1 => t('Lightbox'),
      2 => t('Lightbox grouped'),
      3 => t('Slideshow'),
      4 => t('HTML content'),
      5 => t('HTML content grouped'),
    ];

    // Set image node options help text.
    $form['image_node_options'] = [
      '#value' => t('These options allow automatic URL re-formatting of images.  This removes the need for you to add \'rel="lightbox"\' to each image link throughout your site.  You can select which image sizes will trigger the lightbox and configure a list of image CSS classes which should also have their URLs automatically re-formatted.  This feature is not available when using Lightbox2 Lite.')
      ];

    // Define handler settings fieldset.
  /* --------------------------------- */
    $form['handler_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Automatic handlers'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    // Define image nodes settings fieldset.
  /* ------------------------------------- */
    $form['handler_options']['image_node_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Image node settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    // Add checkbox for image nodes.
    $form['handler_options']['image_node_options']['lightbox2_image_node'] = [
      '#type' => 'select',
      '#title' => t('Automatic handler for image nodes'),
      '#options' => $automatic_options,
      '#description' => t('Choose how URLs for image nodes will be automatically handled.'),
      '#default_value' => variable_get('lightbox2_image_node', 0),
    ];

    /*
  // Add drop-down for list of available image sizes.
  if (module_exists('image')) {
    $sizes = image_get_sizes();
    foreach ($sizes as $size_key => $size) {
      if ($size_key == 'original' or $size_key == '_original')  {
        $size_key = 'original';
      }
      $size_options[$size_key] = $size['label'];
    }
    $form['handler_options']['image_node_options']['lightbox2_display_image_size'] = array(
      '#type' => 'select',
      '#title' => t('Lightbox image display size'),
      '#options' => $size_options,
      '#default_value' => variable_get('lightbox2_display_image_size', 'original'),
      '#description' => t('Select which image size will be loaded in the lightbox.  This only applies to images uploaded with the Image module.'),
    );

    $form['handler_options']['image_node_options']['lightbox2_trigger_image_size'] = array(
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Image trigger size'),
      '#options' => $size_options,
      '#default_value' => variable_get('lightbox2_trigger_image_size', array('thumbnail')),
      '#description' => t('Select which image size, when clicked on, will automatically trigger the lightbox.  This only applies to images uploaded with the Image module.'),
    );

  }
  */

    // Add checkbox for disabling lightbox for gallery lists.
    $form['handler_options']['image_node_options']['lightbox2_disable_nested_galleries'] = [
      '#type' => 'checkbox',
      '#title' => t('Disable lightbox for gallery lists'),
      '#description' => t('Checking this box will disable the lightbox for images in gallery lists.  This means it is possible to open a gallery by clicking on the teaser image, but the lightbox will still appear when viewing images within the gallery.  This only applies to image galleries created with the "image gallery" module.'),
      '#default_value' => variable_get('lightbox2_disable_nested_galleries', TRUE),
    ];

    // Define flickr fieldset.
  /* ----------------------- */
    $form['handler_options']['flickr_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Flickr images'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Add checkbox for flickr image support.
    $form['handler_options']['flickr_options']['lightbox2_flickr'] = [
      '#type' => 'select',
      '#title' => t('Automatic handler for Flickr images'),
      '#options' => $automatic_options,
      '#description' => t('Choose how URLs for Flickr images will be automatically handled.'),
      '#default_value' => variable_get('lightbox2_flickr', 0),
    ];

    // Define gallery2 fieldset.
  /* ------------------------- */
    $form['handler_options']['gallery2_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Gallery2 images'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Add checkbox for gallery2 block image support.
    $form['handler_options']['gallery2_options']['lightbox2_gallery2_blocks'] = [
      '#type' => 'select',
      '#title' => t('Automatic handler for Gallery2 block images'),
      '#options' => $automatic_options,
      '#description' => t('Choose how URLs for images, contained within Gallery2 image blocks, will be automatically handled.'),
      '#default_value' => variable_get('lightbox2_gallery2_blocks', 0),
    ];

    // Define inline fieldset.
  /* ----------------------- */
    $form['handler_options']['inline_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Inline module images'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Add checkbox for inline image support.
    $form['handler_options']['inline_options']['lightbox2_inline'] = [
      '#type' => 'select',
      '#title' => t('Automatic handler for Inline module images'),
      '#options' => $automatic_options,
      '#description' => t('Choose how URLs for Inline module images will be automatically handled.'),
      '#default_value' => variable_get('lightbox2_inline', 0),
    ];

    // Define image assist fieldset.
  /* ----------------------------- */
    $form['handler_options']['img_assist_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Image Assist images'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Add checkbox for image assist custom size images.
    $form['handler_options']['img_assist_options']['lightbox2_image_assist_custom'] = [
      '#type' => 'select',
      '#title' => t('Automatic handler for Image Assist custom size images'),
      '#options' => $automatic_options,
      '#description' => t('Choose how URLs for custom size images, displayed by the Image Assist module, will be automatically handled.'),
      '#default_value' => variable_get('lightbox2_image_assist_custom', 0),
    ];

    // Define custom fieldset.
  /* ----------------------- */
    $form['handler_options']['custom_options'] = [
      '#type' => 'fieldset',
      '#title' => t('Custom class images'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    // Add checkbox for custom class image support.
    $form['handler_options']['custom_options']['lightbox2_custom_class_handler'] = [
      '#type' => 'select',
      '#title' => t('Automatic handler for custom class images'),
      '#options' => $automatic_options,
      '#description' => t('Choose how URLs for custom class images will be automatically handled.'),
      '#default_value' => variable_get('lightbox2_custom_class_handler', 0),
    ];
    // Add text box for custom trigger classes.
    $form['handler_options']['custom_options']['lightbox2_custom_trigger_classes'] = [
      '#type' => 'textarea',
      '#title' => t('Custom image trigger classes'),
      '#description' => t('List the image classes which should trigger the lightbox when clicked on.  Put each class on a separate line.'),
      '#default_value' => variable_get('lightbox2_custom_trigger_classes', ''),
    ];

    // Add checkbox for disabling lightbox for acidfree gallery lists.
    if (module_exists("acidfree")) {
      // Define acidfree settings fieldset.
    /* ---------------------------------- */
      $form['handler_options']['lightbox2_acidfree_options'] = [
        '#type' => 'fieldset',
        '#title' => t('Acidfree settings'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      ];
      $form['handler_options']['lightbox2_acidfree_options']['lightbox2_disable_nested_acidfree_galleries'] = [
        '#type' => 'checkbox',
        '#title' => t('Disable lightbox for Acidfree gallery lists'),
        '#description' => t('Checking this box will disable the lightbox for images in gallery lists.  This means it is possible to open a gallery by clicking on the teaser image, but the lightbox will still appear when viewing images within the gallery.  This only applies to image galleries created with the "acidfree" module.'),
        '#default_value' => variable_get('lightbox2_disable_nested_acidfree_galleries', TRUE),
      ];
      // Add checkbox for enabling lightbox for acidfree videos.
      if (module_exists("video")) {
        $form['handler_options']['lightbox2_acidfree_options']['lightbox2_enable_acidfree_videos'] = [
          '#type' => 'checkbox',
          '#title' => t('Enable lightbox for Acidfree videos'),
          '#description' => t('Checking this box will enable the display of acidfree videos in a lightbox.  This only applies to videos created by the "video" module and which appear in an acidfree gallery.'),
          '#default_value' => variable_get('lightbox2_enable_acidfree_videos', FALSE),
        ];
      }
    }

    return parent::buildForm($form, $form_state);
  }

}
