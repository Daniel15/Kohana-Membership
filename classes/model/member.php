<?php defined('SYSPATH') or die('No direct script access.');
class Model_Member extends ORM
{
	protected $_has_many = array(
		'identities' => array(),
	);
	
	/**
	 * Validates that this name is unique 
	 * @param string Name to validate
	 * @return bool True if the username is unique, false otherwise.
	 */
	public static function unique_name($name)
	{
		return !DB::select(array(DB::expr('COUNT(name)'), 'total'))
			->from('members')
			->where('name', '=', $name)
			->execute()
			->get('total');
	}
}
?>