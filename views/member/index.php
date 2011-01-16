<?php defined('SYSPATH') or die('No direct script access.'); ?>

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