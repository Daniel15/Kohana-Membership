/**
 * Lightbox
 * Author: Daniel15 <dan.cx>
 */
var Lightbox = new Class(
{
	Implements: [Options, Events],
	
	options:
	{
		width: 500,
		height: 200,
		content: 'No content'
	},
	
	/**
	 * Create a new instance of the lightbox
	 * @param options	array	Options for the lightbox
	 */
	initialize: function(options)
	{
		this.setOptions(options);
		this.ensureControls();
		window.addEvent('resize', this.reposition.bind(this));
	},
	
	/**
	 * Ensure all the lightbox elements have been created. Done line this so we can handle each
	 * lightbox having its own DIV in the future.
	 */
	ensureControls: function()
	{
		if ($('lightbox-back'))
		{
			this.back = $('lightbox-back');
			this.container = $('lightbox-container');
			this.lightbox = $('lightbox');
			return;
		}
		
		// Lightbox background dimming
		this.back = new Element('div', { 'id': 'lightbox-back' }).inject(document.body);
		
		this.container = new Element('div', { 'id': 'lightbox-container' }).inject(document.body);
		this.lightbox = new Element('div', { 'id': 'lightbox', 'class': 'lightbox' }).inject(this.container);
	},
	
	/**
	 * Show the lightbox
	 */
	show: function()
	{
		this.lightbox
			.setStyle('width', this.options.width)
			.setStyle('height', this.options.height)
			.addClass('loading');
		this.back.setStyle('display', 'block');
		this.container.setStyle('display', 'block');
		this.visible = true;
		
		this.loadContent();
		this.reposition();
	},
	
	/**
	 * Hide the lightbox
	 */
	hide: function()
	{
		this.visible = false;
		this.back.setStyle('display', 'none');
		this.container.setStyle('display', 'none');
	},
	
	/**
	 * Load the content for the lightbox
	 */
	loadContent: function()
	{
		this.lightbox
			.set('html', this.options.content)
			.removeClass('loading');
	},
	
	/**
	 * Position the lightbox in the centre of the screen
	 */
	reposition: function()
	{
		if (!this.visible)
			return;
			
		var winsize = window.getSize();
		var size = this.lightbox.getSize();
		
		this.lightbox.setStyles(
		{
			top: (winsize.y - size.y) / 2
		});
	}
});

Lightbox.Request = new Class(
{
	Extends: Lightbox,
	
	options:
	{
		/*
		onLoad: $empty,
		*/
		url: null 
	},
	request: null,
	
	initialize: function(options)
	{
		this.parent(options);
		this.request = new Request.HTML(
		{
			url: this.options.url,
			update: this.lightbox,
			noCache: true,
			onSuccess: this.contentLoaded.bind(this)
		});
	},
	
	loadContent: function()
	{
		this.request.get();
	},
	
	contentLoaded: function()
	{
		this.lightbox.removeClass('loading');
		this.reposition();
		this.fireEvent('onLoad');
	}
});