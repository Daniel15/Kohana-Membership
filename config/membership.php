<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'providers' => array(
		'openid' => array(
			'url' => '{username}',
			'requires_username' => true,
		),
		'myopenid' => array(
			'url' => 'https://www.myopenid.com/',
		),
		'google' => array(
			'url' => 'https://www.google.com/accounts/o8/id',
		),
		'facebook' => array(
		),
		'twitter' => array(
		),
		'yahoo' => array(
			'url' => 'https://me.yahoo.com/',
		),
		'myspace' => array(
			'url' => 'http://www.myspace.com/{username}',
			'requires_username' => true,
		),
		'aol' => array(
			'url' => 'http://openid.aol.com/{username}',
			'requires_username' => true,
		),
	),
);