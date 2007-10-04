// Image Node Auto-Format with Auto Image Grouping
// Original version by Steve McKenzie
// Altered by Stella Power for jQuery version

if (Drupal.jsEnabled) {
  $(document).ready(function lightbox2_image_nodes() {

    var settings = Drupal.settings.lightbox2;

    var img_assist = document.getElementById("img_assist_thumbs");
    if (!img_assist) {
      $("a[img.inline, img.image-thumbnail, img.thumbnail]").each(function(i) {
        if (!settings.disable_for_gallery_lists || (settings.disable_for_gallery_lists && !$(this).parents(".galleries").length)) {

          var child = $(this).children();
          var alt = $(child).attr("alt");
          var link_text = settings.node_link_text;
          var rel = "lightbox";
          if (settings.group_images) {
            rel = "lightbox[node_thumbnails]";
          }
          $(this).attr({rel: rel,
            title: alt + "<br /><br /><a href=\"" + this.href + "\">"+ link_text + "</a>",
            href: $(child).attr("src").replace(".thumbnail", "")
            });
        }

      });
    }
  });
}
