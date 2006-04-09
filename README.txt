LIGHTBOX V2 MODULE Version 1.0
-----------------

Drupal Lightbox V2 Module:
By: Mark Ashmead
Mailto: bugzie@gmail.com

Licensed under the GNU/GPL License

This module enables the use of lightbox V2 which places images above your current page, not within. This frees you from the constraints of the layout, particularly column widths..

---------------------------------------------------------------------------------------------------------
- Prefix: If using the CVS version, download all the files into a folder called lightbox2.


Installation
------------
1. Copy lightbox folder to modules directory
2. At admin/modules enable the module
3. Add rel="lightbox" attribute to any link tag to activate the lightbox. For example:

<a href="images/image-1.jpg" rel="lightbox" title="my caption">image #1</a>

Optional: Use the title attribute if you want to show a caption.

4. If you have a set of related images that you would like to group, follow step one but additionally include a group name between square brackets in the rel attribute. For example: 

<a href="images/image-1.jpg" rel="lightbox[roadtrip]">image #1</a>
<a href="images/image-2.jpg" rel="lightbox[roadtrip]">image #2</a>
<a href="images/image-3.jpg" rel="lightbox[roadtrip]">image #3</a>

No limits to the number of image sets per page or how many images are allowed in each set. Go nuts! 



Information
-------------

This module will include the lightbox CSS and JS files in your Drupal Installation without the need to edit the theme.


TODO
-------------

Create a seperate module to introduce the scriptaculous library to Drupal. This will allow porting of other modules without conflict.
