<?php defined('SYSPATH') or die('No direct script access.');

class Membership_Provider_Facebook extends Membership_Provider
{
	const AUTHORIZE_URL = 'https://graph.facebook.com/oauth/authorize';
	const TOKEN_URL = 'https://graph.facebook.com/oauth/access_token';
	const PROFILE_URL = 'https://graph.facebook.com/me';
	
	public function startLogin()
	{
		$data = array(
			'client_id' => $this->settings['client_id'],
			'redirect_uri' => $this->return_url,
			'type' => 'web_server',
		);
		
		header('Location: ' . self::AUTHORIZE_URL . '?' . http_build_query($data, null, '&'));
		die();
	}
	
	public function verifyLogin()
	{
		$data = array(
			'client_id' => $this->settings['client_id'],
			'redirect_uri' => $this->return_url,
			'client_secret' => $this->settings['client_secret'],
			'code' => $_GET['code'],
		);

		// Get an access token
		$result = @file_get_contents(self::TOKEN_URL . '?' . http_build_query($data, null, '&'));
		parse_str($result, $result_array);
		
		// Make sure we actually have a token
		if (empty($result_array['access_token']))
			throw new Exception('Invalid response received from Facebook. Response = "' . $result . '"');
		
		// Grab the user's data
		$access_token = $result_array['access_token'];
		$user = json_decode(file_get_contents(self::PROFILE_URL . '?access_token=' . $access_token));
		if ($user == null)
			throw new Exception('Invalid user data returned from Facebook');
			
		return array(
			'identity' => $user->link,
			'email' => null,
			'name' => $user->name,
			'display_name' => $user->name,
		);
	}
}
?>