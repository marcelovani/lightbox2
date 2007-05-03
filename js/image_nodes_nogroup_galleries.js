// Image Node Auto-Format with no lightbox for nested gallery links
// Stella Power

function lightbox2_image_nodes() {
  var gallery = new Array();
 	var nodes = document.getElementsByClassName("galleries");
 	for (var i = 0; i < nodes.length; i++) {
				// select all the images inside those lists
				var sub = nodes[i].getElementsByTagName("img");
				for (var j = 0; j < sub.length; j++) {
						if (Element.hasClassName(sub[j], "thumbnail")) {
								gallery.push(sub[j]);
						}
				}
		}


 	var nodes = document.getElementsByClassName("image");
 	for (var i = 0; i < nodes.length; i++) {
  		if (Element.hasClassName(nodes[i], "thumbnail")) {
						if (gallery.indexOf(nodes[i]) == -1) {
								var parent = nodes[i].parentNode;
								parent.rel = "lightbox";
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
