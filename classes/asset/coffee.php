<?php defined('SYSPATH') or die('No direct script access.');

class Asset_Coffee extends Asset {

	protected $_type = 'coffee';
	
	public function html()
	{
		$attributes = $this->attributes();

		// Set the script link
		$attributes['src'] = $this->location();

		// Set the script type
		$attributes['type'] = 'text/coffeescript';

		return '<script'.HTML::attributes($attributes).'></script>';
	}

}