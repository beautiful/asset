<?php defined('SYSPATH') or die('No direct script access.');

class Asset_LESS extends Asset {

	protected $_type = 'less';
	
	public function html()
	{
		$attributes = $this->attributes();

		// Set the stylesheet link
		$attributes['href'] = $this->location();

		// Set the stylesheet rel
		$attributes['rel'] = 'stylesheet/less';

		// Set the stylesheet type
		$attributes['type'] = 'text/css';

		return '<link'.HTML::attributes($attributes).' />';
	}

}