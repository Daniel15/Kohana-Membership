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
 * Membership helper class
 */
class Membership
{
	private $session;
	private static $instance;
	private $member;
	
	protected function __construct()
	{
		$this->session = Session::instance();
	}
	
	public static function instance()
	{
		if (self::$instance == null)
			self::$instance = new Membership();
			
		return self::$instance;
	}
	
	/**
	 * Log in a user
	 * @param Model_Identity Identity to log in as
	 */
	public function login(Model_Identity $identity)
	{
		$this->session->set('logged_in', true);
		$this->session->set('member', $identity->member->id);
		$this->session->set('member_identity', $identity->id);
	}
	
	/**
	 * log out the currently logged in user
	 */
	public function logout()
	{
		$this->session->delete('logged_in');
		$this->session->delete('member');
		$this->session->delete('member_identity');
	}
	
	/**
	 * Check if user is logged in
	 * @return bool True if logged in, false otherwise
	 */
	public function logged_in()
	{
		return (bool)$this->session->get('logged_in');
	}
	
	/**
	 * Get the details of the member currently logged in
	 * @return Model_Member Logged in member
	 */
	public function get_member()
	{
		if (!$this->logged_in())
			return false;
			
		if ($this->member == null)
			$this->member = ORM::factory('member', $this->session->get('member'));
		
		return $this->member;
	}
}
?>