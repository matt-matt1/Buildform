<?php

declare(strict_types=1);
namespace Yuma\HTML;

//final class Styles extends Depends {
use Yuma\Logger;

class Styles //extends Depends {
{
	//private $styles = array();
//	protected static $instance;

	//protected function __construct()
	public function __construct()
	//private function __construct()
	{
		global $stylesHTML;
		/* Initialisaes the class array, if absent or is blank */
		//if (!isset($this->styles) || empty($this->styles)) {
		if (!isset($stylesHTML) || empty($stylesHTML)) {
			$stylesHTML = array();
//			$this->styles = array();
		}
	}

	/**
	 * Sets an external style(sheet) file to be queued as an HTML style
	 * Formatted as HTML and placed into the class array
	 * (and when it's time for the web page to be drawn, maybe if a sibling method is called, it will be included the HTML
	 *
	 * @param array $params	an array containing there parameters:
	 *		'name' => '',
	 *		'href' => '',
	 *		'version' => '',
	 *		'media' => '',/ *string $media='all'* /
	 *		'preload' => false,
	 *		'defer' => false,
	 *		'cross_domain' => '',
	 *		'show_comment' => false,
	 *		'data-minify' => true,
	 *		'id' => '',
	 *		'integrity' => "",
	 *		'cross_origin' => "",
	 */
	public function enqueue( array $params )
	{
		global $stylesHTML;
		$pre = 'Styles::enqueue(): ';
		if (!isset($params['href']) || empty($params['href'])) {
			return "Invalid or missing HREF";
		}
		$out = '';
		//$out = '<!--'. implode(',', $params). '--> ';
		if (isset($params['show_comment']) && $params['show_comment']) {
			$out .= '<!-- style "' . filter_var(str_replace('.', '_', $params['name'])) . '" -->';
		}
		//$out .= '<link href="'. filter_var( $params['href'] );
		$out .= '<link href="'. $params['href'];
		if (defined('LOG_STYLES') && \LOG_STYLES) {
			$log = do_mbstr('str_replace', BASE, '', filter_var( $params['href'] ));
            try {
                $logger = new Logger;
                $logger->log($pre. $log);
            } catch (Exception $e) {
                error_log ('error_log: '. $pre. $log);
            }
		}
		if (isset($params['version']) && !empty($params['version'])) {
			if ($params['version'] == 'time') {
				$out .= '?'. filemtime( $params['href'] );
			} else {
				$out .= '?' . filter_var($params['version']);
			}
		}
		$out .= '"';
		if (isset($params['id']) && !empty($params['id'])) {
			$out .= ' id="' . filter_var($params['id']) . '"';
		}
		if (isset($params['media']) && !empty($params['media'])) {
			$out .= ' media="' . filter_var($params['media']) . '"';
		}
		if (isset($params['integrity']) && !empty($params['integrity'])) {
			$out .= ' integrity="' . filter_var($params['integrity']) . '"';
		}
		if (isset($params['defer']) && $params['defer']) {
			$out .= ' defer';
		}
		if (isset($params['href']) /*&& is_readable($params['href'])*/)
		{
			$ext = pathinfo($params['href'], PATHINFO_EXTENSION);
			if (isset($params['preload']) && $ext == "css") {
				$out .= ' as="style"';
			}
		}
		if (isset($params['rel']) && $params['rel'] != 'stylesheet' &&
			isset($params['href']) && $ext != 'css') {
			//isset($params['href']) && strpos($params['href'], '.css') === false) {
			$out .= ' rel="'. filter_var($params['rel']). '"';
		}
		if (isset($params['preload']) && !empty($params['preload'])) {
			$out .= ' rel="preload"';
		}
		if ( $ext == 'css' && (!isset($params['preload']) || empty($params['preload'])) )
		{
			$out .= ' type="text/css"';
			$out .= ' rel="stylesheet"';
		}
		if (isset($params['data-minify']) && !empty($params['data-minify'])) {
			$out .= ' data-minify="1"';
		}
		if (isset($params['crossorigin']) && !empty($params['crossorigin'])) {
			$out .= ' crossorigin="' . filter_var($params['crossorigin']) . '"';
		}
		if (isset($params['cross_domain']) && !empty($params['cross_domain'])) {
			$out .= ' cross_domain="' . filter_var($params['cross_domain']) . '"';
		}
		$out .= '>';
		//echo "<script>console.log('Styles::enqueue() \$out\[\'{$name}\'\] = {$out}.');</script>"; OK
//		if (isset($this->styles[$params['name']])) {
//			$log = "!!Style '{$this->styles[$params['name']]}' already exists!!";
		if (isset($stylesHTML[$params['name']])) {
			$log = "!!Style '{$stylesHTML[$params['name']]}' already exists!!";
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*			(is_callable(array('Error', 'log'))) &&
				Error::log( $log ) ||
				error_log( $log );*/
			//throw new Exception("!!Style '$name' already exists!!");
		//	return true;
		}
//		$this->styles[$params['name']] = $out;
		$stylesHTML[$params['name']] = $out;
		return false;
	}

	public function comment( string $name, string $str )
	{
		global $stylesHTML;
		$pre = 'Styles::comment(): ';
		try {
			//$this->scripts[$name] = '<!-- '. $str. ' -->';
			$stylesHTML[$name] = '<!-- '. $str. ' -->';
			return false;
		} catch(Exception $ex) {
			$msg = 'Cannot add comment to HEAD style section : '. $ex->getMessage();
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*			(is_callable(array('Error', 'log'))) &&
				Error::log( $msg ) ||
				error_log( $msg );*/
			die($msg);
		}
	}

	/**
	 * Returns the class array of all the HTML styles
	 */
	public function getAll() {
		global $stylesHTML;
		//return $this->styles;
		//return array_reverse($this->styles);
		return array_reverse($stylesHTML);
	}

}
