<?php
/*
Plugin Name: CloudWok
Plugin URI: http://www.cloudwok.com
Description: CloudWok enables you to let your website visitors upload files directly into a Dropbox, Google Drive, Amazon S3, Box.com, or other cloud storage folder that you own.
Version: 0.3.5
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
			'show_form_input_name' => True,
			'show_form_input_email' => True,
			'show_powered_by_link' => False,
			'simple_file_upload_button' => False,
			'label_add_files_btn' => '',
			'label_send_msg_btn' => '',
			'label_dropzone' => '',
			'label_send_msg_placeholder' => '',
			'label_send_email_placeholder' => '',
			'label_send_firstname_placeholder' => '',
			'label_send_lastname_placeholder' => ''
		), $atts )
	);

	$show_uploads = '';
	$show_downloads = '';
	$show_form = '';
	$show_form_input_name = ' data-show-name="n"';
	$show_form_input_email = ' data-show-email="n"';
	$show_powered_by_link = ' data-pby="n"';
	$file_upload_input = '<div class="cloudwok-dropzone"></div>';

	// customize labels and texts
	$customizeDropzone = '';
	$customizeMessages = '';

	if(array_key_exists('show_uploads', $atts) && $atts['show_uploads'] == "True") {
		$show_uploads = '<div class="cloudwok-upload-files"></div>';
	}
	if(array_key_exists('show_form', $atts) && $atts['show_form']  == "True") {
		$show_form = '<div class="cloudwok-upload-message"></div>';
	}
	if(array_key_exists('show_form_input_name', $atts) && $atts['show_form_input_name'] == "True") {
		$show_form_input_name = ' data-show-name="y"';
	}
	if(array_key_exists('show_form_input_email', $atts) && $atts['show_form_input_email'] == "True") {
		$show_form_input_email = ' data-show-email="y"';
	}
	if(array_key_exists('show_downloads', $atts) && $atts['show_downloads']  == "True") {
		$show_downloads = '<div class="cloudwok-download-files"></div>';
	}
	if(array_key_exists('simple_file_upload_button', $atts) && $atts['simple_file_upload_button'] == "True") {
		$file_upload_input = '<input type="file" name="files[]" multiple>';
	}
	if(array_key_exists('show_powered_by_link', $atts) && $atts['show_powered_by_link'] == "True") {
		$show_powered_by_link = 'data-pby="y"';
	}

	// custom labels
	if(array_key_exists('label_add_files_btn', $atts) || array_key_exists('label_dropzone', $atts)) {
		$customizeDropzone = 'document.querySelector( ".cloudwok-embed .cloudwok-dropzone").addEventListener("DOMNodeInserted", customizeDropzone, false);
		function customizeDropzone(e) {
		  if(e.target && e.target.nodeName == "DIV") {
				';
		if(array_key_exists('label_add_files_btn', $atts)) {
			$customizeDropzone = $customizeDropzone . 'document.querySelector(".cloudwok-embed .dropzone span.lead > .fileinput-button > span" ).innerHTML = "' . $atts['label_add_files_btn'] . '";';
		}
		if(array_key_exists('label_dropzone', $atts)) {
			$customizeDropzone = $customizeDropzone . 'document.querySelector(".cloudwok-embed .dropzone span.lead > strong" ).innerHTML = "' . $atts['label_dropzone'] . '";';
		}
		$customizeDropzone = $customizeDropzone . '
	  }}';
	}
	if(array_key_exists('label_send_msg_btn', $atts) || array_key_exists('label_send_msg_placeholder', $atts)) {
		$customizeMessages = 'document.querySelector( ".cloudwok-embed .cloudwok-upload-message").addEventListener("DOMNodeInserted", customizeMessages, false);
		function customizeMessages(e) {
			if(e.target && e.target.nodeName == "DIV") {';
		if(array_key_exists('label_send_msg_btn', $atts)) {
			$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed .cloudwok-upload-message .btn-start-upload" ).innerHTML = "<i class=\'fa fa-send\'></i> ' . $atts['label_send_msg_btn'] . '";';
		}
		if(array_key_exists('label_send_msg_placeholder', $atts)) {
			$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed .cloudwok-upload-message > form > fieldset > div.form-group > div > textarea" ).innerHTML = "' . $atts['label_send_msg_placeholder'] . '";';
		}
		if(array_key_exists('label_send_msg_placeholder', $atts)) {
			$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed .cloudwok-upload-message > form > fieldset > div.form-group > div > textarea" ).placeholder = "' . $atts['label_send_msg_placeholder'] . '";';
		}
		if(array_key_exists('label_send_email_placeholder', $atts)) {
			$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed input[name=from]").placeholder = "' . $atts['label_send_email_placeholder'] . '";';
		}
		if(array_key_exists('label_send_firstname_placeholder', $atts)) {
			$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed input[name=from_firstname]").placeholder = "' . $atts['label_send_firstname_placeholder'] . '";';
		}
		if(array_key_exists('label_send_lastname_placeholder', $atts)) {
			$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed input[name=from_lastname]" ).placeholder = "' . $atts['label_send_lastname_placeholder'] . '";';
		}
		$customizeMessages = $customizeMessages . '
	  }}';
	}

	// Code
  $to_return = '<div class="cloudwok-embed" data-wokid="' . $atts['wok_id'] . '" ' . $show_powered_by_link . $show_form_input_name . $show_form_input_email . '>'
	  . $show_uploads .
    '<form class="cloudwok-upload">'
		. $file_upload_input
		.
    '</form>'
		. $show_form
		. $show_downloads .
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

	' . $customizeDropzone . '

	' .	$customizeMessages . '
	</script>
	';
	return $to_return;
}
add_shortcode( 'cloudwok', 'cloudwok_shortcode' );

?>
