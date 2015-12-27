<?php

/**
 * @file
 * Contains \Drupal\lightbox2\Form\Lightbox2IframeSettingsForm.
 */

namespace Drupal\lightbox2\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class Lightbox2IframeSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lightbox2_iframe_settings_form';
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


    // Add text box for iframe width.
    $form['lightbox2_default_frame_width'] = [
      '#type' => 'textfield',
      '#title' => t('Default width'),
      '#description' => t('The default width of the iframe in pixels.'),
      '#default_value' => variable_get('lightbox2_default_frame_width', 600),
      '#size' => 20,
    ];

    // Add text box for iframe height.
    $form['lightbox2_default_frame_height'] = [
      '#type' => 'textfield',
      '#title' => t('Default height'),
      '#description' => t('The default height of the iframe in pixels.'),
      '#default_value' => variable_get('lightbox2_default_frame_height', 400),
      '#size' => 20,
    ];

    // Add option for iframe border.
    $form['lightbox2_frame_border'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable border'),
      '#description' => t('Enable iframe border.  You can modify the border style in your theme\'s css file using the iframe\'s id "lightboxFrame".'),
      '#default_value' => variable_get('lightbox2_frame_border', 1),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if (!is_numeric($form_state->getValue(['lightbox2_default_frame_width']))) {
      $form_state->setErrorByName('lightbox2_slideshow_interval', t('The "default width" value must be numeric.'));
    }
    if (!is_numeric($form_state->getValue(['lightbox2_default_frame_height']))) {
      $form_state->setErrorByName('lightbox2_slideshow_interval', t('The "default height" value must be numeric.'));
    }
  }

}
