# wp-frontend-image-upload
Simple HTML Frontend image upload plugin for wordpress using shortcode

* Plugin Name: wp-frontend-image-upload
* Plugin URI: https://github.com/SPhillips1337/wp-frontend-image-upload
* Description: Simple HTML Frontend image upload plugin for wordpress using shortcode
* Version: 0.4
* Author: Stephen Phillips
* Author URI: https://www.stephenphillips.co.uk/

Introduction
============
I had an idea a while ago to make a HTML 5 frontend image upload plugin for Wordpress which worked with mobiles to show the preview etc when taking photos with a camera, Which I hope this will be the start of.

This is an example of a functioning plugin which will allow you to place an image upload form on any page in Wordpress using a shortcode of '[frontend_image_uploader]' which will allow a user to upload an image directly into the Wordpress media library from the front end.

I hope to develop this plugin further.

Demo at https://www.stephenphillips.co.uk/demo
Grab latest plugin files from https://www.stephenphillips.co.uk/demo/wp-frontend-image-upload.zip

Installation
============
1. Simply place the file 'frontend-image-upload.php' into a folder called 'frontend-image-upload' and zip it to create 'frontend-image-upload.zip' then go to your Wordpress Admin area for plugins and upload the zip file, and activate it to enable the new '[frontend_image_uploader]' shortcode.
2. Place '[frontend_image_uploader]' shortcode, for example using the gutenburg shortcode block on a page where you would like the upload form to be shown (And save it!).
3. That's it! Go to the page and test uploading an image and then check your media library in the Wordpress admin area where the image should now be shown.

Notes
=====
I followed several guides in creating this plugin which can be found below for reference;

Version 0.1 - 17/08/2022
===========
Initial dev of working frontend image upload plugin with shortcode
https://www.dreamhost.com/blog/how-to-create-your-first-wordpress-plugin/
https://rudrastyh.com/wordpress/how-to-add-images-to-media-library-from-uploaded-files-programmatically.html
https://www.sitepoint.com/handling-post-requests-the-wordpress-way/

Version 0.2 - 18/08/2022
===========
Added an admin menu option and page to the plugin
Added simple validation of upload type as image
https://blog.idrsolutions.com/2014/06/wordpress-plugin-part-1/

Version 0.3 - 18/08/2022
===========
Added option on admin page to show shortcode and click to copy it

Version 0.4 - 15/09/2022
===========
Added a settings page to the plugin which shows under the WP Admin Settings menu

Article describing how to create a simple settings page for a plugin can be found over at https://deliciousbrains.com/create-wordpress-plugin-settings-page/