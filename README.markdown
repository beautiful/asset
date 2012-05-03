# Beautiful Asset

Asset management for Kohana.

## The idea

The idea involves `Asset_Group`s and `Asset`s. An `Asset`
represents one file that needs to be included in an output. I
left that previous sentence fairly abstract on purpose. An
`Asset` is simply a resource to be grouped into an
`Asset_Group`.

## Usage

You can use this module with or without configuration. Things
do get a lot neater and make for easier changes if you do use
configuration however do what you will.

### Describing an Asset

The first class we'll introduce is `Asset`. An `Asset`
represents a single file (or asset funnily enough) and can be
used to individually render such files.

Take an example of including a CSS file on your web page, you
would simply echo the following:

``` php
echo new Asset_CSS('css/screen.css');
```

`Asset::__construct()` takes a second argument, an array of
additional configuration.

``` php
echo new Asset_CSS('css/screen.css', array(
	'attributes' => array(
	),
	'cache_buster' => TRUE,
));

``` php
<?php
echo new Asset_Group(array(
	new Asset_CSS('css/screen.css'),
	new Asset_CSS('css/print.css', array('media' => 'print')),
	new Asset_Less('css/testing.less'),
));
```
	
Using the default Kohana config reader you could have described this
like so:

``` php
<?php
return array(
	'groups' => array(
		'stylesheets' => array(
			array('CSS', 'css/screen.css'),
			array('CSS', 'css/print.css', array(
				'attributes' => array(
					'media' => 'print',
				),
			),
			array('LESS', 'css/testing.less'),
		),
	),
);
```
	
And then only had to do this in your PHP:

``` php
<?php
echo new Asset_Group('stylesheets');
```
	
So really it's up to you.

## Asset Types

These Asset types will be shipped when complete:

 - JavaScript
 - Cascading StyleSheets
 - Less
 - Coffee
 
## Asset Caching

For use in production environement you can now use `Asset_Cache`. It
is basically a handy decorator for caching your asset compilations.

``` php
<?php
echo new Asset_Cache(new Asset_Group('javascripts'));

// Or 
echo Asset_Cache::group('javascripts');
```
 
## Warning

This module is still in development currently.
 
## Author

Luke Morton

## License

MIT