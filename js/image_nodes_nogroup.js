// Image Node Auto-Format
// Steve McKenzie

function lightbox2_image_nodes() {
	var nodes = document.getElementsByClassName("image");
	for (var i = 0; i < nodes.length; i++) {
		if (Element.hasClassName(nodes[i], "image-thumbnail")) {
			var parent = nodes[i].parentNode;
			parent.rel = "lightbox";
			parent.title = nodes[i].alt.concat("<br /><br /><a href=\"" + parent.href + "\">View Image Information</a>");
			parent.href = nodes[i].src.replace(".thumbnail", "");
			//parent.title = nodes[i].alt;
		}
	}
}

try {
		if (isJsEnabled()) {
				addLoadEvent(lightbox2_image_nodes);
		}
} catch (e) {
		if (Drupal.jsEnabled) {
				$(document).ready(lightbox2_image_nodes);
		}
}
