<?php defined('SYSPATH') or die('No direct script access.');

return array(

	/**
	 * Example Asset configuration:
	 *
	'groups' => array(
		'stylesheets' => array(
			array('css', 'css/screen.css'),
			array('css', 'css/print.css', array(
					'media' => 'print',
				)),
		),
		'javascripts' => array(
			array('js', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js'),
			array('js', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js'),
			array('js', 'js/app.js'),
		),
	),
	
	'filters' => array(
		'stylesheets' => array('Concat'),
		'javascripts' => array('Concat', 'JsMinPlus'),
	),
	 */
	 
);