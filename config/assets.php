<?php defined('SYSPATH') or die('No direct script access.');

return array(
	/**
	 * Example Asset configuration:
	 *
	'groups' => array(
		// Group name => Assets
		'js' => array(
			// array(Type, Location),
			array('js', 'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'),
			array('js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js'),
			array('coffee', 'coffee/app.coffee', array('defer' => 'defer')),
		),
	),
	
	'filters' => array(
		// Group name => array(Filter, Filter),
		'js' => array('Coffee', 'JsHint', 'Concat'),
	),
	 */
	 
	'filter_options' => array(
		'coffeecmd' => '/usr/local/coffee :src 2>&1',
		'jshintcmd' => 'java -jar :path/js.jar :path/env/rhino.js :src'
	),
);