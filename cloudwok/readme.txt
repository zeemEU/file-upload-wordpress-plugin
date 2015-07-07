=== CloudWok File-Upload ===
Contributors: markusklems
Donate link: http://www.cloudwok.com/
Tags: upload, file upload, dropbox, google drive, microsoft onedrive, box, s3, facebook, upload form, feedback
Requires at least: 3.0.0
Tested up to: 4.2.2
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

File-upload plugin to let visitors of your site upload files into your Dropbox, Google Drive, Amazon S3, Box.com, etc.

== Description ==

Receive files directly in your Dropbox folder, Google Drive folder, S3 bucket, Box.com folder, or Facebook photo album. With the CloudWok file-upload plugin you can let others upload files to your cloud storage.

With the CloudWok WordPress plugin, you can embed a cloudwok file-upload form into your own website or blog. Visitors of your website can upload files that are transferred directly into your connected Dropbox folder (or Google Drive folder, ...).

For example, with the CloudWok Wordpress plug-in, you can:

* Create a wedding website where you let wedding guests upload photos directly into your Dropbox, Google Drive, ...
* Create a project website with passowrd-protected file-upload form, through which you collect project deliverables and submissions from colleagues, collaborators, and business partners.
* Create a blog where you invite your audience to submit pictures and videos for a contest.

Share your use case with us by sending us a link to your Wordpress site at markus@cloudwok.com. We are always happy to hear feedback from our users. Sometimes so much, that we give away service upgrades and upload quota for free.

== Installation ==

How to install the plugin and get it working in 5 minutes.

=== Create a CloudWok ===

1. Go to https://www.cloudwok.com and create an account.
2. Create a CloudWok that is either connected to a folder in your Dropbox, Google Drive, Box.com account or other cloud storage accounts that are supported by CloudWok.
3. After you have created a Wok, you get a URL to an upload website, such as this: https://www.cloudwok.com/u/AneJ. The last four letters are your "wok id" (in this example: AneJ).

=== Install the plug-in ===

1. Upload `cloudwok.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

=== Use the plug-in ===

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

== Frequently Asked Questions ==

So far no questions. Send us an e-mail if you have an issue: markus@cloudwok.com or a tweet @cloudwok.

== Screenshots ==

1. File-upload form in a WordPress blog post view.
2. Shortcode that adds a file-upload form to a blog post.

== Changelog ==

== Upgrade Notice ==
