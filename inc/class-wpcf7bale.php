<?php
class wpcf7_Bale
{

	private
		$bot_token;
	private
		$bot_token_bale;
	private
		$bot_api_bale;

	public
		$domain = 'wpcf7_telegram',
		$api_url = 'https://api.telegram.org/bot%s/',
		$chats = array(),
		$api_url_bale = 'https://apitest.bale.ai/v1/bots/http/',
		$chats_bale = array();

	function __construct()
	{
		$this->load_bot_token();
		$this->load_chats();

		$this->load_bot_api_bale();
		$this->load_bot_token_bale();
		$this->load_chats_bale();

		add_action('admin_menu', array($this, 'menu_page'));
		add_action('admin_init', array($this, 'settings_section'));

		add_action('wpcf7_init', array($this, 'wpcf7_tg_shortcode'));
		//add_action('wpcf7_mail_sent', array($this, 'wpcf7_tg_mail_sent'));
		add_action('wpcf7_mail_sent', array($this, 'wpcf7_tg_mail_sent_bale'));

		if ($this->current_action() == 'save') :
			$this->save_bot_token();
			$this->save_chats();
			$this->save_bot_api_bale();
			$this->save_bot_token_bale();
			$this->save_chats_bale();
		endif;
	}



	function settings_section()
	{
		add_settings_section(
			'wpcf7_tg_sections__main',
			__('Bot-settings', $this->domain),
			array($this, 'wpcf7_tg_sections__main_callback_function'),
			'wpcf7_tg_settings_page'
		);


		add_settings_field('bot_token2_api',  __('Bale Bot API<br/><small>You need to use bale bot api url.<br/><a target="_blanc" href="https://dev.bale.ai/quick-start">Help</a> for example : https://apitest.bale.ai/v1/bots/http/ </small>', $this->domain), array($this, 'wpcf7_tg_settings_clb'), 'wpcf7_tg_settings_page', 'wpcf7_tg_sections__main', array(
			'type'		=> 'text',
			'name'		=> 'wpcf7_bale_api',
			'value'		=> $this->get_bot_api_bale()
		));

		add_settings_field('bot_token2',  __('Bale Bot Token<br/><small>You need to create your own Bale-Bot.<br/><a target="_blanc" href="https://dev.bale.ai/quick-start">How to create</a></small>', $this->domain), array($this, 'wpcf7_tg_settings_clb'), 'wpcf7_tg_settings_page', 'wpcf7_tg_sections__main', array(
			'type'		=> 'text',
			'name'		=> 'wpcf7_bale_tkn',
			'value'		=> $this->get_bot_token_bale()
		));

		add_settings_field('chat_id2',  __('Bale Chat ID(s)<br/><small>Type there one or more chat ids separated by commas. like : 485750575<br/><a target="_blanc" href="https://ble.im/get_id_bot">How to get Chat ID</a></small>', $this->domain), array($this, 'wpcf7_tg_settings_clb'), 'wpcf7_tg_settings_page', 'wpcf7_tg_sections__main', array(
			'type'		=> 'text',
			'name'		=> 'wpcf7_bale_chats',
			'value'		=> $this->chats_bale
		));
	}

	function wpcf7_tg_settings_clb($data)
	{
		switch ($data['type']) {
			case 'text':;
			case 'password':
				echo
					'<input type="' . $data['type'] . '" ' .
						'name="' . $data['name'] . '" ' .
						'value="' . $data['value'] . '"' .
						'class="large-text" ' .
						'/>';
				break;
		}
	}

	function menu_page()
	{
		add_submenu_page('wpcf7', 'CF7 Bale Bot', 'CF7 Bale Bot', 'wpcf7_read_contact_forms', 'wpcf7_tg', array($this, 'wpcf7_tg_plugin_menu_cbf'));
	}
	function wpcf7_tg_plugin_menu_cbf()
	{
		?>
	<div class="wrap">
		<h1><?php echo __('Bale notificator settings', $this->domain); ?></h1>

		<?php settings_errors(); ?>
		<form method="post" action="admin.php?page=wpcf7_tg">
			<?php settings_fields('wpcf7_tg_settings_page'); ?>
			<?php do_settings_sections('wpcf7_tg_settings_page'); ?>
			<input type="hidden" name="wpcf7_tg_action" value="save" />
			<p><?php echo __('Just use the shortcode <code>[balebot]</code> in the form for activate notification through Telegram.', $this->domain); ?></p>
			<?php submit_button(); ?>
		</form>

	</div>
<?php
}

function wpcf7_tg_sections__main_callback_function()
{
	echo '';
}

function wpcf7_tg_shortcode()
{
	wpcf7_add_form_tag('balebot', array($this, 'wpcf7_tg_shortcode_handler'));
}

function wpcf7_tg_shortcode_handler()
{
	return '<input type="hidden" name="wpcf7_telegram" value="1" />';
}

function wpcf7_tg_mail_sent($mail)
{
	if (isset($_REQUEST['wpcf7_telegram'])) :
		$output = wpcf7_mail_replace_tags($mail->mail['body']);
		$chats = explode(',', $this->chats);
		foreach ($chats as $chat)
			$this->apiRequest(
				'sendMessage',
				[
					'chat_id'	=> $chat,
					'text'		=> $output
				]
			);
	endif;
}

function wpcf7_tg_mail_sent_bale($mail)
{
	if (isset($_REQUEST['wpcf7_telegram'])) :
		$output = wpcf7_mail_replace_tags($mail->mail['body']);
		$chats = explode(',', $this->chats_bale);
		foreach ($chats as $chat)
			$this->apiRequest_bale(
				'sendMessage',
				[
					'chat_id'	=> $chat,
					'text'		=> $output
				]
			);
	endif;
}

function current_action()
{
	if (isset($_REQUEST['wpcf7_tg_action']))
		return $_REQUEST['wpcf7_tg_action'];

	return '';
}

private function load_bot_token()
{
	$this->bot_token = get_option('wpcf7_telegram_tkn');
	return $this;
}
private function load_bot_api_bale()
{
	$this->bot_api_bale = get_option('wpcf7_bale_api');
	return $this;
}
private function load_bot_token_bale()
{
	$this->bot_token_bale = get_option('wpcf7_bale_tkn');
	return $this;
}
private function set_bot_token($token)
{
	$this->bot_token = $token;
	update_option('wpcf7_telegram_tkn', $token);
	return $this;
}
private function save_bot_token()
{
	$token = $_REQUEST['wpcf7_telegram_tkn'];
	$this->bot_token = $token;
	update_option('wpcf7_telegram_tkn', $token);
	return $this;
}
private function set_bot_token_bale($token_bale)
{
	$this->bot_token_bale = $token_bale;
	update_option('wpcf7_bale_tkn', $token_bale);
	return $this;
}
private function save_bot_api_bale()
{
	$token = $_REQUEST['wpcf7_bale_api'];
	$this->bot_api_bale = $token;
	update_option('wpcf7_bale_api', $token);
	return $this;
}
private function save_bot_token_bale()
{
	$token = $_REQUEST['wpcf7_bale_tkn'];
	$this->bot_token_bale = $token;
	update_option('wpcf7_bale_tkn', $token);
	return $this;
}

private function get_bot_token()
{
	return $this->bot_token;
}

private function get_bot_api_bale()
{
	return $this->bot_api_bale;
}

private function get_bot_token_bale()
{
	return $this->bot_token_bale;
}
private function get_api_url()
{
	return sprintf($this->api_url, $this->bot_token);
}
private function get_api_url_bale()
{
	return  $this->bot_api_bale . $this->bot_token_bale;
}

function load_chats()
{
	$this->chats = get_option('wpcf7_telegram_chats');
	return $this;
}

function save_chats()
{
	$chats = $_REQUEST['wpcf7_telegram_chats'];
	$this->chats = $chats;
	update_option('wpcf7_telegram_chats', $this->chats);
	return $this;
}

function load_chats_bale()
{
	$this->chats_bale = get_option('wpcf7_bale_chats');
	return $this;
}

function save_chats_bale()
{
	$chats = $_REQUEST['wpcf7_bale_chats'];
	$this->chats_bale = $chats;
	update_option('wpcf7_bale_chats', $this->chats_bale);
	return $this;
}

function exec_wp_request($url, $args)
{
	$response = wp_remote_post($url, $args);

	if (is_wp_error($response)) :
		error_log("wp_remote_post returned error : " . $response->get_error_code() . ': ' . $response->get_error_message() . ' : ' . $response->get_error_data() . "\n");
		return false;
	endif;
	$http_code = intval($response['response']['code']);
	if ($http_code >= 500) {
		// do not wat to DDOS server if something goes wrong
		sleep(3);
		return false;
	} elseif ($http_code != 200) {
		error_log("Request has failed with error {$response['response']['code']}: {$response['response']['message']}\n");
		if ($http_code == 401) {
			throw new Exception('Invalid access token provided');
		}
		return false;
	} else {
		return true;
	}
}


function exec_wp_request_bale($url, $args, $parameters)
{

	$array_with_parameters = array(
		'$type' => 'Request',
		'body' =>
		array(
			'$type' => 'SendMessage',
			'randomId' => '13851122' . $this->generateRandomString(),
			'peer' =>
			array(
				'$type' => 'User',
				'accessHash' => '0011' . $this->generateRandomString() . '83383',
				'id' => $parameters["chat_id"],
			),
			'message' =>
			array(
				'$type' => 'Text',
				'text' => $parameters["text"] . '

https://qavami.com/wp-admin/admin.php?page=vxcf_leads&tab=entries&form_id=cf_1805&msg=1',
			),
			'quotedMessage' => NULL,
		),
		'service' => 'messaging',
		'id' => '2' . $this->generateRandomString(5),
	);

	/*
			*/

	$url2 = $url;
	$data = wp_remote_post($url2, array(
		'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
		'body'        => json_encode($array_with_parameters),
		'method'      => 'POST',
		'data_format' => 'body',
	));
}

function generateRandomString($length = 10)
{
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function apiRequest($method, $parameters)
{
	if (!is_string($method)) {
		error_log("Method name must be a string\n");
		return false;
	}

	if (!$parameters) {
		$parameters = array();
	} else if (!is_array($parameters)) {
		error_log("Parameters must be an array\n");
		return false;
	}

	foreach ($parameters as $key => &$val) {
		// encoding to JSON array parameters, for example reply_markup
		if (!is_numeric($val) && !is_string($val)) {
			$val = json_encode($val);
		}
	}
	$url = $this->get_api_url() . $method . '?' . http_build_query($parameters);

	$args = array(
		'timeout'		=> 5,
		'redirection'	=> 5,
		'blocking'		=> true,
		'method'		=> 'GET',
	);
	return $this->exec_wp_request($url, $args);
}


function apiRequest_bale($method, $parameters)
{
	if (!is_string($method)) {
		error_log("Method name must be a string\n");
		return false;
	}

	if (!$parameters) {
		$parameters = array();
	} else if (!is_array($parameters)) {
		error_log("Parameters must be an array\n");
		return false;
	}

	foreach ($parameters as $key => &$val) {
		// encoding to JSON array parameters, for example reply_markup
		if (!is_numeric($val) && !is_string($val)) {
			$val = json_encode($val);
		}
	}
	$url = $this->get_api_url_bale(); //. $method;  //.'?'.http_build_query($parameters);

	$args = array(
		'timeout'		=> 5,
		'redirection'	=> 5,
		'blocking'		=> true,
		'method'		=> 'GET',
	);
	return $this->exec_wp_request_bale($url, $args, $parameters);
}
}
