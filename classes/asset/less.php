<?php defined('SYSPATH') or die('No direct script access.');

class Asset_LESS extends Asset {

	protected $_type = 'less';
	
	public function html()
	{
		$location = $this->location();
		
		if (strpos($location, '://') === FALSE)
		{
			// Add the base URL
			$location = URL::base().$location;
		}
		
		$attributes = $this->attributes();

		// Set the stylesheet link
		$attributes['href'] = $location;

		// Set the stylesheet rel
		$attributes['rel'] = 'stylesheet/less';

		// Set the stylesheet type
		$attributes['type'] = 'text/css';

		return '<link'.HTML::attributes($attributes).' />';
	}

}