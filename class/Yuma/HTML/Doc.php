<?php
declare(strict_types=1);
namespace Yuma\HTML;
use function \Yuma\do_mbstr;

class Doc //extends Singleton
{
//	protected static $instance;
	private $lang;
	private $class;
//	private $_array = array();
	private $params = array();

		public function __construct(array $params=null)//string $lang=null, string $class=null)
		{
			global $docHTML;

			if (null == $docHTML || !is_array($docHTML) || empty($docHTML)) {
				$docHTML = array();
			}
			if (isset($params) && !empty($params)) {
				$this->params = $params;
			}

			if (isset($params['lang']) && !empty($params['lang'])) {
				$this->setLang($params['lang']);
			}
			if (isset($params['class']) && !empty($params['class'])) {
				$this->setClass($params['class']);
			}
		}
	
	/**
	 * Sets the html language code
	 */
	public function setLang(string $str) {
		global $docHTML;
		$docHTML['lang'] = $str;
	}

	/**
	 * Returns the html language code
	 */
	public function getLang() {
		global $docHTML;
		return isset($docHTML['lang']) ? $docHTML['lang'] : '';
	}

	/**
	 * Sets the html class string
	 */
	public function setClass(string $str) {
		global $docHTML;
		$docHTML['class'] = $str;
	}

	/**
	 * Return the html class string
	 */
	public function getClass() {
		global $docHTML;
		return isset($docHTML['class']) ? $docHTML['class'] : '';
	}

	/**
	 * Appends a class string to the whole html class string
	 */
	public function add2Class(string $str) {
		global $docHTML;
		if (isset($docHTML['class'])) {
			$docHTML .= $str;
		} else {
			$this->setClass($str);
		}
	}

	/**
	 * Removes a class string from the whole html class string
	 */
	public function removeClass(string $str) {
		global $docHTML;
		if (isset($docHTML['class'])) {
			$docHTML['class'] = trim(str_replace('  ', ' ', str_replace($str, '', $this->class)));
		}
	}

	/**
	 * Sets the DOCTYPE for this document
	 *
	 * unlimited number of arguments
	 *
	 * example:
	 * self::set( 'lang="en"', 'class="myname"' );  ==>
	<html lang="en" class="myname" >
	 */
	public function setType() {
		global $docHTML;
		$numargs = func_num_args();
		$arg_list = func_get_args();
	//	if ($numargs < 1) {
	//		return __CLASS__. '::set - not enough arguments';
	//	}
		$out = '<!DOCTYPE';
		for ($i = 0; $i < $numargs; $i++) {
			if (!empty($arg_list[$i])) {
				//$out .= ' '. func_get_args($i);
				$out .= ' '. $arg_list[$i];
			}
		}
		$out .= '>';
		$flag = false;
		if (isset($docHTML['doc'])) {
			//return __CLASS__. '::set - HTML opening tag already set';
//			return true;
			$flag = true;
		}
		$docHTML['doc'] = $out;
//		return false;
		return $flag;
	}

	/**
	 * Returns the conditional html string for IE
	 */
	public function putIE() {
		global $docHTML;
		$out = '<!--[if lt IE 7 ]> <html';
		if (isset($this->lang) && !empty($this->lang)) {
			$out .= " lang=\"{$this->lang}\"";
		}
		$out .= ' class="';
		if (isset($this->class) && !empty($this->class)) {
			$out .= $this->class. ' ';
		}
	   	$out .= 'ie6 ielt8"> <![endif]--> ';
		$out .= '<!--[if IE 7 ]> <html';	// begin with a carriage return
		if (isset($this->lang) && !empty($this->lang)) {
			$out .= " lang=\"{$this->lang}\"";
		}
		$out .= ' class="';
		if (isset($this->class) && !empty($this->class)) {
			$out .= $this->class. ' ';
		}
		$out .= 'ie7 ielt8"> <![endif]--> ';
		$out .= '<!--[if IE 8 ]> <html';
		if (isset($this->lang) && !empty($this->lang)) {
			$out .= " lang=\"{$this->lang}\"";
		}
		$out .= ' class="';
		if (isset($this->class) && !empty($this->class)) {
			$out .= $this->class. ' ';
		}
	   	$out .= 'ie8"> <![endif]--> ';
		$out .= '<!--[if (gte IE 9)|!(IE)]><!--> <html';
		if (isset($this->lang) && !empty($this->lang)) {
			$out .= " lang=\"{$this->lang}\"";
		}
		if (isset($this->class) && !empty($this->class)) {
			$out .= " class=\"{$this->class}\"";
		}
	   	$out .= '> <![endif]--> ';
		$docHTML['cond'] = $out;
		return false;
	}

	/**
	 * Sets the attribute(s) for the opening HTML tag
	 *
	 * unlimited number of arguments
	 *
	 * example:
	 * self::set( 'lang="en"', 'class="myname"' );  ==>
	<html lang="en" class="myname" >
	 */
	public function inHTML() {
		global $docHTML;
		$numargs = func_num_args();
		$arg_list = func_get_args();
		$out = '<html';
		for ($i = 0; $i < $numargs; $i++) {
			if (!empty($arg_list[$i])) {
				if (do_mbstr('substr', trim($arg_list[$i]), 0, 4) === 'lang=') {
					$this->lang = do_mbstr('substr', trim($arg_list[$i]), 5);
					$out .= " {$this->lang}";
				} elseif (do_mbstr('substr', trim($arg_list[$i]), 0, 5) === 'class=') {
					$this->class = do_mbstr('substr', trim($arg_list[$i]), 6);
					$out .= " {$this->class}";
				} else {
					$out .= ' '. $arg_list[$i];
				}
			}
		}
		$out .= '>';
		$flag = false;
		if (isset($docHTML['html'])) {
			$flag = true;
		}
		$docHTML['html'] = $out;
		if (!isset($docHTML['doc'])) {
			$this->setType('html');
		}
		return $flag;
	}
/*	public static function inHTML() {
		$numargs = func_num_args();
		$arg_list = func_get_args();
		//if ($numargs < 1) {
		//	return __CLASS__. '::inHTML - not enough arguments';
		//}
		$out = '<html';
		for ($i = 0; $i < $numargs; $i++) {
			//if (!empty(func_get_args($i))) {
			if (!empty($arg_list[$i])) {
				//$out .= ' '. func_get_args($i);
				$out .= ' '. $arg_list[$i];
			}
		}
		$out .= '>';
		$flag = false;
		if (isset($this->_array['html'])) {
			//return __CLASS__. '::set - HTML opening tag already set';
//			return true;
			$flag = true;
		}
		$this->_array['html'] = $out;
		if (!isset($this->_array['doc'])) {
			$this->type('html');
		}
//		return false;
		return $flag;
	}
*/
	public function getAll() {
		global $docHTML;
		if (!isset($docHTML) || empty($docHTML) || !isset($docHTML['doc']) || empty($docHTML['doc'])) {
			$this->setType('html');
		}
		if (!isset($docHTML['cond']) || empty($docHTML['cond'])) {
			$this->putIE();
		}
		if (!isset($docHTML['html']) || empty($docHTML['html'])) {
			//$this->inHTML('lang="en"');
			$this->inHTML();
		}
		return $docHTML;
	}

}
