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

if (count($errors) > 0)
{
	echo '
	<div class="error">
		<p>Some errors were encountered. Please correct these and try again:</p>
		<ul>
			<li>', implode('</li>
			<li>', $errors), '</li>
		</ul>
	</div>';
}
?>

<p>You don't appear to be a registered user yet. If you'd like to create a new account, we just need a few more details:</p>
<?php echo form::open('member/register'); ?>

	<p>
		<label for="name">Display name:</label>
		<input type="text" name="name" id="name" value="<?php echo html::chars($data['name']) ?>" /><br />
		<small>This is your name as it will be displayed throughout the site.</small>
	</p>
	<p>
		<label for="email">Email address:</label>
		<input type="email" name="email" id="email" value="<?php echo html::chars($data['email']) ?>"<br />
		<small>Just in case we need to contact you.</small>
		
	</p>
	<p>
		<label for="recaptcha_response_field">Security code:</label>
		<?php echo Recaptcha::get_html(); ?>
	</p>
	<p><input name="submit" type="submit" value="Register" /></p>
	<p><em>If you already have a registered account and just want to link this identity to it, please log in to your existing account, and then link this new identity via the account management section.</em></p>
</form>