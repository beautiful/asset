<?php defined('SYSPATH') or die('No direct script access.');

class Asset_JS extends Asset {

	protected $_type = 'js';
	
	public function html()
	{
		return HTML::script($this->location(), $this->attributes());
	}

}