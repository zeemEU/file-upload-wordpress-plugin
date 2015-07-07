# File-upload Wordpress plugin

Install the CloudWok Wordpress plugin to let visitors of your Wordpress website upload files into your Dropbox, Google Drive, S3, Box.com, etc.

Plugin description: [https://wordpress.org/plugins/cloudwok-file-upload/](https://wordpress.org/plugins/cloudwok-file-upload/)

## Prerequisites

1. Go to https://www.cloudwok.com and create an account.

2. Create a CloudWok that is either connected to a folder in your Dropbox, Google Drive, Box.com account or other cloud storage accounts that are supported by CloudWok.

3. After you have created a Wok, you get a URL to an upload website, such as this: https://www.cloudwok.com/u/AneJ. The last four letters are your "wok id" (in this example: AneJ).

## Install via WordPress Plugin Directory

The simplest way is to click on your menu item "Plugins" in the left-side WordPress admin sidebar.
1. Click on "Add New" and search for the CloudWok plugin.
2. Click on "Install" and then click on "Activate".

## Manually install the plugin

1. Copy the cloudwok.php file to your Wordpress plugin folder (`path/to/your/wordpress/wp-content/plugins`).
2. Click on 'Plugins' on the left-side menu bar. You should see the CloudWok plugin in the list. Click on 'Activate'.

That's it. Any problems? Send an e-mail to markus@cloudwok.com

## Use the plugin

Now you can add shortcodes like the following to your pages, blog posts, etc:

`[cloudwok wok_id="YOUR_WOK_ID" show_powered_by_link="True"]`

In the example, replace `YOUR_WOK_ID` your actual wok id (a four-letter id, such as "AneJ").

By default, the file-upload widget shows a file-upload button (and drag & drop area) and after file uploads shows the uploaded files in a list. You can configure the shortcode as follows:

* `[cloudwok wok_id="YOUR_WOK_ID" show_uploads="False" show_powered_by_link="True"]` Don't show a list of uploaded files
* `[cloudwok wok_id="YOUR_WOK_ID" show_downloads="True" show_powered_by_link="True"]` List all files that have been uploaded so far
* `[cloudwok wok_id="YOUR_WOK_ID" show_form="True" show_powered_by_link="True"]` Show a form where uploaders can enter additional information along with their uploaded files, such as their e-mail and a message.

You can also combine the options of the shortcode, as in this example:

`[cloudwok wok_id="YOUR_WOK_ID" show_uploads="False" show_downloads="True" show_form="True" show_powered_by_link="True"]`

By default, a small "powered by" text-link to www.cloudwok.com is disabled. If you like our plug-in, we would appreciate it if you would enable the link via `[cloudwok wok_id="YOUR_WOK_ID" show_powered_by_link="True"]`.
