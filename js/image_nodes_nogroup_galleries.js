// Image Node Auto-Format with lightbox for nested gallery links
// Stella Power

if (Drupal.jsEnabled) {
  $(document).ready(function lightbox2_image_nodes() {
   var img_assist = document.getElementById("img_assist_thumbs");
    if (!img_assist) {
      // apply to all other images
      $("a[img.inline, img.image-thumbnail, img.thumbnail]").each(function(i) {
        if (!$(this).parents(".galleries").length ) {

        var child = $(this).children();
        var alt = $(child).attr("alt");
        var link_text = Drupal.t("View Image Information");
        $(this).attr({rel: "lightbox",
          title: alt + "<br /><br /><a href=\"" + this.href + "\">"+ link_text + "</a>",
          href: $(child).attr("src").replace(".thumbnail", "")
          });
        }

      });
    }
  });
}

