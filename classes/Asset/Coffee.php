<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Coffee extends Asset {

	protected $_type = 'coffee';
	
	public function html()
	{
		$location = $this->location();
		
		if (strpos($location, '://') === FALSE)
		{
			// Add the base URL
			$location = URL::base().$location;
		}
		
		$attributes = $this->attributes();

		// Set the script src
		$attributes['src'] = $location;

		// Set the stylesheet type
		$attributes['type'] = 'text/coffeescript';

		return '<script'.HTML::attributes($attributes).'></script>';
	}

}