<?php defined('SYSPATH') or die('No direct script access.'); ?>

<p>Log in with:</p>
<?php echo $login_box; ?>
<p>If you don't have any of these accounts, please <a href=
"https://www.myopenid.com/signup?affiliate_id=<?php echo Kohana::config('membership.providers.myopenid.affiliate_id') ?>&amp;openid.sreg.required=email,nickname"
>sign up for a free MyOpenID account</a>.</p>