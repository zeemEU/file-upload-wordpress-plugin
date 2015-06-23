<?php
/*
Plugin Name: CloudWok
Plugin URI: http://www.cloudwok.com
Description: CloudWok enables you to let your website visitors upload files directly into a Dropbox, Google Drive, Amazon S3, Box.com, or other cloud storage folder that you own.
Version: 0.2
Author: CloudWok
Author Email: info@cloudwok.com
License: GPL2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

  Copyright 2015 CloudWok (info@cloudwok.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if (!defined('ABSPATH')) die();

// Add Shortcode
function cloudwok_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'wok_id' => '',
			'show_uploads' => True,
			'show_downloads' => False,
			'show_form' => True,
		), $atts )
	);

	$show_uploads = '';
	$show_downloads = '';
	$show_form = '';

	if($atts['show_uploads']) {
		$show_uploads = '<div class="cloudwok-upload-files"></div>';
	}
	if($atts['show_downloads']) {
		$show_downloads = '<div class="cloudwok-download-files"></div>';
	}
	if($atts['show_form']) {
		$show_form = '<div class="cloudwok-upload-message"></div>';
	}

	// Code
  $to_return = '<div class="cloudwok-embed" data-wokid="' . $atts['wok_id'] . '">'
	  . $show_uploads .
    '<form class="cloudwok-upload">
      <div class="cloudwok-dropzone"></div>
    </form>'
		. $show_downloads
		. $show_form .
  '</div>

  <script>
    (function(window, document) {
      var loader = function() {
        var script = document.createElement("script"),
        tag = document.getElementsByTagName("script")[0];
        script.src = "https://www.cloudwok.com/cdn-vassets/javascripts/cw.js";
        tag.parentNode.insertBefore(script, tag);
      };
      window.addEventListener ? window.addEventListener("load", loader, false) :
      window.attachEvent("onload", loader);
    })(window, document);
  </script>';
	return $to_return;
}
add_shortcode( 'cloudwok', 'cloudwok_shortcode' );

?>
