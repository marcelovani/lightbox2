// Image Node Auto-Format
// Steve McKenzie

if (Drupal.jsEnabled) {
		$(document).ready(function lightbox2_image_nodes() {
				if (!Element.hasClassName("img_assist_thumbs", "img_assist")) {
						var nodes = document.getElementsByClassName("image");
						for (var i = 0; i < nodes.length; i++) {
								if (Element.hasClassName(nodes[i], "image-thumbnail") || Element.hasClassName(nodes[i], "thumbnail")) {
										var parent = nodes[i].parentNode;
										parent.rel = "lightbox";
										parent.title = nodes[i].alt.concat("<br /><br /><a href=\"" + parent.href + "\">View Image Information</a>");
										parent.href = nodes[i].src.replace(".thumbnail", "");
										//parent.title = nodes[i].alt;
								}
						}
				}
		});
}

