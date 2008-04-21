CONTENTS OF THIS FILE
----------------------

  * Introduction
  * Installation
  * Adding Lightbox Functionality to your Images
    - No Grouping
    - With Grouping
    - Slideshow
    - Turning the Image Caption into a Link
  * Known Issues
    - Lightbox Lite in IE


INTRODUCTION
------------
Maintainers:
  Mark Ashmead (http://drupal.org/user/52392)
  Stella Power (http://drupal.org/user/66894)

Documentation: http://drupal.org/node/144469

Licensed under the GNU/GPL License
Based on Lightbox v2.03.3 by Lokesh Dhakar
<http://www.huddletogether.com/projects/lightbox2/>

Originally written to make use of the Prototype framework, and Script.acalo.us,
now altered to use jQuery.

Permission has been granted to Mark Ashmead & other Drupal Lightbox2 module
maintainers to distribute the original lightbox.js via Drupal.org under this
license scheme.  This file has been subsequently modified to make use of jQuery
instead of prototype / script.acalo.us.

This module enables the use of lightbox2 which places images above your
current page, not within. This frees you from the constraints of the layout,
particularly column widths.

This module will include the lightbox CSS and JS files in your Drupal
Installation without the need to edit the theme. The module comes with a
Lightbox2 Lite option which does not use the jQuery libraries; it is therefore
less likely to conflict with anything else.


INSTALLATION
------------
1. Copy lightbox2 folder to modules directory.
2. At admin/build/modules enable the lightbox2 module.
3. Enable permissions at admin/user/access.
4. Configure the module at admin/settings/lightbox2.
5. Modify your image links to open in a lightbox where necessary, see "Adding
   Lightbox Functionality to your Images' section below.


ADDING LIGHTBOX FUNCTIONALITY TO YOUR IMAGES
--------------------------------------------
No Grouping
===========
Add rel="lightbox" attribute to any link tag to activate the lightbox.
For example:
<a href="images/image-1.jpg" rel="lightbox" title="my caption">image #1</a>

Optional: Use the title attribute if you want to show a caption.

With Grouping
==============
If you have a set of related images that you would like to group, follow step
one but additionally include a group name between square brackets in the rel
attribute. For example:

<a href="images/image-1.jpg" rel="lightbox[roadtrip]">image #1</a>
<a href="images/image-2.jpg" rel="lightbox[roadtrip]">image #2</a>
<a href="images/image-3.jpg" rel="lightbox[roadtrip]">image #3</a>

No limits to the number of image sets per page or how many images are allowed
in each set. Go nuts!

If you have a set of images that you would like to group together in a
lightbox, but only wish for one of these images to be visible on your page, you
can assign the "lightbox_hide_image" class to hide the additional images.  For
example:

<a href="images/image-1.jpg" rel="lightbox[roadtrip]">image #1</a>
<a href="images/image-2.jpg" rel="lightbox[roadtrip]" class="lightbox_hide_image">image #2</a>
<a href="images/image-3.jpg" rel="lightbox[roadtrip]" class="lightbox_hide_image">image #3</a>

Slideshow
=========
This is very similar to the grouping functionality described above.  The only
difference is that "rel" attribute should be set to "lightshow" instead of
"lightbox".  Using the same example as above, we could launch the images in a
slideshow by doing:

<a href="images/image-1.jpg" rel="lightshow[roadtrip]">image #1</a>
<a href="images/image-2.jpg" rel="lightshow[roadtrip]">image #2</a>
<a href="images/image-3.jpg" rel="lightshow[roadtrip]">image #3</a>

Turning the Image Caption into a Link
=====================================
If you wish to turn the caption into a link, format your caption in the
following way:

<a href="images/image-1.jpg" rel="lightbox" title='<a href="http://www.yourlink.com">Clicky Visit Link</a>'>image #1</a>


KNOWN ISSUES
------------

Lightbox Lite in IE
--------------------
There is an issue with Lightbox Lite in IE browsers but only for sites where
Drupal is installed in a subdirectory.  In such instances, the overlay.png image
can not be found.  To overcome this issue you will need to edit the
lightbox2/css/lightbox_lite.css file and change the path to this image.  By
default the line is set to:

filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="../images/overlay.png", sizingMethod="scale");

You will need to change the image path on this line to be the full path, e.g.

filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="/sites/all/modules/lightbox2/images/overlay.png", sizingMethod="scale");

See http://drupal.org/node/185866 for more details.
