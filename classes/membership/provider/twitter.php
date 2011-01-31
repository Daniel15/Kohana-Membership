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

/**
 * Membership provider for Twitter logins
 * TODO: Genericize instead of being Twitter-specific
 */
class Membership_Provider_Twitter extends Membership_Provider
{
	const REQUEST_TOKEN_URL = 'http://api.twitter.com/oauth/request_token';
	const AUTHORIZE_URL = 'https://api.twitter.com/oauth/authenticate';
	const ACCESS_TOKEN_URL = 'http://twitter.com/oauth/access_token';
	const PROFILE_URL = 'http://twitter.com/account/verify_credentials.json';
	
	const PROFILE_URL_PREFIX = 'http://twitter.com/';
	
	protected $oauth;
	
	public function __construct($provider_name, $options)
	{
		parent::__construct($provider_name, $options);
		$this->oauth = new OAuth($options['key'], $options['secret'], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
	}
	
	public function startLogin()
	{
		$token = $this->oauth->getRequestToken(self::REQUEST_TOKEN_URL, $this->return_url);
		Session::instance()->set('oauth_token_secret', $token['oauth_token_secret']);
		header('Location: ' . self::AUTHORIZE_URL . '?oauth_token=' . $token['oauth_token']);
		die();
	}
	
	public function verifyLogin()
	{
		$this->oauth->setToken($_GET['oauth_token'], Session::instance()->get('oauth_token_secret'));
		$access_token = $this->oauth->getAccessToken(self::ACCESS_TOKEN_URL);
		
		$this->oauth->setToken($access_token['oauth_token'], $access_token['oauth_token_secret']);
		$this->oauth->fetch(self::PROFILE_URL);
		$user = json_decode($this->oauth->getLastResponse());
		
		return array(
			'identity' => self::PROFILE_URL_PREFIX . $user->screen_name,
			'email' => null,
			'name' => $user->name,
			'display_name' => $user->screen_name,
		);
	}
}
?>