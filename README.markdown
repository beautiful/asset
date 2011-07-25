# Beautiful Asset

Asset management for Kohana.

## The idea

The idea involves `Asset_Group`s, `Asset`s and `Asset_Filter`s. An
`Asset` represents one file that needs to be included in an output. I
left that previous sentence fairly abstract on purpose. An `Asset` is
simply a resource to be grouped into an `Asset_Group`. That group can
then have various `Asset_Filter`s applied to it when it is rendered.

## Usage

You can use this module with or without configuration. If you do not
want to use Kohana's configuration readers to load your assets then
you may describe them directly in PHP like so:

	$stylesheets = new Asset_Group(array(
		new Asset_CSS('css/screen.css'),
		new Asset_CSS('css/print.css', array('media' => 'print')),
		new Asset_Less('css/testing.less'),
	));
	$stylesheets->add_filter(new Asset_Filter_Less);
	$stylesheets->add_filter(new Asset_Filter_Concat);
	echo $stylesheets;
	
Using the default Kohana config reader you could have described this
like so:

	return array(
		'groups' => array(
			'stylesheets' => array(
				array('css', 'css/screen.css'),
				array('css', 'css/print.css', array('media' => 'print')),
				array('less', 'css/testing.less'),
			),
		),
		'filters' => array(
			'stylesheets' => array(array('Less'), array('Concat')),
		),
	);
	
And then only had to do this in your PHP:

	echo new Asset_Group('stylesheets');
	
So really it's up to you.

## Asset Types

These Asset types will be shipped when complete:

 - JavaScript
 - Cascading StyleSheets
 - Less

## Asset Filters

This might be slightly over-zealous:

 - Concat (for joining assets together)
 - Less (for compiling Less into CSS)
 - JSMin (for minifying JS)
 
## Asset Caching

For use in production environement you can now use `Asset_Cache`. It
is basically a handy decorator for caching your asset compilations.

	echo new Asset_Cache(new Asset_Group('javascripts'));
	
	// Or 
	echo Asset_Cache::group('javascripts');
 
## Warning

This module is still in development currently.
 
## Author

Luke Morton

## License

MIT