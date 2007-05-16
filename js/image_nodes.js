// Image Node Auto-Format with Auto Image Grouping
// Steve McKenzie

function lightbox2_image_nodes() {
 var body = document.getElementsByTagName("body");
 if (!Element.hasClassName(body, "img_assist")) {
			var nodes = document.getElementsByClassName("image");
			for (var i = 0; i < nodes.length; i++) {
				if (Element.hasClassName(nodes[i], "thumbnail")) {
					var parent = nodes[i].parentNode;
					parent.rel = "lightbox[node_thumbnails]";
					parent.title = nodes[i].alt.concat("<br /><br /><a href=\"" + parent.href + "\">View Image Information</a>");
					parent.href = nodes[i].src.replace(".thumbnail", "");
					//parent.title = nodes[i].alt;
				}
			}
 }
}

if (isJsEnabled()) {
  addLoadEvent(lightbox2_image_nodes);
}
