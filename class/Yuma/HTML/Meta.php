<?php

declare(strict_types=1);
namespace Yuma\HTML;

class Meta //extends Singleton
{
//	protected static $instance;
	//private static $_array = array();
//	private $_array = array();

	public function __construct(array $params=null)
	{
		global $metaHTML;
		if (!isset($metaHTML) || empty($metaHTML)) {
			$metaHTML = array();
		}
		if (isset($params) && !empty($params)) {
			$this->add($params);
		}
	}

	/**
	 * Adds a meta string to the class array
	 *
	 * $name string	Associated name for this meta tag
	 * $type string	The first atrribute - maybe 'name', 'http-equiv', or a string value
	 * $content string	The value inserted into the content attribute
	 *
	 * example:
	 * add( array( 'name' => 'meta_vp', 'type' => 'name', 'type_value' => "viewport", 'content' => "width=device-width, initial-scale=1" ) ); -- OR --
	 * add( 'meta_vp', 'name', "viewport",  "width=device-width, initial-scale=1" ); ==>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	 */
	public function add() {
		global $metaHTML;
//		printf ('called add with %d parameters', func_num_args());
		if (func_num_args() > 1 || !is_array(func_get_arg(0)))
		{
			$param = array();
			$param['name'] = func_get_arg(0);
			if (func_num_args() > 1)
			{
				$param['type'] = func_get_arg(1);
			}
			if (func_num_args() > 2)
			{
				$param['type_value'] = func_get_arg(2);
			}
			if (func_num_args() > 3)
			{
				$param['content'] = func_get_arg(3);
			}
//			echo print_r($param, true);
		} else {
			$param = func_get_arg(3);
		}
		$out = '<meta';
//		if(isset($name) && !empty($name)) {
//			$out .= " {$name}";
//		}
		if(isset($param['type']) && !empty($param['type'])) {
			$out .= " {$param['type']}";
		}
		if(isset($param['type_value']) && !empty($param['type_value'])) {
			$out .= "=\"{$param['type_value']}\"";
		}
		if(isset($param['content']) && !empty($param['content'])) {
			$out .= " content=\"{$param['content']}\"";
		}
		$out .= '>';
		//if (isset(self::$_array[$param['name']])) {
		$name = $param['name'];
/*		if ($this->has($name)) {
			$log = "Meta::add() tag '{$name}' already exists";
			(is_callable(array('Error', 'log'))) &&
				Error::log( $log ) ||
				error_log( $log );
//			return "Meta for '{$name}' already exists";
		}*/
		$metaHTML[$name] = $out;
		return false;
	}

	/**
	 * Returns true if 'name' exists or false if name no not exist
	 */
	public function has( string $name )
	{
		global $metaHTML;
		return isset( $metaHTML[$name] );
	}

	/**
	 * Add a comment to the meta of the head section.
	 * Accepts either one or two parameters:
	 * $meta->comment( [<comment name>,] <comment string> );
	 */
	public function comment()
	{
		$pre = 'Meta::comment() ';
		global $metaHTML;
		if (func_num_args() > 1 || !is_array(func_get_arg(0)))
		{
			$param = array();
			$param['name'] = func_get_arg(0);	// store as first parameter
			if (func_num_args() > 1)
			{
				$param['str'] = func_get_arg(1);	// store as second parameter, if exists
			}
			if (!isset($param['str']))
			{
				preg_replace('/\b\w+\b/i', $param['name'], $result);	// get first word of the only parameter
				$param['name'] = $result;//strtok($param['name'], ' ');	// store name as first word
				$param['str'] = func_get_arg(0);	// store str as the whole of the only parameter
			}
		} else {
			$param = func_num_args();
		}
		$name = $param['name'];
		try {
			$metaHTML[$name] = '<!-- '. $param['str']. ' -->';
			return false;
		} catch( Exception $ex ) {
			$msg = 'Meta::comment() cannot add : '. $ex->getMessage(). ' ('. __CLASS__. '::'. __FUNCTION__. ' in '. __FILE__. ':'. __LINE__. ')'. PHP_EOL;
					try {
						$logger = new Logger;
						$logger->log($pre. $msg);
					} catch (Exception $e) {
						error_log ($pre. $msg);
					}
/*			(is_callable(array('Error', 'log'))) &&
				Error::log( $msg ) ||
				error_log( $msg );*/
			die( $msg );
		}
	}

	/**
	 * Returns an array of all the currently set meta tags
	 */
	public function getAll() {
		global $metaHTML;
		return $metaHTML;
	}
/*
	public static function has( string $name )
	{
		return isset(self::$_array[$name]);
	}

	public static function comment( string $name, string $str )
	{
		try {
			self::$_array[$name] = '<!-- '. $str. ' -->';
			return false;
		} catch(Exception $ex) {
			$msg = 'Cannot add comment to HEAD meta section : '. $ex->getMessage();
			(is_callable(array('Error', 'log'))) &&
				Error::log( $msg ) ||
				error_log( $msg );
			die($msg);
		}
	}

	public static function getAll() {
		return self::$_array;
	}
*/
}
