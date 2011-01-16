<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Base class for all membership providers 
 */
abstract class Membership_Provider
{
	// Class prefix for all membership providers. Used by the factory method.
	const PROVIDER_CLASS_PREFIX = 'Membership_Provider_';
	
	public $provider_url;
	public $return_url;
	public $username;
	
	protected $provider_name;
	protected $settings;
	
	public function __construct($provider_name, $settings)
	{
		$this->provider_name = $provider_name;
		$this->settings = $settings;
	}
	
	/**
	 * Start the login - Redirect to the provider's website to login.
	 */
	abstract function startLogin();
	/**
	 * Verify the login - Verify the token returned by the provider, based on
	 * POST or GET data.
	 */
	abstract function verifyLogin();
	
	/**
	 * Create an instance of the provider passed
	 */
	public static function factory($provider_name)
	{
		// Load their provider 
		$settings = Kohana::config('membership.providers.' . $provider_name);
		if ($settings == null)
		{
			throw new Exception('Invalid OpenID provider specified');
		}
		
		// Is there a class for this particular type?
		// TODO: During dev, this is slow, as it does a filesystem lookup. Will be cached in production
		// so speed won't be an issue.
		if (Kohana::find_file('classes', 'membership/provider/' . $provider_name) !== false)
			$class_name = self::PROVIDER_CLASS_PREFIX . ucfirst($provider_name);
		// Otherwise, is there a type?
		elseif (!empty($settings['type']))
			$class_name = self::PROVIDER_CLASS_PREFIX . ucfirst($settings['type']);
		// Otherwise, default to OpenID
		else
			$class_name = self::PROVIDER_CLASS_PREFIX . 'Openid';
			
		$provider = new $class_name($provider_name, $settings);
		$provider->return_url = url::site('member/return/' . $provider_name, true);
		if (!empty($settings['url']))
			$provider->provider_url = $settings['url'];
		
		return $provider;
	}
}
?>