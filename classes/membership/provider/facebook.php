<?php
/*
 * Kohana-Membership
 * Copyright (C) 2011, Daniel Lo Nigro (Daniel15) <daniel at dan.cx>
 * http://go.dan.cx/kohana-membership
 * 
 * This file is part of Kohana-Membership.
 * 
 * Kohana-Membership is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Kohana-Membership is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Kohana-Membership.  If not, see <http://www.gnu.org/licenses/>.
 */
 
defined('SYSPATH') or die('No direct script access.');

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