/* $Id$ */

// Image Node Auto-Format with Auto Image Grouping.
// Original version by Steve McKenzie.
// Altered by Stella Power for jQuery version.

if (Drupal.jsEnabled) {
  $(document).ready(function lightbox2_image_nodes() {

    var settings = Drupal.settings.lightbox2;

    // Don't do it on the image assist popup selection screen.
    var img_assist = document.getElementById("img_assist_thumbs");
    if (!img_assist) {

      // Select the enabled image types.
      var classes = settings.trigger_image_classes;
      $("a["+classes+"]").each(function(i) {

        if ((!settings.disable_for_gallery_lists && !settings.disable_for_acidfree_gallery_lists) || (!$(this).parents(".galleries").length && !$(this).parents(".acidfree-folder").length && !$(this).parents(".acidfree-list").length) || ($(this).parents(".galleries").length && !settings.disable_for_gallery_lists) || (($(this).parents(".acidfree-folder").length || $(this).parents(".acidfree-list").length) && !settings.disable_for_acidfree_gallery_lists)) {

          var child = $(this).children();

          // Set the alt text.
          var alt = $(child).attr("alt");
          if (!alt) {
            alt = "";
          }

          // Set the image node link text.
          var link_text = settings.node_link_text;

          // Set the rel attribute.
          var rel = "lightbox";
          if (settings.group_images) {
            rel = "lightbox[" + $(child).attr("class") + "]";
          }
          

          // Handle flickr images.
          var href = $(child).attr("src");
          if ($(child).attr("class").match("flickr-photo-img")) {
            href = $(child).attr("src").replace("_s", "").replace("_t", "").replace("_m", "").replace("_b", "");
            if (settings.group_images) {
              rel = "lightbox[flickr]";
            }
          }

          // Handle "inline" images.
          else if ($(child).attr("class").match("inline")) {
            href = $(this).attr("href");
          }

          // Set the href attribute.
          else if (settings.image_node_sizes != '()') {
            href = $(child).attr("src").replace(new RegExp(settings.image_node_sizes), ((settings.display_image_size == "")?settings.display_image_size:"."+ settings.display_image_size)).replace(/(image\/view\/\d+)(\/\w*)/, ((settings.display_image_size == "")?"$1/_original":"$1/"+ settings.display_image_size));
            if (settings.group_images) {
              rel = "lightbox[node_images]";
            }
          }

          // Modify the image url.
          $(this).attr({rel: rel,
            title: alt + "<br /><a href=\"" + this.href + "\" id=\"node_link_text\">"+ link_text + "</a>",
            href: href
            });
        }

      });

    }
  });
}
