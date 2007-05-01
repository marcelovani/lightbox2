LIGHTBOX V2 MODULE
------------------

Drupal Lightbox V2 Module:
By: Mark Ashmead
Mailto: bugzie@gmail.com
Co-maintainer: Stella Power (http://drupal.org/user/66894)

Licensed under the GNU/GPL License

Permission has been granted by Lokesh Dhakar to distribute the lightbox.js file via Drupal.org under this license scheme, as allowed by the Creative Commons License.

This module enables the use of lightbox V2 which places images above your current page, not within. This frees you from the constraints of the layout, particularly column widths.

---------------------------------------------------------------------------------------------------------

Pre-Installation
----------------

Due to variation in licensing, you will need to download the Scriptaculous/Prototype libraries seperately. Please visit: http://script.aculo.us/downloads - and download the latest version of the libraries. You will then need to copy the files located in \lib and \src directories of this download into the \lightbox2\js\ directory of the LightboxV2 module. 

The files required are: 

\lib\prototype.js
\src\builder.js
\src\dragdrop.js
\src\effects.js
\src\scriptaculous.js
\src\slider.js
\src\unittest.js

When copied, the directory should look like:

\lightbox2\js\prototype.js
\lightbox2\js\builder.js
\lightbox2\js\dragdrop.js
\lightbox2\js\effects.js
\lightbox2\js\scriptaculous.js
\lightbox2\js\slider.js
\lightbox2\js\unittest.js


Installation
------------
1. Copy lightbox2 folder to modules directory
2. At admin/modules enable the module
3. Add rel="lightbox" attribute to any link tag to activate the lightbox. For example:

<a href="images/image-1.jpg" rel="lightbox" title="my caption">image #1</a>

Optional: Use the title attribute if you want to show a caption.

4. If you have a set of related images that you would like to group, follow step one but additionally include a group name between square brackets in the rel attribute. For example: 

<a href="images/image-1.jpg" rel="lightbox[roadtrip]">image #1</a>
<a href="images/image-2.jpg" rel="lightbox[roadtrip]">image #2</a>
<a href="images/image-3.jpg" rel="lightbox[roadtrip]">image #3</a>

No limits to the number of image sets per page or how many images are allowed in each set. Go nuts! 

5. If you wish to turn the caption into a link, format your caption in the following way:

<a href="images/image-1.jpg" rel="lightbox" title='<a href="http://www.yourlink.com">Clicky Visit Link</a>'>image #1</a>


Information
------------

This module will include the lightbox CSS and JS files in your Drupal Installation without the need to edit the theme. The module comes with a Lightbox2 Lite option which does not use the Scriptaculous/Prototype libraries; it is therefore less likely to conflict with anything else. 

Known Issues:
-------------

Image Issues - An issue has been identified with the loading of certain images when using the module. (close.gif, prev.gif, next.gif)

If your installation of Drupal exists in the root of your domain, i.e., www.yourinstallation.com then you shouldn't have any problems. The issue only occurs when Drupal is installed in a subdirectory, i.e., www.yourinstallation.com/subdirectory.

If this is the case, you will need to edit the lightbox.js on lines 63, 64 and 65 to reflect the fully qualified URL of your images. In the above case, this would be as follows;

var fileLoadingImage = "/modules/lightbox2/images/loading.gif";
var fileBottomNavCloseImage = "/modules/lightbox2/images/closelabel.gif";
var fileBottomNavZoomImage = "/modules/lightbox2/images/expand.gif"; //Update to 2.02+

should be changed to

var fileLoadingImage = "/subdirectory/modules/lightbox2/images/loading.gif";
var fileBottomNavCloseImage = "/subdirectory/modules/lightbox2/images/closelabel.gif";
var fileBottomNavZoomImage = "/subdirectory/modules/lightbox2/images/expand.gif"; //Update to 2.02+

There may be other methods that can be used to acheive this, but this should be the simplest for those with little or no programming experience. If you choose to use Lightbox2 Lite option, then you will no to edit the lightbox_lite.js file in a similar manner.

