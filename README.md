Kohana-Membership
===================
By [Daniel15](http://dan.cx/)

This is a Kohana module that lets you log via OpenID, Twitter or Facebook. It includes a UI for logging in and connecting multiple identities to the one account.

Installation
-------------
1. Copy css, img and js directories to /css/membership/, /img/membership/ and /js/membership/. You can use symlinks instead
2. Create application/config/membership.php with the following:
<pre>
<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'providers' => array(
		'facebook' => array(
			'client_id' => 'TODO',
			'client_secret' => 'TODO',
		),
		'myopenid' => array(
			'affiliate_id' => TODO,
		),
		'twitter' => array(
			'key' => 'TODO',
			'secret' => 'TODO',
		),
	),
);
</pre>
(TODO: Document these settings)