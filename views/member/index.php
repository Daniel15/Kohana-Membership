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

<p>The following identities are attached to your account. You may use any of them to sign in:</p>
<ul>
<?php
foreach ($member->identities->find_all() as $identity)
{
	echo '
	<li><a href="', url::site('member/delete_identity/' . $identity->id), '?token=', $token, '" class="delete">[delete]</a> <img src="img/membership/icons/', $identity->provider, '.png" alt="', $identity->provider, '" /> ', $identity->display_name, '</li>';
}
?>
</ul>


<p>Attach another identity to your account:</p>
<?php echo $login_box; ?>