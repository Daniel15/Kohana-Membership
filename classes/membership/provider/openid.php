<?php defined('SYSPATH') or die('No direct script access.');

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