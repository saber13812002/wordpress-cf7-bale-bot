
=== Contact Form 7 - CF7 Bale Bot Messenger ===

Contributors: sabertaba

Donate link: https://berimbasket.ir/

Tags: balemessenger, balebot , cf7 to bale , contact form to Bale Messenger

Requires at least: 3.0

Tested up to: 5.0

Requires PHP: 5.4

Stable tag: trunc

License: GPLv2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin allows to post CF7-messages to you through Bale-bot. Just use shortcode [balebot] in your CF7-form. (not in page or beside short code. in post)

== Description ==

This plugin allows to send Contact Form 7 messages to your Bale-Bot-chat. For this you need to make several simple steps.

1. Create the Bale-Bot and save the Bot-Token parameter on the settings page Contact Form 7 - CF7 Bale Bot.

2. Paste the shortcode <code>[balebot]</code> in your contact form template for activate sending to Telegaram.

3. Get your Chat ID from https://ble.im/get_id_bot and save this on the settings page Contact Form 7 - CF7 Bale. You can see your Chat ID by typing anything to Bale-Bot.

4. Start chat with your bot, you created in first step. Use the same Bale-account as the 3-rd step. Just click the START button. your other users should start your bot 

This plugin uses [API Bale](https://dev.bale.ai/api "Bale docs") and makes remote HTTP-requests to Bale servers for sending your notifications.


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin
1. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)


== Frequently Asked Questions ==



= How to create the Telegram-Bot? =



It is very simple. Please, follow to  [official documentation](https://core.telegram.org/bots#3-how-do-i-create-a-bot "Telegram docs").



= What is Chat ID & how to get it? =



The Chat ID parameter is your Telegram-identifier. But this is not your phone number or Telegram-login (@xxxxxxxx). 

You can see your Chat ID by typing anything to Telegram-Bot <code>@wpcf7Bot</code>.


== Screenshots ==

1. if you define text in CF7 for email that text uses , `/assets/screenshot-1.png`

2. Bale Bot setting in CF7 setting page here , `/assets/screenshot-2.png`

== Changelog ==

= 0.1 =
* it can sent messages to bale when users fill your Cf7 forms

== Upgrade Notice ==

= 0.1 =
When Api for Bale bot changed or want to call another messenger 


== A brief Markdown Example ==

Setting Page Sample:

1. api link : https://apitest.bale.ai/v1/bots/http/
2. token : o324ui5y2o3i4yu5io32u4yi5o2y34o5y2u3y4o5u32y45ouiy2
3. admin bot ids: 34534534,3453453,2342342
