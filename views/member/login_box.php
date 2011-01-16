<?php defined('SYSPATH') or die('No direct script access.'); ?>

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