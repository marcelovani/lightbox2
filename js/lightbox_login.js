// $Id$

function lightbox2_login() {
  $("a[@href*='/user/login'], a[@href*='?q=user/login']").each(function() {
    $(this).attr({
      href: this.href.replace(/user\/login?/,"user/login/lightbox2"),
      rel: 'lightmodal[|width:250px; height:200px;]'
    });
  });
}

// Initialize the lightbox.
if (Drupal.jsEnabled) {
  $(document).ready(function(){
    lightbox2_login();
  });
}

