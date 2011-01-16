<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Member extends Controller_Template
{
	protected $membership;
	protected $session;
	
	public function before()
	{
		parent::before();
		$this->session = Session::instance();
		$this->membership = Membership::instance();
	}
	
	public function action_index()
	{
		// If they're not logged in, go to the login page
		if (!$this->membership->logged_in())
			$this->request->redirect('member/login');
			
		$this->template->body = $page = new View('member/index');
		$page->token = $this->get_token();
		$page->member = $this->membership->get_member();
		$page->login_box = new View('member/login_box');
		$page->login_box->providers = Kohana::config('membership.providers');
	}
	
	/**
	 * Login. Shows a login widget. When a provider is clicked, redirect to that provider to log in
	 */
	public function action_login($provider_name = null)
	{
		// If a provider is posted, it overrides the URL
		if (!empty($_POST['provider']))
			$provider_name = $_POST['provider'];
			
		if (!empty($provider_name))
		{
			$provider = Membership_Provider::factory($provider_name);
			if (!empty($_POST['openid_identifier']))
				$provider->username = html::chars($_POST['openid_identifier']);
			$provider->startLogin();
			die();
		}
		
		$this->template->body = $page = new View('member/login');
		$page->login_box = new View('member/login_box');
		$page->login_box->providers = Kohana::config('membership.providers');
	}
	
	/**
	 * Return - Called when we return from the provider's login page. Validate the login. Check if 
	 * the user already has an account, and redirect to a registration page if not.
	 */
	public function action_return($provider)
	{
		$provider_name = $provider;
		$provider = Membership_Provider::factory($provider);
		if (!($user = $provider->verifyLogin()))
		{
			echo 'Failed';
			die();
		}
		
		// Try to look up this user
		$identity = ORM::factory('identity')
			->where('identity', '=', $user['identity'])
			->find();
			
		// If they're loaded, they're a member
		if ($identity->loaded())
		{
			// Log in as this user
			$identity->login();
			$this->session->set('message', 'Welcome back, ' . html::chars($identity->member->name) . '!');
			$this->request->redirect('');
		}
		
		// Otherwise, if we're here, this identity isn't associated with any one yet.
		// Are they currently logged in?
		if ($this->membership->logged_in())
		{			
			$member = $this->membership->get_member();
			
			// Associate their new OpenID with their current account.
			// TODO: Remove code duplication with action_register() below.
			$identity = ORM::factory('identity');
			$identity->identity = $user['identity'];
			$identity->member = $member;
			$identity->provider = $provider_name;
			$identity->display_name = $user['display_name'];
			$identity->save();
			$this->session->set('message', 'Attached identity "' . html::chars($user['display_name']) . '" (' . $provider_name .') to your account.');
			$this->request->redirect('member');
		}
		
		// Otherwise, they need a new account
		// Redirect to the registration page
		$this->session->set('reg_openid', $user);
		$this->session->set('reg_openid_provider', $provider_name);
		$this->request->redirect('member/register');
	}
	
	/**
	 * Register - Called when they log in and aren't a current member. Shows a CAPTCHA and creates
	 * an account for them
	 */
	public function action_register()
	{		
		// Grab the user data from the session
		if (($user = $this->session->get('reg_openid')) == null)
			throw new Exception('Register called but no OpenID in session');
		
		// Override default data with POST data - But don't override identity!
		$data = array(
			'identity' => $user['identity'],
			'name' => isset($_POST['name']) ? $_POST['name'] : $user['name'],
			'email' => isset($_POST['email']) ? $_POST['email'] : $user['email'],
			'recaptcha_challenge_field' => isset($_POST['recaptcha_challenge_field']) ? $_POST['recaptcha_challenge_field'] : '',
		);
		
		// Set up our validation
		$post = Validate::factory($data)
			->filter(true, 'trim')
			->callback('recaptcha_challenge_field', 'Recaptcha::validate')
			->rule('identity', 'Model_Identity::unique_identity')
			->rule('name', 'not_empty')
			->rule('name', 'Model_Member::unique_name')
			->rule('email', 'email');
		
		// If posted, and the post is valid
		if ($_POST && $post->check())
		{			
			// Create an account for them
			$member = ORM::factory('member');
			$member->name = $post['name'];
			$member->email = $data['email']; // Change to $post when using validation.
			$member->save();
			
			// TODO: Remove code duplication with action_return() above.
			$provider_name = $this->session->get('reg_openid_provider');			
			$identity = ORM::factory('identity');
			$identity->identity = $post['identity'];
			$identity->provider = $provider_name;
			$identity->display_name = $user['display_name'];
			$identity->member = $member;
			$identity->save();
			
			// Clear the temp session data
			$this->session->delete('reg_openid');
			$this->session->delete('reg_openid_type');
			$this->session->get('reg_openid_type');
			
			// Log in as this user
			$identity->login();
			$this->session->set('message', 'Thanks for registering, ' . html::chars($data['name']) . '!');
			$this->request->redirect('');
		}
			
		$this->template->body = $page = new View('member/register');
		$page->errors = $post->errors('membership');
		$page->data = $data;
	}
	
	/**
	 * Log the user out
	 */
	public function action_logout()
	{
		$this->membership->logout();
		$this->request->redirect('');
	}
	
	/**
	 * Delete a member's identity
	 * @param int ID of identity to delete
	 */
	public function action_delete_identity($identity)
	{
		$this->validate_token($_GET['token']);
		
		// Load the identity and check the current user actually owns it.
		$identity = ORM::factory('identity', $identity);
		if ($identity->member->id != $this->membership->get_member()->id)
			throw new Exception('You don\'t own that identity, so can\'t delete it!');
		
		$display_name = $identity->display_name;
		$identity->delete();
		
		$this->session->set('message', 'Deleted identity "' . $display_name . '"');
		$this->request->redirect('member');
	}
	
	/**
	 * Get a token to prevent CSRF
	 * @return string A random string to use as the token
	 */
	protected function get_token()
	{
		if (($token = $this->session->get('member_csrf')) === null)
		{
			$this->session->set('member_csrf', ($token = text::random('alnum', 16)));
		}

		return $token;
	}

	/**
	 * Verify the CSRF token
	 * @param string Token from request
	 */
	protected function validate_token($token)
	{
		if ($token !== $this->session->get('member_csrf'))
			throw new Exception('Request failed CSRF validation');
	}
	
	
}

?>