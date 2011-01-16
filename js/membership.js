/*
 * TODO: Clean up classes.
 */

Member = {};
/**
 * TODO: Make this a proper MooTools class, to reuse it in inline login boxes.
 */
Member.LoginBox = 
{
	current_provider: null,
	
	require_username: new Hash(
	{
		'myspace': 'MySpace username',
		'openid': 'Enter your OpenID',
		'aol': 'AOL screenname'
	}),
	
	/**
	 * Initialise the login box
	 */
	init: function()
	{
		$('username_panel').setStyle('display', 'none');
		$$('#providers li a').addEvent('click', this.click.bindWithEvent(this));
		$$('#login-box form').addEvent('submit', this.loading);
	},
	
	/**
	 * Called when a provider is clicked
	 * @param event Click event
	 */
	click: function(e)
	{		
		if (this.current_provider != null)
			this.current_provider.removeClass('active');
			
		var provider = $(e.target).getParent();
		var provider_name = provider.get('class').trim();
		// Highlight the provider
		provider.addClass('active');
		this.current_provider = provider;
		
		// Does this provider not require a username?
		if (!this.require_username.has(provider_name))
		{
			$('username_panel').setStyle('display', 'none');
			this.loading();
			return true;
		}
		
		// Display the username panel
		$('username_label').set('html', this.require_username[provider_name] + ': ');
		$('username_provider').set('value', provider_name);
		$('username_panel').setStyle('display', 'block');
		
		var username_box = $('openid_identifier');
		username_box.set('value', '');
		username_box.className = provider_name;
		username_box.focus();
		
		return false;
	},
	
	/**
	 * Called to show the loading indicator
	 */
	loading: function()
	{
		$('logging-in').setStyle('display', 'block');
	}
};

Member.Login = 
{
	init: function()
	{
		Member.LoginBox.init();
	}
};

Member.Index =
{
	init: function()
	{
		Member.LoginBox.init();
		$$('a.delete').addEvent('click', this.del.bind(this));
	},
	
	del: function(e)
	{
		return confirm('Are you sure you want to remove this identity from your account?');
	}
};

window.addEvent('domready', function()
{
	// Don't do this on the login page
	if (document.body.id == 'member-login' || document.body.id == 'member-register')
		return;
	
	if (!window['Lightbox'] && Lightbox['Request'])
		return;
		
	// Do we have a login link in the header?
	var link;
	if (!(link = $('header-login')))
		return;
		
	var lightbox = new Lightbox.Request(
	{
		width: 500,
		height: 'auto',
		url: base_url + 'member/login',
		onLoad: function()
		{
			Member.LoginBox.init();
		}
	});
	
	link.addEvent('click', function()
	{
		lightbox.show();
		return false;
	});
	
	link.store('lightbox', lightbox);
});