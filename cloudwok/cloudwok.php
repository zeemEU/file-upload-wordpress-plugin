<?php
/*
Plugin Name: CloudWok
Plugin URI: http://www.cloudwok.com
Description: CloudWok enables you to let your website visitors upload files directly into a Dropbox, Google Drive, Amazon S3, Box.com, or other cloud storage folder that you own.
Version: 0.4.4
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

class WokDataConfigDropzone {
    public $label = "";
    public $button  = "";
}

class WokDataConfigSuccess {}

class WokDataConfigSuccessMessage {
    public $title = "";
    public $subtitle  = "";
		public $text  = "";
}

class WokDataConfigForm {
	public $button  = "";
	public $sent  = "";
}

class WokDataConfigFormEmail {
	public $placeholder  = "";
	public $optional  = false;
}

class WokDataConfigFormName {}

class WokDataConfigFormNameFirstName {
	public $placeholder  = "";
	public $required  = false;
}

class WokDataConfigFormNameLastName {
	public $placeholder  = "";
	public $required  = false;
}

class WokDataConfigFormMessage {
	public $placeholder  = "";
	public $optional  = false;
}

class WokDataConfig {}

// Add Shortcode
function cloudwok_shortcode( $atts ) {

	global $current_user;
  get_currentuserinfo();
	if($current_user) {
	  $wp_user_id = $current_user->ID;
	  $wp_user_firstname = $current_user->user_firstname;
		if(!$wp_user_firstname) {
			$wp_user_firstname = $current_user->display_name;
		}
	  $wp_user_lastname = $current_user->user_lastname;
	  $wp_user_email = $current_user->user_email;
  }

	// init wok data-config
	$wdc = new WokDataConfig();
	$wdc->dropzone = new WokDataConfigDropzone();
	$wdc->success = new WokDataConfigSuccess();
	$wdc->success->message = new WokDataConfigSuccessMessage();
	$wdc->form = new WokDataConfigForm();
	$wdc->form->email = new WokDataConfigFormEmail();
	$wdc->form->name = new WokDataConfigFormName();
	$wdc->form->name->firstname = new WokDataConfigFormNameFirstName();
	$wdc->form->name->lastname = new WokDataConfigFormNameLastName();
	$wdc->form->message = new WokDataConfigFormMessage();

	// Attributes
	extract( shortcode_atts(
		array(
      'config' => '',
			'wok_id' => '',
			'show_uploads' => True,
			'show_downloads' => False,
			'show_form' => True,
			'show_form_input_name' => True,
			'invisible_form_input_name' => False,
			'show_form_input_email' => True,
			'hide_form_message_text' => False,
			'show_powered_by_link' => False,
			'simple_file_upload_button' => False,
			'hide_upload_success_message' => False,
			'allow_upload' => True,
			'label_add_files_btn' => '',
			'label_send_msg_btn' => '',
			'label_dropzone' => '',
			'success_message_title' => '',
			'success_message_subtitle' => '',
			'success_message_text' => '',
			'label_send_msg_placeholder' => '',
			'label_send_email_placeholder' => '',
			'label_send_firstname_placeholder' => '',
			'label_send_lastname_placeholder' => '',
			'prefill_form_fields' => '',
			'required_firstname' => '',
			'required_lastname' => ''
		), $atts )
	);

	$show_uploads = '';
	$show_downloads = '';
	$show_form = '';
	$show_form_input_name = '';
	$show_form_input_email = '';
	$hide_form_message_text = '';
	$show_powered_by_link = ' data-pby="n"';
	$hide_upload_success_message = '';
	$file_upload_input = '<div class="cloudwok-dropzone"></div>';
  $file_upload_form = '';

	// customize labels and texts
	$customizeDataConfig = '';
	$customizeDropzone = '';
	$customizeMessages = '';

  // break if no wok_id was entered
  if(!array_key_exists('wok_id', $atts)) {
    return "Please enter your 4-letter wok id (create a wok on https://www.cloudwok.com)";
  }

	if(array_key_exists('show_uploads', $atts) && $atts['show_uploads'] == "True") {
		$show_uploads = '<div class="cloudwok-upload-files"></div>';
	}
	if(array_key_exists('show_form', $atts) && $atts['show_form']  == "True") {
		$show_form = '<div class="cloudwok-upload-message"></div>';
	}
	if(array_key_exists('hide_form_message_text', $atts) && $atts['hide_form_message_text'] == "True") {
		$hide_form_message_text = ' data-hide-message-text="y"';
	}
	if(array_key_exists('show_downloads', $atts) && $atts['show_downloads']  == "True") {
		$show_downloads = '<div class="cloudwok-download-files"></div>';
	}
	if(array_key_exists('simple_file_upload_button', $atts) && $atts['simple_file_upload_button'] == "True") {
		$file_upload_input = '<input type="file" name="files[]" multiple>';
	}
	if(array_key_exists('simple_file_upload_button', $atts) && $atts['simple_file_upload_button'] == "True") {
		$file_upload_input = '<input type="file" name="files[]" multiple>';
	}
	if(array_key_exists('allow_upload', $atts) && $atts['allow_upload'] == "False") {
		$file_upload_form = '<form class="cloudwok-upload"></form>';
	} else {
		$file_upload_form = '<form class="cloudwok-upload">' . $file_upload_input . '</form>';
	}
	if(array_key_exists('hide_upload_success_message', $atts) && $atts['hide_upload_success_message'] == "True") {
		$hide_upload_success_message = 'data-hide-upload-success-msg="y"';
	}
	if(array_key_exists('show_powered_by_link', $atts) && $atts['show_powered_by_link'] == "True") {
		$show_powered_by_link = 'data-pby="y"';
	}

// customization via data-config
	$customizeDataConfigFlag = false;
	// init with default values (must do this to prevent duplicate input fields)
	if(array_key_exists('show_form_input_name', $atts) && $atts['show_form_input_name'] == "True" ) {
		$wdc->form->name->firstname->optional = true;
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('show_form_input_email', $atts) && $atts['show_form_input_email'] == "True") {
		$wdc->form->email->required = false;
		$customizeDataConfigFlag = true;
	}
	// custom dropzone labels //
	if(array_key_exists('label_dropzone', $atts)) {
		$wdc->dropzone->label = $atts['label_dropzone'];
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('label_add_files_btn', $atts)) {
		$wdc->dropzone->button  = $atts['label_add_files_btn'] ;
		$customizeDataConfigFlag = true;
	}
	// customize form //
	// placeholders
	if(array_key_exists('label_send_firstname_placeholder', $atts)) {
		$wdc->form->name->firstname->placeholder = $atts['label_send_firstname_placeholder'];
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('label_send_lastname_placeholder', $atts)) {
		$wdc->form->name->lastname->placeholder = $atts['label_send_lastname_placeholder'];
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('label_send_email_placeholder', $atts)) {
		$wdc->form->email->placeholder = $atts['label_send_email_placeholder'];
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('label_send_msg_placeholder', $atts)) {
		$wdc->form->message->placeholder = $atts['label_send_msg_placeholder'];
		$customizeDataConfigFlag = true;
	}
	// required
	if(array_key_exists('required_firstname', $atts) && $atts['required_firstname']  == "True") {
		$wdc->form->name->firstname->optional = false;
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('required_lastname', $atts) && $atts['required_lastname']  == "True") {
		$wdc->form->name->lastname->optional = false;
		$customizeDataConfigFlag = true;
	}
	// fill e-mail, first name, and last name with wp user values
	if(array_key_exists('prefill_form_fields', $atts)) {
		$wdc->form->email->value = $wp_user_email;
		$wdc->form->name->firstname->value = $wp_user_firstname;
		$wdc->form->name->lastname->value = $wp_user_lastname;
		$customizeDataConfigFlag = true;
	}
	// you can also hide the message fields
	if(array_key_exists('show_form_input_name', $atts) && $atts['show_form_input_name'] == "True" && array_key_exists('show_form_input_name', $atts) &&$atts['show_form_input_name'] == "True") {
			if(array_key_exists('invisible_form_input_name', $atts) && $atts['invisible_form_input_name'] == "True") {
				$customizeMessages = 'document.querySelector( ".cloudwok-embed .cloudwok-upload-message").addEventListener("DOMNodeInserted", customizeMessages, false);
				function customizeMessages(e) {
					if(e.target && e.target.nodeName == "DIV") {';
				$wdc->form->email->required = false;
				$wdc->form->name->firstname->optional = true;
				$wdc->form->name->lastname->optional = true;
				$wdc->form->message->required = false;
				$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed input[name=from_lastname]" ).style.display = "none";';
				$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed input[name=from_firstname]" ).style.display = "none";';
				$customizeMessages = $customizeMessages . 'document.querySelector(".cloudwok-embed textarea[name=message]" ).style.display = "none";';
				$customizeMessages = $customizeMessages . '
				 }}';
				$customizeDataConfigFlag = true;
			}
	}
	// sucess message customization
	if(array_key_exists('success_message_title', $atts)) {
		$wdc->success->message->title = $atts['success_message_title'];
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('success_message_subtitle', $atts)) {
		$wdc->success->message->subtitle = $atts['success_message_subtitle'];
		$customizeDataConfigFlag = true;
	}
	if(array_key_exists('success_message_text', $atts)) {
		$wdc->success->message->text = $atts['success_message_text'];
		$customizeDataConfigFlag = true;
	}
	// END configData code
	if($customizeDataConfigFlag) {
		$customizeDataConfig = 'var cloudWokConfig =' . json_encode($wdc) . '
  document.querySelector(".cloudwok-embed").setAttribute("data-config", JSON.stringify(cloudWokConfig));
  console.log("cloudWokConfig: " + JSON.stringify(cloudWokConfig));';
	}

	// Code
  $to_return = '<div class="cloudwok-embed" data-wokid="' . $atts['wok_id'] . '" ' . $show_powered_by_link . $show_form_input_name . $show_form_input_email . $hide_form_message_text . $hide_upload_success_message . '>'
	  . $show_uploads
		. $file_upload_form
		. $show_form
		. $show_downloads .
  '</div>

  <script>
	' . $customizeDataConfig .
	'
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
