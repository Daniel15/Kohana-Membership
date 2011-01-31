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

class Membership_Provider_Openid extends Membership_Provider
{
	protected $openid;
	protected $generic;
	
	public function __construct($provider_name, $settings)
	{
		parent::__construct($provider_name, $settings);
		$this->openid = new Vendor_LightOpenID;
		
		// Is this a generic OpenID, or a specific ("branded" / shows button on login page) one?
		$this->generic = $provider_name == 'openid';
	}
	
	public function startLogin()
	{
		$this->openid->identity = str_replace('{username}', $this->username, $this->provider_url);
		$this->openid->returnUrl = $this->return_url;
		$this->openid->required = array('namePerson/friendly', 'contact/email');
		header('Location: ' . $this->openid->authUrl());
		die();
	}
	
	public function verifyLogin()
	{
		if (!($this->openid->validate()))
		{
			return false;
		}
		
		$attributes = $this->openid->getAttributes();
		
		$return = array(
			'identity' => $this->openid->identity,
			'email' => !empty($attributes['contact/email']) ? $attributes['contact/email'] : null,
			'name' => !empty($attributes['namePerson/friendly']) ? $attributes['namePerson/friendly'] : null,
		);

		// If it's a "generic" OpenID, return the identity
		if ($this->generic || empty($return['name']))
			$return['display_name'] = $return['identity'];
		else
			$return['display_name'] = $return['name'];
		
		return $return;
	}
}
?>