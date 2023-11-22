<?php
declare(strict_types=1);
namespace Yuma\HTML;
use Yuma\Logger;
use function \Yuma\do_mbstr;

final class Scripts //extends Depends
{
//	private $queue;
//	protected static $instance;

	//protected function __construct()
	public function __construct()
	{
		global $scriptsHTML;
		if (!isset($scriptsHTML) || empty($scriptsHTML)) {
			$scriptsHTML = array(
				'head' => array(),
				'foot' => array(),
			);
/*			$this->queue = array(
				'head' => array(),
				'foot' => array()
			);*/
		}
	}

	/**
	 * Sets an external script file to be queued as an HTML script
	 * Formatted as HTML and placed into the class array
	 * (and when it's time for the web page to be drawn, maybe if a sibling method is called, it will be included the HTML
	 *
	 * @param array $params	an array containing there parameters:
	 *	string name			text name (whatever you want to call this script) - required
	 *	string filename		the absolute or relative filename (including the path) for this script - required
	 *	string version		appended to the filename (preceeded by a ?) - optional
	 *	bool put_in_header	whether this script should be loaded into the head section - optional
	 *	bool is_async		whether this script should be marked as asynchronis - optional
	 *	string cross_domain	the type of cross domain method for this script - optional
	 *	array requires		each string of the script name to be before it - optional
	 *
	 * @return false (if no errors) - The final HTML string is added to the class array
	 */
	public function enqueue( array $params )
	{
		global $scriptsHTML;
		$pre = 'Scripts::enqueue(): ';
		$defaults = Array(
			'name' => '',
			'src' => '',
			'version' => '',
			'put_in_header' => false,
			'is_async' => false,
			'cross_domain' => '',
			'integrity' => '',
			'referrerpolicy' => '',//"no-referrer"
			'show_comment' => false,
			'requires' => array()
		);
		$use = array_merge( $defaults, (array)$params );
		unset($defaults);
		$out = '';
		if (isset($use['show_comment']) && $use['show_comment']) {
			$out .= '<!-- script "' . filter_var($use['name']) . '" -->';
		}
		$out .= '<script src="'. filter_var( $use['src'] );
		if (defined('LOG_SCRIPTS') && LOG_SCRIPTS) {
			$log = do_mbstr('str_replace', BASE, '', filter_var( $use['src'] ));
            try {
                $logger = new Logger;
                $logger->log($pre. $log);
            } catch (Exception $e) {
                error_log ('error_log: '. $pre. $log);
            }
		}
		if ($use['version']) {
			if ($use['version'] === 'file')
			{
				$use['version'] = filemtime(str_replace(BASE, '', filter_var( $use['src'] )));
			}
			$out .= '?' . filter_var($use['version']);
		}
		$out .= '"';
		$ext = pathinfo($use['src'], PATHINFO_EXTENSION);
		if ($ext == 'js') {
			$out .= ' type="text/javascript"';
		}
		if ($use['is_async']) {
			$out .= ' async';
		}
		if (isset($use['integrity']) && !empty($use['integrity'])) {
			$out .= ' integrity="' . filter_var($use['integrity']) . '"';
		}
		if (isset($use['cross_domain']) && !empty($use['cross_domain'])) {
			$out .= ' cross_domain="' . filter_var($use['cross_domain']) . '"';
		}
		$out .= '></script>';
		if (isset($scriptsHTML[$use['name']])) {
			throw newException("Script '{$use['name']}' already exists");
		}
//		$array[$use['name']] = $out;
		if( $use['put_in_header'] ) {
			if (isset($scriptsHTML['head'][$use['name']])) {
				$log = "'{$use['name']}' already exists in the head section";
				$trace = debug_backtrace();
				$log .= ' (' . $trace[1]['file'] .
					' #' . $trace[1]['line']. ')';
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*				(is_callable(array('Error', 'log'))) &&
					Error::log( $pre. $log ) ||
					error_log( $pre. $log );*/
		//		return true;
				//throw new Exception("Script '{$use['name']}' already exists in the head section");
//				return "Script '{$use['name']}' already exists in the head section";
			}
//			if (!isset($use['requires'])) {
				$scriptsHTML['head'][$use['name']] = $out;
//			} else {
//				array_unshift($this->queue['head'], $array);
//			}
		} else {
			if (isset($scriptsHTML['foot'][$use['name']])) {
				$log = "'{$use['name']}' already exists at the end of the body section";
				$trace = debug_backtrace();
				$log .= ' (' . $trace[1]['file'] .
					' #' . $trace[1]['line']. ')';
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*				(is_callable(array('Error', 'log'))) &&
					Error::log( $pre. $log ) ||
					error_log( $pre. $log );*/
		//		return true;
				//throw new Exception("Script '{$use['name']}' already exists in the foot section");
//				return "Script '{$use['name']}' already exists at the end of the body section";
			}
//			if (!isset($use['requires'])) {
				$scriptsHTML['foot'][$use['name']] = $out;
//			} else {
//				array_unshift($this->queue['foot'], $array);
//			}
		}
		return false;
	}

	/**
	 * Sets an line script string to be queued
	 * Formatted as HTML and placed into the class array
	 * (and when it's time for the web page to be drawn, maybe if a sibling method is called, it will be included the HTML
	 *
	 * @param array $params	an array containing there parameters:
	 *	string $name			text name (whatever you want to call this script) - required
	 *	string $filename		the absolute or relative filename (including the path) for this script - required
	 *	string $version			appended to the filename (preceeded by a ?) - optional
	 *	bool $put_in_header		whether this script should be loaded into the head section - optional
	 *	bool $is_async			whether this script should be marked as asynchronis - optional
	 *	string $cross_domain	the type of cross domain method for this script - optional
	 *
	 * @return false (if no errors) - The final HTML string is added to the class array
	 */
	public function enqueueInline( array $params )
	{
		global $scriptsHTML;
		$pre = 'Scripts::enqueueInline(): ';
		$defaults = Array(
			'name' => '',
			'code' => '',
/*			'version' => '',*/
			'put_in_header' => false,
			'nonce' => '',
/*			'is_async' => false,
			'cross_domain' => '',
			'integrity' => '',
			'referrerpolicy' => '',//"no-referrer"*/
			'show_comment' => false
		);
		$use = array_merge( $defaults, (array)$params );
		unset($defaults);
		$out = '';
		if (isset($use['show_comment']) && $use['show_comment']) {
			$out .= '<!-- script "' . filter_var($use['name']) . '" -->';
		}
		$out .= '<script';
/*		if ($use['is_async']) {
			$out .= ' async';
		}*/
		if (isset($use['nonce']) && !empty($use['nonce'])) {
			$out .= ' nonce="' . filter_var($use['nonce']) . '"';
		}
		if (isset($use['type']) && !empty($use['type'])) {
			$out .= ' type="' . filter_var($use['type']) . '"';
		}
		$out .= '>'. "\n";
		$out .= filter_var($use['code']). "\n";
		$out .= "\t". '</script>';
/*		if (isset($this->scripts[$use['name']])) {
			throw newException("Script '{$use['name']}' already exists");
		}*/
//		$array[$use['name']] = $out;
		if( $use['put_in_header'] ) {
			if (isset($scriptsHTML['head'][$use['name']])) {
				//throw new Exception("Script '{$use['name']}' already exists in the head section");
				//return "Script '{$use['name']}' already exists in the head section";
				$log = "Script '{$use['name']}' already exists in the head section";
				$trace = debug_backtrace();
				$log .= ' (' . $trace[1]['file'] .
					' #' . $trace[1]['line']. ')';
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*				(is_callable(array('Error', 'log'))) &&
					Error::log( $pre. $log ) ||
					error_log( $pre. $log );*/
			}
			$scriptsHTML['head'][$use['name']] = $out;
			//array_unshift($this->queue['head'], $array);
		} else {
			if (isset($scriptsHTML['foot'][$use['name']])) {
				//throw new Exception("Script '{$use['name']}' already exists in the foot section");
				//return "Script '{$use['name']}' already exists in the head section";
				$log = "Script '{$use['name']}' already exists in the head section";
				$trace = debug_backtrace();
				$log .= ' (' . $trace[1]['file'] .
					' #' . $trace[1]['line']. ')';
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*				(is_callable(array('Error', 'log'))) &&
					Error::log( $pre. $log ) ||
					error_log( $pre. $log );*/
			}
			$scriptsHTML['foot'][$use['name']] = $out;
			//array_unsift($this->queue['foot'], $array);
		}
		return false;
	}

	public function commentHead( string $name, string $str )
	{
		global $scriptsHTML;
		$pre = 'Scripts::commentHead() ';
		try {
			$scriptsHTML['head'][$name] = '<!-- '. $str. ' -->';
			return false;
		} catch(Exception $ex) {
			$msg = 'Cannot add comment to HEAD scripts section : '. $ex->getMessage();
					try {
						$logger = new Logger;
						$logger->log($pre. $msg);
					} catch (Exception $e) {
						error_log ($pre. $msg);
					}
/*			(is_callable(array('Error', 'log'))) &&
				Error::log( $msg ) ||
				error_log( $msg );*/
			die($msg);
		}
	}

	public function commentFoot( string $name, string $str )
	{
		global $scriptsHTML;
		$pre = 'Scripts::commentFoot() ';
		try {
			$scriptsHTML['foot'][$name] = '<!-- '. $str. ' -->';
			return false;
		} catch(Exception $ex) {
			$msg = 'Cannot add comment to end of BODY scripts section : '. $ex->getMessage();
					try {
						$logger = new Logger;
						$logger->log($pre. $msg);
					} catch (Exception $e) {
						error_log ($pre. $msg);
					}
/*			(is_callable(array('Error', 'log'))) &&
				Error::log( $msg ) ||
				error_log( $msg );*/
			die($msg);
		}
	}

/*
	private function dependencies($script) {
		if ($script['deps']) {
			if (isset($script['put_in_header']) && $script['put_in_header']) {
				$section = $this->queue['head'];
			} else {
				$section = $this->queue['foot'];
			}
            return array_map(array($this, 'dependencies'), array_intersect_key($section, array_flip($script['requires'])));
        }
	}
 */
	private function _unset ($key, &$deps, &$out, $head) {
/*		if (isset($head) && $head) {//isset($script['put_in_header']) && $script['put_in_header']) {
			$section = $this->queue['head'];
		} else {
			$section = $this->queue['foot'];
		}
		$out[$key] = $section[$key];*/
//		$out[$key] = (isset($head) && $head) ? 
//			$this->queue['head'][$key] : 
//			$this->queue['foot'][$key];
        unset ($deps[$key]);
    }

    private function flattern (&$deps, &$out = array(), $head=null) {
        foreach ($deps as $key => $value) {
            empty ($value) ? $this->_unset ($key, $deps, $out, $head) : $this->flattern ($deps[$key], $out, $head);
        }
    }
/* */
	private function processDependencies($script)
	{
		global $scriptsHTML;
		if (isset($script['requires']) && is_array($script['requires'])) {
			if (isset($script['put_in_header']) && $script['put_in_header']) {
				$section = $scriptsHTML['head'];
			} else {
				$section = $scriptsHTML['foot'];
			}
			$deps = array_map(array($this, 'processDependencies'), array_intersect_key($section, array_flip($script['requires'])));
			error_log('processDependencies: '. print_r($deps, true). '.');
			return $deps;
//            return array_map(array($this, 'processDependencies'), array_intersect_key($section, array_flip($script['requires'])));
        }
	}

	/**
	 * Returns the head part of the class array, to be placed into the HTML head section
	 */
	public function getInHead()
	{
		global $scriptsHTML;
/*		$new = array();
		if (count($this->queue['head']) < 2) {
			$new = $this->queue['head'];
		} else {
			$i = 0;
			foreach ($this->queue['head'] as $script) {
				if ($i) {
					if (isset($this->queue['head']['requires']) && is_array($this->queue['head']['requires'])) {
						$new = array_map($this->queue['head']['requires']);
					}*/
/*					$deps = array_map(array($this, 'processDependencies'), $this->queue['head']);
					while ($deps)
						$this->flattern($deps, $js, isset($this->queue['head']['put_in_header']) && $this->queue['head']['put_in_header']);
					return $js;*/
/*					if (in_array($this->queue['head'], $this->queue['head']['requires'])) {
						$new[] = $script;
					} else {
						throw new Exception (sprintf( t('_No dependancy for %s'), $this->queue['head']['name']));
					}
				}
				++$i;
			}
		}*/
//		$this->array_order($this->queue['head']);
		//return array_reverse($this->scripts['head']);
		return $scriptsHTML['head'];
//		return $new;
		/*foreach ($this->scripts['head'] as $s)
		{
			foreach ($s as $k => $v)
			{
				if (isset($k['requires'] && !empty($k['requires']))
				{
					//...
				}
			}
		}
		return*/
	}

	/**
	 * Returns the foot part of the class array, to be placed into the HTML body section
	 */
	public function getInFoot()
	{
		global $scriptsHTML;
//		$this->array_order($this->queue['head']);
		//return array_reverse($this->scripts['foot']);
		return $scriptsHTML['foot'];
		/*foreach ($this->scripts['foot'] as $s)
		{
			foreach ($s as $k => $v)
			{
				if (isset($k['requires'] && !empty($k['requires']))
				{
					//...
				}
			}
		}
		return*/
	}

	public function array_order($array)
	{
		//$found = false;
		foreach ($array as $k => $scr) {	// eg. 0 => array(...)
			if (isset($src['requires'])) {
//				$name = $scr['name'];
//				foreach ($src as $k => $v) {	// eg. 'name' => 'xyz'
//					if ($k == 'name')
				//$found = true;
				foreach ($scr['requires'] as $req) {
					if (!isset($array[$req])) {
						error_log('Scripts::array_order contains no "'. $req. '"');
						return true;
					} else {
						$move = $array[$req];
						unset($array[$req]);
						array_unshift($array, $move);
//						array_reorder($array, 
					}
				}
/*				array_splice(
					$array,
					$newIndex,
					count($array),
					array_merge(
						array_splice($array, $oldIndex, 1),
						array_slice($array, $newIndex, count($array))
					)
				);*/
			}
		}
		//return $array;
		return false;
	}

}
/**
 * Function that gets the current class instance and calls the class method,
 *	hides the class structures - to increase readability
 */
function enScript( array $params )
{
	$spt = new Scripts();
	$spt->enqueue( $params );
}
