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