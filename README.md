Kohana-Membership
===================
By [Daniel15](http://dan.cx/)

This is a Kohana module that lets you log via OpenID, Twitter or Facebook. It includes a UI for logging in and connecting multiple identities to the one account.

Installation
-------------
1. Copy css, img and js directories to /css/membership/, /img/membership/ and /js/membership/. You can use symlinks instead
2. Create application/config/membership.php with the following:

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

(TODO: Document these settings)

License
-------
Licensed under the GNU GPL version 3.

Kohana-Membership is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 

Kohana-Membership is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
