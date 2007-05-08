// Image Node Auto-Format with Auto Image Grouping
// Steve McKenzie

function lightbox2_image_nodes() {
	var nodes = document.getElementsByTagName("img");
	for (var i = 0; i < nodes.length; i++) {
		if (Element.hasClassName(nodes[i], "acidfree")) {
			var parent = nodes[i].parentNode;
			parent.rel = "lightbox";
			parent.title = nodes[i].alt.concat("<br /><br /><a href=\"" + parent.href + "\">View Image Information</a>");
			parent.href = nodes[i].src.replace("_thumb", "_large");
		}
	}
}

if (isJsEnabled()) {
  addLoadEvent(lightbox2_image_nodes);
}
