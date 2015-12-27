<?php
namespace Drupal\lightbox2;

/**
 * Default controller for the lightbox2 module.
 */
class DefaultController extends ControllerBase {

  public function lightbox2_filter_xss() {
    $allowed_tags = trim(variable_get('lightbox2_filter_xss_allowed_tags', 'p, br, a, em, strong, cite, code, ul, ol, li, dl, dt, dd, '));
    $allowed_tags = (empty($allowed_tags) ? [] : preg_split('/[,\s]+/', $allowed_tags));
    if (!empty($_POST['allowed_tags']) && $_POST['allowed_tags'] != 'undefined') {
      $allowed_tags = explode(',', $_POST['allowed_tags']);
      $output = filter_xss($_POST['string'], $allowed_tags);
    }
    else {
      $output = filter_xss($_POST['string'], $allowed_tags);
    }
    drupal_json_output($output);
  }

  public function lightbox2_settings_page($op = NULL) {
    $output = drupal_get_form('lightbox2_general_settings_form');
    return $output;
  }

  public function lightbox2_login() {

    // do not use lightbox2 for failed validation ie: bad password
  // instead, return the fully rendered Drupal page with errors.
    if (count($_POST)) {
      return drupal_get_form('user_login_block');
    }
    else {
      print drupal_render(drupal_get_form('user_login_block'));
      // If the OpenID module is enabled, the javascript and css may not exist
      // on the page, so add them dynamically.
      if (module_exists('openid')) {
        $path = drupal_get_path('module', 'openid');
        $js_file = base_path() . $path . '/openid.js';
        $css_file = base_path() . $path . '/openid.css';

        // Load the javascript dynamically.
        print '<script type="text/javascript">$.getScript("' . $js_file . '", function () {if ($.isFunction(Drupal.behaviors.openid)) { Drupal.behaviors.openid(document); } });</script>';

        // Load the css file dynamically.
        print '<script type="text/javascript">
        var fileref=document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", "' . $css_file . '");
        document.getElementsByTagName("head")[0].appendChild(fileref);
        </script>';
      }

      // drupal_add_js() with 'inline' didn't seem to work, possibly because this is
      // AJAX loaded content.
      print '<script type="text/javascript">Drupal.attachBehaviors();</script>';
    }
    exit;
  }

  public function lightbox2_contact() {
    if (module_exists('contact') && variable_get('lightbox2_enable_contact', FALSE) && user_access('access site-wide contact form')) {
      $path = drupal_get_path('module', 'contact');
      include_once($path . '/contact.pages.inc');
      print drupal_render(drupal_get_form('contact_site_form'));
      // drupal_add_js() with 'inline' didn't seem to work, possibly because this is
      // AJAX loaded content.
      print '<script type="text/javascript">Drupal.attachBehaviors();</script>';
      exit;
    }
  }

}
