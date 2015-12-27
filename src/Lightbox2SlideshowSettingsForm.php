<?php
namespace Drupal\lightbox2;

class Lightbox2SlideshowSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lightbox2_slideshow_settings_form';
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


    // Add text box for slideshow interval.
    $form['lightbox2_slideshow_interval'] = [
      '#type' => 'textfield',
      '#title' => t('Interval seconds'),
      '#description' => t('The slideshow interval is the length of time in seconds an image is visible before the slideshow shows the next image.'),
      '#default_value' => variable_get('lightbox2_slideshow_interval', 5),
      '#size' => 20,
    ];

    // Add checkbox for slideshow automatic start.
    $form['lightbox2_slideshow_automatic_start'] = [
      '#type' => 'checkbox',
      '#title' => t('Automatically start slideshow'),
      '#description' => t('When enabled the slideshow will automatically start.'),
      '#default_value' => variable_get('lightbox2_slideshow_automatic_start', TRUE),
    ];

    // Add checkbox for slideshow automatic exit.
    $form['lightbox2_slideshow_automatic_exit'] = [
      '#type' => 'checkbox',
      '#title' => t('Automatically exit slideshow'),
      '#description' => t('When enabled the lightbox will automatically close after displaying the last image.'),
      '#default_value' => variable_get('lightbox2_slideshow_automatic_exit', TRUE),
    ];

    // Add checkbox for showing hte play / pause button.
    $form['lightbox2_slideshow_show_play_pause'] = [
      '#type' => 'checkbox',
      '#title' => t('Show play / pause button'),
      '#description' => t('When enabled, a play / pause button will be shown in the slideshow allowing the user more control over their viewing experience.'),
      '#default_value' => variable_get('lightbox2_slideshow_show_play_pause', TRUE),
    ];

    // Add checkbox for "pausing on next click".
    $form['lightbox2_slideshow_pause_on_next_click'] = [
      '#type' => 'checkbox',
      '#title' => t('Pause slideshow on "Next Image" click'),
      '#description' => t('When enabled the slideshow is automatically paused, and the following image shown, when the "Next" button is clicked.'),
      '#default_value' => variable_get('lightbox2_slideshow_pause_on_next_click', FALSE),
    ];

    // Add checkbox for "pausing on prev click".
    $form['lightbox2_slideshow_pause_on_previous_click'] = [
      '#type' => 'checkbox',
      '#title' => t('Pause slideshow on "Previous Image" click'),
      '#description' => t('When enabled the slideshow is automatically paused, and the previous image shown, when the "Previous" button is clicked.'),
      '#default_value' => variable_get('lightbox2_slideshow_pause_on_previous_click', TRUE),
    ];

    // Add checkbox for "looping through slides'.
    $form['lightbox2_loop_slides'] = [
      '#type' => 'checkbox',
      '#title' => t('Continuous loop'),
      '#description' => t('When enabled the slideshow will automatically start over after displaying the last slide.  This prevents the slideshow from automatically exiting when enabled.'),
      '#default_value' => variable_get('lightbox2_loop_slides', FALSE),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if (!is_numeric($form_state->getValue(['lightbox2_slideshow_interval']))) {
      $form_state->setErrorByName('lightbox2_slideshow_interval', t('The "interval seconds" value must be numeric.'));
    }
  }

}
