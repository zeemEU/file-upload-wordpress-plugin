<?php
/*
Plugin Name: CloudWok
Plugin URI: http://www.cloudwok.com
Description: CloudWok enables you to let your website visitors upload files directly into a Dropbox, Google Drive, Amazon S3, Box.com, or other cloud storage folder that you own.
Version: 0.1
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
		), $atts )
	);

	// Code

  echo '<div class="cloudwok-embed" data-wokid="' . $atts['wok_id'] . '">';
  echo '  <div class="cloudwok-upload-files"></div>';
  echo '  <form class="cloudwok-upload">';
  echo '    <div class="cloudwok-dropzone"></div>';
  echo '  </form>';
  echo '  <div class="cloudwok-download-files"></div>';
  echo '  <div class="cloudwok-upload-message"></div>';
  echo '</div>';

  echo '<script>';
  echo '  (function(window, document) {';
  echo '    var loader = function() {';
  echo '      var script = document.createElement("script"),';
  echo '      tag = document.getElementsByTagName("script")[0];';
  echo '      script.src = "https://www.cloudwok.com/cdn-vassets/javascripts/cw.js";';
  echo '      tag.parentNode.insertBefore(script, tag);';
  echo '    };';
  echo '    window.addEventListener ? window.addEventListener("load", loader, false) :';
  echo '    window.attachEvent("onload", loader);';
  echo '  })(window, document);';
  echo '</script>';
}
add_shortcode( 'cloudwok', 'cloudwok_shortcode' );

?>
