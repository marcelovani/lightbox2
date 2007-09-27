// Image Node Auto-Format
// Original version by Steve McKenzie
// Altered by Stella Power for jQuery version


if (Drupal.jsEnabled) {
  $(document).ready(function lightbox2_image_nodes() {
    var img_assist = document.getElementById("img_assist_thumbs");
    if (!img_assist) {
      $("a[img.inline, img.image-thumbnail, img.thumbnail]").each(function(i) {

        var child = $(this).children();
        var alt = $(child).attr("alt");
        var link_text = "View Image Information";
        $(this).attr({rel: "lightbox",
          title: alt + "<br /><br /><a href=\"" + this.href + "\">"+ link_text + "</a>",
          href: $(child).attr("src").replace(".thumbnail", "")
          });

      });
    }

  });
}

