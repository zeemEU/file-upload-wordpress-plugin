<?php
/*
Plugin Name: CloudWok
Plugin URI: http://www.cloudwok.com
Description: CloudWok enables you to let your website visitors upload files directly into a Dropbox, Google Drive, Amazon S3, Box.com, or other cloud storage folder that you own.
Version: 0.5.2
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
      'ref' => '',
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

  // new feature since 0.5 - simply reference the embed code
  if(array_key_exists('ref', $atts)) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cloudwok';
    $cloudwok_code_ref = $atts['ref'];
    $query="SELECT * FROM $table_name WHERE id=$ref";
    $result=$wpdb->get_results($query);
    if(!$result || !$result[0] || !$result[0]->code) {
      return "<p style=\"color: red;\">Sorry, could not embed [cloudwok ref=" . $ref . " ]. Please check that the shortcode looks like this: [cloudwok ref=number] and please check in the Settings > CloudWok admin sidebar that the code has been saved with ref=" . $ref . ".</p>";
    }
    // replace escaped double quotes with normal ones
    $to_return = str_replace('\"','"', $result[0]->code);
    $to_return = str_replace("\'","'", $to_return);
    return $to_return;
  }

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
  document.querySelector(".cloudwok-embed").setAttribute("data-config", JSON.stringify(cloudWokConfig));';
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

function cloudwok_activate() {
  cw_plugin_create_db();
}
register_activation_hook( __FILE__, 'cloudwok_activate' );

function cw_plugin_create_db() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'cloudwok';

	$sql1 = "CREATE TABLE $table_name (
		id smallint(5) NOT NULL AUTO_INCREMENT,
		code varchar(65536) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

  if (file_exists( ABSPATH . 'wp-admin/includes/upgrade.php' )) {
	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  } else {
    // could not find upgrade.php where we expected it to be
    // trying out some other paths to find it
    if (file_exists( ABSPATH . '/../wp-admin/includes/upgrade.php' )) {
  	  require_once( ABSPATH . '/../wp-admin/includes/upgrade.php' );
    } elseif (file_exists( ABSPATH . '/../../wp-admin/includes/upgrade.php' )) {
  	  require_once( ABSPATH . '/../../wp-admin/includes/upgrade.php' );
    } elseif (file_exists( ABSPATH . '/../../../wp-admin/includes/upgrade.php' )) {
  	  require_once( ABSPATH . '/../../../wp-admin/includes/upgrade.php' );
    }
  }
	dbDelta( $sql1 );
}

function cw_db_get_all() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'cloudwok';
  $query="SELECT * FROM $table_name";
  $result=$wpdb->get_results($query);
  $to_return = array();
  foreach ($result as $row)
  {
    $to_return[] = $row->id;
  }
  return json_encode($to_return);
}

/** Step 1. */
function cw_plugin_menu() {
	add_options_page( 'Manage CloudWok', 'CloudWok', 'manage_options', 'manage-cloudwok-settings', 'cw_plugin_options' );
}

/** Step 2  */
add_action( 'admin_menu', 'cw_plugin_menu' );

/** Step 3. */
function cw_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
  $adminUrl = plugin_dir_url( __FILE__ ) . 'admin.php';
  $nonce = wp_create_nonce( "cWn0Nc€4tW" );
  // check if wp-config.php and wp-includes/wp-db.php files can be found
  define('MYPATH', dirname(__FILE__));
  if (!file_exists( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php') && !file_exists( MYPATH . '/../../../wp-config.php' ) && !file_exists( MYPATH . '/../../wp-config.php' ) && !file_exists( MYPATH . '/../wp-config.php' ) && !file_exists( MYPATH . '/wp-config.php' )) {
    echo '<p style="color:red;">Warning: wp-config.php could not be found at ' . $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' . ' or sub paths of ' . MYPATH . '. The CloudWok settings page might therefore not work correctly. Please send an e-mail with this warning to markus@cloudwok.com and ask for a solution of the issue.</p>';
  }
  if (!file_exists( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php') && !file_exists( MYPATH . '/../../../wp-includes/wp-db.php' ) && !file_exists( MYPATH . '/../../wp-includes/wp-db.php' ) && !file_exists( MYPATH . '/../wp-includes/wp-db.php' ) && !file_exists( MYPATH . '/wp-includes/wp-db.php' )) {
    echo '<p style="color:red;">Warning: wp-includes/wp-db.php could not be found at ' . $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' . ' or sub paths of ' . MYPATH . '. The CloudWok settings page might therefore not work correctly. Please send an e-mail with this warning to markus@cloudwok.com and ask for a solution of the issue.</p>';
  }
  if (!file_exists( ABSPATH . 'wp-admin/includes/upgrade.php' ) && !file_exists( ABSPATH . '/../wp-admin/includes/upgrade.php' ) && !file_exists( ABSPATH . '/../../wp-admin/includes/upgrade.php' ) && !file_exists( ABSPATH . '/../../../wp-admin/includes/upgrade.php' )) {
    echo '<p style="color:red;">Warning: wp-admin/includes/upgrade.php could not be found at ' . ABSPATH . 'wp-admin/includes/upgrade.php' . ' or sub paths. The CloudWok settings page might therefore not work correctly. Please send an e-mail with this warning to markus@cloudwok.com and ask for a solution of the issue.</p>';
  }

  // Add bootstrap css and js
  echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  ';
  echo '<div class="container-fluid">';
  echo '<h2><i class="fa fa-wrench"></i> Manage CloudWok Settings</h2>';
  echo '<div class="row">';
  echo '<div class="col-md-6">
  <h4><button class="btn btn-default" type="button" data-toggle="collapse"    data-target="#cloudwokInfo" aria-expanded="false" aria-controls="cloudwokInfo">
  <i class="fa fa-info-circle"></i> Info</button> PLEASE ENTER THE EMBED CODE:</h4>';
  echo '<div class="collapse" id="cloudwokInfo">
  <div class="well">
    Please visit <strong><a href="https://www.cloudwok.com/developers" target="_blank">https://www.cloudwok.com/developers</a></strong> to generate a customized embed code of the CloudWok file-upload widget. Find the "HTML code" tab and click on the clipboard icon to copy the code. Then paste the code into the text area below.
  </div>
  </div>';
  echo '<form>
    <div class="form-group">
      <textarea class="form-control" rows="16" id="cloudwokEmbedCode">
      </textarea>
    </div>
    <input id="editRef" type="text" style="display:none !important;" />
    <button id="saveBtn" class="btn btn-primary pull-left"><i class="fa fa-floppy-o"></i> Save</button>
    <button id="newBtn" class="btn btn-default pull-right">Cancel</button>
  </form>
  </div>';
  echo '<div id="cloudwokEmbedCodeList" class="col-md-6">

  </div>';
  echo '</div>'; // row
  echo '</div>'; // container
  echo '<script type="text/javascript">';
  echo '
  function escapeHtml(unsafe) {
    return unsafe.replace(/&/g, "__amp;");
  }
  function unescapeHtml(safe) {
    return safe.replace(/__amp;/g, "&").replace(/\\\"/g, "\"").replace(/\\\\\'/g, "\'");
  }

  function appendRefListItem(ref) {
    jQuery("#cloudwokEmbedCodeList").append( "<div id=\"ecListRow"+ref+"\" class=\"row clickable-row\"><div class=\"col-md-4\"><pre>[cloudwok ref="+ref+"]</pre></div><div class=\"col-md-4\"><button id=\"editBtn"+ref+"\" onclick=\"onEditBtnClick("+ref+")\" class=\"btn btn-primary editBtn\" style=\"margin-right: 5px;\"><i class=\"fa fa-pencil\"></i> Edit</button><button  id=\"deleteBtn"+ref+"\" onclick=\"onDeleteBtnClick("+ref+")\" class=\"btn btn-danger\"><i class=\"fa fa-trash\"></i> Delete</button></div></div></div>" );
  }

  function onEditBtnClick(ref) {
    if(jQuery("#editBtn"+ref+".btn-success").length == 0) {
      jQuery(".editBtn").removeClass("btn-success").removeClass("btn-primary").addClass("btn-primary");
      jQuery("#editBtn"+ref).addClass("btn-success").removeClass("btn-primary");
      jQuery("#cloudwokEmbedCode").val("");
      jQuery("#editBtn"+ref).prop("disabled", true);
      jQuery("#cloudwokEmbedCode").prop("placeholder","Loading embed code...");
      jQuery.ajax({
          type: "GET",
          url: "' . $adminUrl . '",
          data: "cwnonce=' . $nonce . '&ref="+ref,
          success: function(resp)
          {
            jQuery("#cloudwokEmbedCode").prop("placeholder","");
              var jsonObj = jQuery.parseJSON(resp);
              jQuery("#cloudwokEmbedCode").val(unescapeHtml(jsonObj.code));
              jQuery("#editRef").val(jsonObj.id);
              jQuery("#editBtn"+ref).prop("disabled", false);
          }
      });
    } else {
      jQuery("#editBtn"+ref).removeClass("btn-success").addClass("btn-primary");
      jQuery("#editRef").val("");
      jQuery("#cloudwokEmbedCode").val("");
    }
  }

  function onDeleteBtnClick(ref) {
    jQuery("#deleteBtn"+ref).prop("disabled", true);
    jQuery.ajax({
        type: "DELETE",
        url: "' . $adminUrl . '",
        data: "cwnonce=' . $nonce . '&ref="+ref,
        success: function(resp)
        {
            jQuery("#ecListRow"+ref).remove();
            jQuery("#editRef").val("");
            jQuery("#cloudwokEmbedCode").val("");
        }
    });
  }

  function create() {
    var code = escapeHtml(jQuery("#cloudwokEmbedCode").val());
    jQuery.ajax({
        type: "POST",
        url: "' . $adminUrl . '",
        data: "cwnonce=' . $nonce . '&code="+code,
        success: function(ref)
        {
            appendRefListItem(ref);
            jQuery("#editRef").val(ref);
            jQuery("#editBtn"+ref).addClass("btn-success").removeClass("btn-primary");
        }
    });
  }

  function update() {
    var ref = jQuery("#editRef").val();
    var code = escapeHtml(jQuery("#cloudwokEmbedCode").val());
    jQuery.ajax({
        type: "POST",
        url: "' . $adminUrl . '",
        data: "cwnonce=' . $nonce . '&ref="+ref+"&code="+code,
        success: function(resp)
        {
        }
    });
  }

  jQuery( "#saveBtn" ).click(function( event ) {
    event.preventDefault();
    if(!jQuery("#editRef").val()) {
      create();
    } else {
      update();
    }
  });
  jQuery( "#newBtn" ).click(function( event ) {
    event.preventDefault();
    jQuery("#editRef").val("");
    jQuery("#cloudwokEmbedCode").val("");
    jQuery(".editBtn").removeClass("btn-success").removeClass("btn-primary").addClass("btn-primary");
  });

  jQuery( document ).ready(function() {
    jQuery("#editRef").val("");
    var refList = jQuery.parseJSON(JSON.stringify(' . cw_db_get_all() . '));
    jQuery("#cloudwokEmbedCodeList").append( "<div class=\"row\"><div class=\"col-md-4\"><h4><i class=\"fa fa-code\"></i> SHORTCODE</h4></div></div>" );
    jQuery.each(refList,function(index,ref) {
      appendRefListItem(ref);
    });
    jQuery("#cloudwokEmbedCode").prop("placeholder","Please enter your embed code here (visit https://www.cloudwok.com/developers to generate your customized code)");
  });';
  echo '</script>';
}

?>
