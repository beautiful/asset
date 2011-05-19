<?php defined('SYSPATH') or die('No direct script access.');

class Asset_CSS extends Asset {

	protected $_type = 'css';
	
	public function html()
	{
		return HTML::style($this->location(), $this->attributes());
	}

}