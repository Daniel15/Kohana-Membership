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
defined('SYSPATH') or die('No direct script access.'); ?>

<!-- Begin login box include -->
<div id="login-box">
	<div id="logging-in">Logging in, please wait...</div>
		
	<ul id="providers">
	<?php
	foreach ($providers as $provider_name => $provider)
	{
		echo '
		<li class="', $provider_name, '"><a href="', url::site('member/login/' . $provider_name), '">', $provider_name, '</a></li>';
	}
	?>
	</ul>

	<?php echo form::open('member/login'); ?>
		
		<div id="username_panel">
			<label for="openid_identifier" id="username_label">Enter your OpenID:</label>
			<input id="openid_identifier" name="openid_identifier" type="text" class="openid" /><br />
			
			<button type="submit" id="username_provider" name="provider" value="openid">Log In</button>
		</div>
	</form>
</div>
<!-- End login box include -->