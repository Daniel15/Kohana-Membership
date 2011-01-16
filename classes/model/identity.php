<?php defined('SYSPATH') or die('No direct script access.');
class Model_Identity extends ORM
{
	protected $_belongs_to = array(
		'member' => array(),
	);
	
	/**
	 * Validates that this identity is unique
	 * @param string URL to validate
	 * @return bool True if the URL is unique, false otherwise.
	 */
	public static function unique_identity($identity)
	{
		return !DB::select(array(DB::expr('COUNT(identity)'), 'total'))
			->from('identities')
			->where('identity', '=', $identity)
			->execute()
			->get('total');
	}
	
	public function login()
	{
		return Membership::instance()->login($this);
	}
}
?>