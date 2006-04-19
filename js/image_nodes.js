function lightbox2_image_nodes() {
	var nodes = document.getElementsByClassName("image");
	for (var i = 0; i < nodes.length; i++) {
		if (Element.hasClassName(nodes[i], "thumbnail")) {
			var parent = nodes[i].parentNode;
			parent.rel = "lightbox[node_thumbnails]";
			parent.href = nodes[i].src.replace(".thumbnail", "");
			parent.title = nodes[i].alt;
		}
	}
}

if (isJsEnabled()) {
  addLoadEvent(lightbox2_image_nodes);
}