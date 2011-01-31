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