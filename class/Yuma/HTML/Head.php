<?php
declare(strict_types=1);

namespace Yuma\HTML;
use Yuma\Logger;
use function Yuma\glob_recursive;
use function Yuma\do_mbstr;
use function Yuma\t;
use function Yuma\showRequest;
use function Yuma\do_action;
use function Yuma\has_hook;
use function Yuma\getServerValue;

/**
 * @property-read (all)
 */
/*abstract*/ class Head //extends Singleton
{
//	static protected $instance;
	protected $children = array();
	private $out = false;
	private $loaded = false;
	private $methods = [];
	private $data = array();
//	private static $data = array();
	private $done_hooks = false;

	/*abstract function prepare();*/

	/**
	 * Appends the variables for this class into the parent's data array
	 */
	public function __construct(/*array $methods=null*/)
	{
//		$this->methods = $methods;
/*		$pre = 'Head::__construct() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
 */		
		$this->pageTitle = "Web page";
		$this->pagePreTitle = "";
		$this->pagePostTitle = "";
		$this->pageDescription = "";
		$this->bodyId = '';
		$this->bodyClass = '';
//		$this->data['beforeModalButtons'] = ''; /* or array */
//		$this->data['afterModalButtons'] = ''; /* or array */
		$this->noModel = false;
		$this->noNoScript = false;
		$this->noRequest = false;
//		parent::__construct();
		$this->showHooks();
//		$this->prepare();
	}

	/**
	 * Disallow clonning
	 */
	protected function __clone() {
		$pre = 'Head::__clone(): ';
		//throw new Exception("Can't clone a singleton");
		$log = 'clonning is not allowed for this class';
        try {
            $logger = new Logger;
            $logger->log($pre. $log);
        } catch (Exception $e) {
            error_log ('error_log: '. $pre. $log);
        }
	}

	/*	Method overloading - really overriding */

/*	public function __call(string $method, array $arguments)
	{
/ *		$method = $this->methods[$name];
		if (!is_callable(array(__CLASS__, $method))
		{
			$pre = 'Head::__call() ';
			$log = sprintf("method %s not found.", $name);
			(is_callable(array('Error', 'log'))) &&
				Error::log( $pre. $log ) ||
				error_log( $pre. $log );
			return;
		}* /
		if (isset($this->methods[$method]) && !empty($this->methods[$method]) && $func = $this->methods[$method]) {
//		if (method_exists($this, $method)) {
//			$this->$method($arguments);
//			return call_user_func($this->{$method}, $arguments);
			$func(...$arguments);
			return $this;
/ *		} else {
//		try {
//			return call_user_func($this->{$name}, $arguments);
//		} catch Exception ($ex) {
			$pre = 'Head::__call() ';
			$log = sprintf("%s::'%s' not found.", __CLASS__, $method);
			$post = '(may succeed next msg.)';
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $log. $post ) ||
				error_log( $pre. $log. $post );* /
		}
	}
 */
	/* Overloading (overriding) properties using magic methods */

	/**
	 * Retrieve the value associated with the name within the private data array
	 * IE. handle output of all unknown variables - retrieve variable from private array
	 */
	public function __get(string $name)
	{
		if (array_key_exists($name, $this->data)) {
//		if (array_key_exists($name, self::$data)) {
			return $this->data[$name];
//			return self::$data[$name];
		}
		$pre = 'Head::__get(): ';
		$trace = debug_backtrace();
		$log = 'Undefined property "'. $name .
		//	'" in ' . $trace[1]['file'] .
		//	' on line ' . $trace[1]['line'];
			'" stacktrace: ' . print_r($trace, true);
//		trigger_error($pre. $log, E_USER_NOTICE);
		(is_callable(array('Logger', 'log'))) &&
			$logger = new Logger ||
			$logger->log( $pre. $log ) ||
			error_log( $pre. $log );
		return $log;
/*		//if (!isset($this->data[$name])) {
		if (!isset(self::$data[$name])) {
		//if (null !== $this->data[$name]) {
			//$log = 'Head::__set($this->data['. $name. '], "'. $value. '")';
			//$log = 'Head::__get '. $name. ' = "'. $value. '"';
			$log = 'Head::__get failed to get "'. $name. '"';
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $log ) ||
				error_log( $log );
			return;
		}
		//return $this->data[$name];
		return self::$data[$name];*/
	}

	/**
	 * Sets external values into private data array
	 * IE. handle assigns of all unknown variables - store them in the private array
	 */
	public function __set(string $name, /*mixed*/ $value)
	{
		if (/*!array_key_exists($name, $this->data) &&*/ !isset($this->data[$name]) || $value != $this->data[$name]) {
//		if (!array_key_exists($name, self::$data) && $value != self::$data[$name]) {
			//self::$data[$name] = $value;
			//$log = 'Head::__set($this->data['. $name. '], "'. $value. '")';
			$pre = 'Head::__set(): ';
			if (!empty($value)) {
				$log = $name. ' = "';
				if (is_string($value) && !empty($value)) {
					$log .= $value;
				} elseif (is_array($value) && !empty($value)) {
					$log .= print_r($value, true);
//			} elseif (empty($value)) {
//				$log = $name. ' = (empty)';
				}
				$log .= '"';
//			}
//			if (!empty($value)) {
				(is_callable(array('Logger', 'log'))) &&
					$logger = new Logger ||
					$logger->log( $pre. $log ) ||
					error_log( $pre. $log );
			}
		}
		$this->data[$name] = $value;
//		self::$data[$name] = $value;
	}

	/**
	 * Returns whether a variable exists in the private array
	 */
	public function __isset($name)
	{
//		echo "Is '$name' set?\n";
		return isset($this->data[$name]);
//		return isset(self::$data[$name]);
	}

	/**
	 * Unset a variable from the private array
	 */
	public function __unset($name)
	{
//		echo "Unsetting '$name'\n";
		unset($this->data[$name]);
//		unset(self::$data[$name]);
	}

	/**
	 * Returns the cuurent class instance, or
	 *	creates a new instance, if none already
	 */
/*	public static function getInstance()
	{
		if (!isset(static::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}
 */
	/**
	 * Displays a HTML comment with the hook name
	 */
	public function showHooks()
	{
		if (!isset($GLOBALS['data']['DEBUG_SHOW_HOOK_POSITIONS']) && defined('DEBUG_SHOW_HOOK_POSITIONS')) {
			$GLOBALS['data']['DEBUG_SHOW_HOOK_POSITIONS'] = \DEBUG_SHOW_HOOK_POSITIONS;
		}
		if (!$this->done_hooks && isset($GLOBALS['data']['DEBUG_SHOW_HOOK_POSITIONS']) && $GLOBALS['data']['DEBUG_SHOW_HOOK_POSITIONS']) {
			/*
			Hooks::getInstance()->add('insert_doc', function () { echo '<!-- HOOK insert_doc -->'. "\n";});
			 */
//			Hooks::getInstance()->add('append_doc', function () { echo '<!-- HOOK append_doc -->'. "\n";});
			add_hook('append_doc', function () { echo '<!-- HOOK append_doc -->'. "\n";});
//			Hooks::getInstance()->add('insert_doc_html', function () { echo '<!-- HOOK insert_doc_html -->'. "\n";});
			add_hook('insert_doc_html', function () { echo '<!-- HOOK insert_doc_html -->'. "\n";});
//			Hooks::getInstance()->add('append_doc_html', function () { echo '<!-- HOOK append_doc_html -->'. "\n";});
			add_hook('append_doc_html', function () { echo '<!-- HOOK append_doc_html -->'. "\n";});
//			Hooks::getInstance()->add('insert_before_head', function () { echo '<!-- HOOK insert_before_head -->'. "\n";});
			add_hook('insert_before_head', function () { echo '<!-- HOOK insert_before_head -->'. "\n";});
//			Hooks::getInstance()->add('insert_head_top', function () { echo '<!-- HOOK insert_head_top -->'. "\n";});
			add_hook('insert_head_top', function () { echo '<!-- HOOK insert_head_top -->'. "\n";});
//			Hooks::getInstance()->add('insert_head_meta', function () { echo '<!-- HOOK insert_head_meta -->'. "\n";});
			add_hook('insert_head_meta', function () { echo '<!-- HOOK insert_head_meta -->'. "\n";});
//			Hooks::getInstance()->add('append_head_meta', function () { echo '<!-- HOOK append_head_meta -->'. "\n";});
			add_hook('append_head_meta', function () { echo '<!-- HOOK append_head_meta -->'. "\n";});
//			Hooks::getInstance()->add('insert_head_title', function () { echo '<!-- HOOK insert_head_title -->'. "\n";});
//			Hooks::getInstance()->add('append_head_title', function () { echo '<!-- HOOK append_head_title -->'. "\n";});
//			Hooks::getInstance()->add('insert_head_desc', function () { echo '<!-- HOOK insert_head_desc -->'. "\n";});
//			Hooks::getInstance()->add('append_head_desc', function () { echo '<!-- HOOK append_head_desc -->'. "\n";});
//			Hooks::getInstance()->add('insert_head_styles', function () { echo '<!-- HOOK insert_head_styles -->'. "\n";});
			add_hook('insert_head_styles', function () { echo '<!-- HOOK insert_head_styles -->'. "\n";});
//			Hooks::getInstance()->add('append_head_styles', function () { echo '<!-- HOOK append_head_styles -->'. "\n";});
			add_hook('append_head_styles', function () { echo '<!-- HOOK append_head_styles -->'. "\n";});
//			Hooks::getInstance()->add('insert_head_scripts', function () { echo '<!-- HOOK insert_head_scripts -->'. "\n";});
			add_hook('insert_head_scripts', function () { echo '<!-- HOOK insert_head_scripts -->'. "\n";});
//			Hooks::getInstance()->add('append_head_scripts', function () { echo '<!-- HOOK append_head_scripts -->'. "\n";});
			add_hook('append_head_scripts', function () { echo '<!-- HOOK append_head_scripts -->'. "\n";});
//			Hooks::getInstance()->add('insert_head_bottom', function () { echo '<!-- HOOK insert_head_bottom -->'. "\n";});
			add_hook('insert_head_bottom', function () { echo '<!-- HOOK insert_head_bottom -->'. "\n";});
//			Hooks::getInstance()->add('append_after_head', function () { echo '<!-- HOOK append_after_head -->'. "\n";});
			add_hook('append_after_head', function () { echo '<!-- HOOK append_after_head -->'. "\n";});
//			Hooks::getInstance()->add('insert_before_body', function () { echo '<!-- HOOK insert_before_body -->'. "\n";});
			add_hook('insert_before_body', function () { echo '<!-- HOOK insert_before_body -->'. "\n";});
//			Hooks::getInstance()->add('insert_body_top', function () { echo '<!-- HOOK insert_body_top -->'. "\n";});
			add_hook('insert_body_top', function () { echo '<!-- HOOK insert_body_top -->'. "\n";});
//			Hooks::getInstance()->add('insert_before_header_modal', function () { echo '<!-- HOOK insert_before_header_modal -->'. "\n";});
			add_hook('insert_before_header_modal', function () { echo '<!-- HOOK insert_before_header_modal -->'. "\n";});
//			Hooks::getInstance()->add('insert_header_modal_buttons', function () { echo '<!-- HOOK insert_header_modal_buttons -->'. "\n";});
			add_hook('insert_header_modal_buttons', function () { echo '<!-- HOOK insert_header_modal_buttons -->'. "\n";});
//			Hooks::getInstance()->add('append_header_modal_buttons', function () { echo '<!-- HOOK append_header_modal_buttons -->'. "\n";});
			add_hook('append_header_modal_buttons', function () { echo '<!-- HOOK append_header_modal_buttons -->'. "\n";});
//			Hooks::getInstance()->add('append_after_header_modal', function () { echo '<!-- HOOK append_after_header_modal -->'. "\n";});
			add_hook('append_after_header_modal', function () { echo '<!-- HOOK append_after_header_modal -->'. "\n";});
//			Hooks::getInstance()->add('insert_before_header_noscript', function () { echo '<!-- HOOK insert_before_header_noscript -->'. "\n";});
			add_hook('insert_before_header_noscript', function () { echo '<!-- HOOK insert_before_header_noscript -->'. "\n";});
//			Hooks::getInstance()->add('insert_header_noscript_top', function () { echo '<!-- HOOK insert_header_noscript_top -->'. "\n";});
			add_hook('insert_header_noscript_top', function () { echo '<!-- HOOK insert_header_noscript_top -->'. "\n";});
//			Hooks::getInstance()->add('insert_header_noscript_bottom', function () { echo '<!-- HOOK insert_header_noscript_bottom -->'. "\n";});
			add_hook('insert_header_noscript_bottom', function () { echo '<!-- HOOK insert_header_noscript_bottom -->'. "\n";});
//			Hooks::getInstance()->add('append_after_header_noscript', function () { echo '<!-- HOOK append_after_header_noscript -->'. "\n";});
			add_hook('append_after_header_noscript', function () { echo '<!-- HOOK append_after_header_noscript -->'. "\n";});
//			Hooks::getInstance()->add('append_after_header', function () { echo '<!-- HOOK append_after_header -->'. "\n";});
			add_hook('append_after_header', function () { echo '<!-- HOOK append_after_header -->'. "\n";});
		}
		$this->done_hooks = true;
	}

	/**
	 * Empty - To be overriden
	 */
/*	public function prepare()
	{
		$log = 'Head::prepare ';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $log ) ||
			error_log( $log );
//		$this->load();
/ *		error_log('Head::prepare() is_callable = '. is_callable(array('MakeHead', 'prepare')));
		if (is_callable(array('MakeHead', 'prepare'))) {
			return MakeHead::getInstance()->prepare;
		}* /
	}
*/
	/**
	 * Loads each child class (that extend this class) - includes them
	 */
	public function load()
	{
		$pre = 'Head::load(): ';
		if ($this->loaded) {
			$log = 'already loaded.';
			$from = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT|DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
			$log .= ' From '. $from['file']. ':'. $from['line'];
			(is_callable(array('Logger', 'log'))) &&
			$logger = new Logger ||
			$logger->log( $pre. $log ) ||
				error_log( $pre. $log );
			return;
		}
//		$out = false;
		foreach ( glob_recursive( __DIR__. "/Head", "*.php") as $filename ) {
			include_once($filename);
			$class = basename($filename, '.php');
				$log = 'included "'. $class. '"';
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}
/*			if ($class === 'Head') {
				continue;
			}*/
			//$c = $class::getInstance();
			$a = __NAMESPACE__ . '\\Head\\' . $class;
			$c = new $a;
		//	error_log('instance = '. print_r($c, true));
			$array = array();
			$array['class'] = $class;
			$array['filename'] = $filename;
			//print '!include '. $filename. ' >> '. $class. '!';
			//			static::$out .= '!include '. $filename. ' >> '. $class. '!'. "\n";
			$log = 'included "'. do_mbstr('str_replace', BASE, '', $filename). '", loaded "'. $class. '"';
//			$log = 'included "'. $filename. '", loaded "'. $class. '"';
/*			if ($class == 'MakeHead')
			{
				continue;
				$log = 'Head::load() preparing head ouput';
//				(is_callable(array('Error', 'log'))) &&
//					Error::log( $log ) ||
//					error_log( $log );
			} else* /if ($class !== 'MakeHead' && is_callable( array(new $class, 'prepare'), true) ) {	// Call to undefined method Head::prepare() /*
//			} else* /if ($class !== 'MakeHead' && is_callable( array($c, 'prepare') )) {	// negative /*
			} else* /if (method_exists($class, 'prepare')) {	// Call to undefined method Head::prepare()
			} else* /if (method_exists($c, 'prepare')) {	// negative */
				$c = "\\Yuma\\HTML\\Head\\$class";
/*			} else*/if (is_callable( array(new $c, 'prepare'), true, $callable_name )) {	// Call to undefined method Head::prepare() /*
				(new $c)->prepare();
				//$class::getInstance()->prepare();
//			/**/				$c->prepare();
				$log .= ', executed "prepare()"';
			$array['method'] = "prepare";/**/
			/**/		}/**/ else {
				$log = sprintf('Failed to call %s', $callable_name);
				(is_callable(array('Logger', 'log'))) &&
			$logger = new Logger ||
			$logger->log( $pre. $log ) ||
					error_log( $pre. $log );
			}
			(is_callable(array('Logger', 'log'))) &&
			$logger = new Logger ||
			$logger->log( $pre. $log ) ||
				error_log( $pre. $log );
			$this->children[] = $array;
		}
		$this->loaded = true;
		//new MakeHead();
		//$c = MakeHead::getInstance();
//		MakeHead::getInstance()->prepare();
		//$this->out = (new MakeHead)->prepare();
/*		if (is_callable( array($c, 'prepare'), true) ) {
			$this->out = $c->prepare();
		}*/
/*		$c = MakeHead::getInstance();	// call last
		$can = is_callable(array($c/ *'MakeHead'* /, 'prepare'));
		error_log('Head::load() is_callable = '. $can);
		if ($can) {
			$prepared = $c->prepare();
			$this->out = (!empty($prepared)) ? $prepared : $this->out;
		}
		return $this->out;*/
		$this->out();
	}

	/**
	 * Execute the prepare method of each child
	 */
	public function out()
	{
		if (!$this->loaded) {
			$this->load();
		}
		foreach ($this->children as $child) {
			if (!isset($child['class']))
				continue;
			//error_log('Head processing '. $child['class']);
//			error_log('Head processing '. print_r($child, true));
			if (isset($child['class']) /*&& $child['class'] !== 'MakeHead'*/ && isset($child['method']) && $child['method'] == 'prepare') {
				$c = $child['class'];
				$cls = "\\Yuma\\HTML\\Head\\$c";
				$m = $child['method'];
	//			(new $child['class'])->$m();
				(new $cls)->prepare();
//				$c::getInstance()->$m();
/*				$log = 'Head::out preparing "'. $child['class']. '::'. $m. '()"';
				(is_callable(array('Logger', 'log'))) &&
					Logger::getInstance()->log( $log ) ||
					error_log( $log );*/
			}
		}
//		$c = new MakeHead();	// call last
/*		$c = MakeHead::getInstance();	// call last
//		error_log('MakeHead = '. print_r($c, true));
		//if (is_callable(array($c/ *'MakeHead'* /, 'prepare'))) {
		//error_log('is_callable = '. is_callable(array($c/ *'MakeHead'* /, 'prepare')));
		//if (method_exists(array('MakeHead', 'prepare'))) {
//			$this->out = (new MakeHead)->prepare();
		$can = is_callable(array($c/ *'MakeHead'* /, 'prepare'));
		error_log('Head::out() is_callable = '. $can);
		if ($can) {
			$prepared = $c->prepare();
			$this->out = (!empty($prepared)) ? $prepared : $this->out;
		}
		//error_log('Head::out() is_callable = '. is_callable(array($c/ *'MakeHead'* /, 'prepare')));
		//	$prepared = $c->prepare();
		//	$this->out = (!empty($prepared)) ? $prepared : $this->out;
//			$this->out = MakeHead::getInstance()->prepare();
		return $this->out;*/
		$out = $this->output();
//		Hooks::getInstance()->perform('insert_body_top');
		do_action('insert_body_top');
		if (!isset($this->noModel) || !$this->noModel)
		{
			$log = 'try headModal';
			try {
				$logger = new Logger();
				$logger->log ('Head::out() '. $log);
			} catch (Exception $e) {
				error_log ('error_log: Head::out() '. $uri. ' ; Exception: '. json_encode($e));
			}
			$out .= $this->headModal();
		}
		if (!isset($this->noNoScript) || !$this->noNoScript)
		{
			$log = 'try noScript';
			try {
				$logger = new Logger();
				$logger->log ('Head::out() '. $log);
			} catch (Exception $e) {
				error_log ('error_log: Head::out() '. $uri. ' ; Exception: '. json_encode($e));
			}
			$out .= $this->noScript();
		}
		if (!isset($this->noRequest) || !$this->noRequest)
		{
			/**/$out .= $this->headRequest();	// TODO: UNKNOWN ERROR IN HERE
		}
//		Hooks::getInstance()->perform('append_afer_header');
/*	$log = 'try loaded2-before append_afer_header';
	try {
		$logger = new \Yuma\Logger();
		$logger->log ('Head::out() '. $log);
	} catch (Exception $e) {
		error_log ('error_log: Head::out() '. $uri. ' ; Exception: '. json_encode($e));
	}*/
		do_action('append_afer_header');
			return $out;
//		}
	//	echo 'makehead():'. print_r($this->out, true);
	}
/*
	function addMethod($name, $method, $args=null)
	{
		if (func_num_args() > 1) {
//			$method['body_id'] = func_get_arg(1);
		}
		$this->{$name} = $method;
	}
*/

	/**
	 * Returns the HTML code for the head section
	 *  and includes the scripts, if any, from the Scripts class
	 *  and includes the styles, if any, from the Styles class
	 *  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	 */
	private function output()
	{
/*		$pre = 'Head::output() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );*/
		$d = new Doc();
		$meta = new Meta();
		$sty = new Styles;
		$spt = new Scripts;
		ob_start();
//		if (!Hooks::getInstance()->has('insert_doc')) :
		//if (!\Yuma\HTML\Hook\has_hook('insert_doc')) :
		if (!has_hook('insert_doc')) :
			//$doc = Doc::getAll();
			$doc = $d->getAll();
			if (isset($doc['doc'])) {
				echo $doc['doc']. "\n";
				unset($doc['doc']);
			}
//			Hooks::getInstance()->perform('insert_doc_html');
			do_action('insert_doc_html');
			if (is_array($doc) && !empty($doc)) {
		//		if (defined('DEBUG') && DEBUG > 2) {
		//			echo "<!-- HTML -->\n";
		//		}
				foreach( $doc as $d ) {
					echo "{$d}\n";
				}
		//		if (defined('DEBUG') && DEBUG > 2) {
		//			echo "<!-- /HTML -->\n";
		//		}
			} else
//				Hooks::getInstance()->perform('insert_doc');
				do_action('insert_doc');
		endif;
//		Hooks::getInstance()->perform('append_doc_html');
		do_action('append_doc_html');
//		Hooks::getInstance()->perform('append_doc');
		do_action('append_doc');
//		Hooks::getInstance()->perform('insert_before_head');
		do_action('insert_before_head');
		?><head><?php echo "\n";
//		Hooks::getInstance()->perform('insert_head_top');
		do_action('insert_head_top');
		//if (!defined('DEBUG_HIDE_HEAD_METAS') || !DEBUG_HIDE_HEAD_METAS)
		if (!isset($GLOBALS['data']['DEBUG_HIDE_HEAD_METAS']) || !$GLOBALS['data']['DEBUG_HIDE_HEAD_METAS'])
		{
//			Hooks::getInstance()->perform('insert_head_meta');
			do_action('insert_head_meta');
			$metas = $meta->getAll();
			if (is_array($metas) && !empty($metas) ) {
				//if (defined('DEBUG') && isset(DEBUG['head_placeholders']) && DEBUG['head_placeholders']) {
				//if (defined('DEBUG_SHOW_HEAD_COMMENTS') && DEBUG_SHOW_HEAD_COMMENTS) {
				if (isset($GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) && $GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) {
					echo "<!-- metas -->\n";
				}
				foreach( $metas as $meta ) {
					echo "\t$meta\n";
				}
				//if (defined('DEBUG') && isset(DEBUG['head_placeholders']) && DEBUG['head_placeholders']) {
				//if (defined('DEBUG_SHOW_HEAD_COMMENTS') && DEBUG_SHOW_HEAD_COMMENTS) {
				if (isset($GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) && $GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) {
				//if (defined('DEBUG') && DEBUG > 2) {
					echo "<!-- /metas -->\n";
				}
			}
//			Hooks::getInstance()->perform('append_head_meta');
			do_action('append_head_meta');
		}
//		Hooks::getInstance()->perform('insert_head_title');
		echo "\t";
//		if (self::$data['bodyId'] === 'home')
		if ($this->data['bodyId'] === 'home')
		{
			/*?><title><?=self::$data['pageTitle']?></title><?php	*/
			?><title><?=$this->data['pageTitle']?></title><?php	
		} else {
			/*?><title><?=self::$data['pagePreTitle']?><?=self::$data['pageTitle']?><?=self::$data['pagePostTitle']?></title><?php	*/
			?><title><?=$this->data['pagePreTitle']?><?=$this->data['pageTitle']?><?=$this->data['pagePostTitle']?></title><?php	
		}
		echo "\n";
//		Hooks::getInstance()->perform('append_head_title');
//		Hooks::getInstance()->perform('insert_head_desc');
//		$desc = t('_pageDescription');
//		if (substr($desc, 0, 1) != '_') :
		if (!empty($this->data['pageDescription'])) :
//		if (!empty(self::$data['pageDescription'])) :
			?><meta name="description" content="<?=$this->data['pageDescription']?>" /><?php echo "\n";
			/*?><description><?=self::$data['pageDescription']?></description><?php echo "\n";*/
		endif;
//		Hooks::getInstance()->perform('append_head_desc');
		//if (!defined('DEBUG_HIDE_HEAD_STYLES') || !DEBUG_HIDE_HEAD_STYLES)
		if (!isset($GLOBALS['data']['DEBUG_HIDE_HEAD_STYLES']) || !$GLOBALS['data']['DEBUG_HIDE_HEAD_STYLES'])
		{
//			Hooks::getInstance()->perform('insert_head_styles');
			do_action('insert_head_styles');
			//$header_styles = Styles::getInstance()->getAll();
			$header_styles = $sty->getAll();
			if (is_array($header_styles) && !empty($header_styles) ) {
				//if (defined('DEBUG') && isset(DEBUG['head_placeholders']) && DEBUG['head_placeholders']) {
				//if (defined('DEBUG_SHOW_HEAD_COMMENTS') && DEBUG_SHOW_HEAD_COMMENTS) {
				if (isset($GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) && $GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) {
					echo "<!-- header_styles -->\n";
		//		echo print_r($header_styles, true);
				}
				foreach( $header_styles as $style ) {
					echo "\t$style\n";
				}
				//if (defined('DEBUG') && isset(DEBUG['head_placeholders']) && DEBUG['head_placeholders']) {
				//if (defined('DEBUG_SHOW_HEAD_COMMENTS') && DEBUG_SHOW_HEAD_COMMENTS) {
				if (isset($GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) && $GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) {
					echo "<!-- /header_styles -->\n";
				}
			}
//			Hooks::getInstance()->perform('append_head_styles');
			do_action('append_head_styles');
		}
		//if (!defined('DEBUG_HIDE_HEAD_SCRIPTS') || !DEBUG_HIDE_HEAD_SCRIPTS)
		if (!isset($GLOBALS['data']['DEBUG_HIDE_HEAD_SCRIPTS']) || !$GLOBALS['data']['DEBUG_HIDE_HEAD_SCRIPTS'])
		{
//			Hooks::getInstance()->perform('insert_head_scripts');
			do_action('insert_head_scripts');
		//	$inline = 'var myModal = new bootstrap.Modal(document.getElementById("myModal"), options);';
		//	Scripts::getInstance()->enqueueInline(Array( 'name' => 'bootstrap-modal', 'code' => $inline, 'put_in_header' => true, 'requires' => Array('bootstrap@5.0.1') ));
		//	Scripts::getInstance()->enqueue(Array( 'name' => 'element', 'src' => BASE. 'js/Element.js', 'version' => filemtime('js/Element.js') ));
	//		Scripts::getInstance()->enqueue(Array( 'name' => 'fontawesomekit', 'src' => "https://kit.fontawesome.com/42a7fe7c88.js", 'crossorigin' => "anonymous" ));
			//$header_scripts = Scripts::getInstance()->getInHead();
			$header_scripts = $spt->getInHead();
			if (is_array($header_scripts) && !empty($header_scripts) ) {
				//if (defined('DEBUG') && isset(DEBUG['head_placeholders']) && DEBUG['head_placeholders']) {
				//if (defined('DEBUG_SHOW_HEAD_COMMENTS') && DEBUG_SHOW_HEAD_COMMENTS) {
				if (isset($GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) && $GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) {
					echo "<!-- header_scripts -->\n";
				}
				foreach( $header_scripts as $script ) {
					echo "\t$script\n";
				}
				//if (defined('DEBUG') && isset(DEBUG['head_placeholders']) && DEBUG['head_placeholders']) {
				//if (defined('DEBUG_SHOW_HEAD_COMMENTS') && DEBUG_SHOW_HEAD_COMMENTS) {
				if (isset($GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) && $GLOBALS['data']['DEBUG_SHOW_HEAD_COMMENTS']) {
					echo "<!-- /header_scripts -->\n";
				}
			}
//			Hooks::getInstance()->perform('append_head_scripts');
			do_action('append_head_scripts');
		}
		/*<link rel="canonical" href="https://rasp.local/home.php">*/
//		Hooks::getInstance()->perform('insert_head_bottom');
		do_action('insert_head_bottom');
		?></head>
<?php	//Hooks::getInstance()->perform('append_afer_head');	?>
<?php	do_action('append_afer_head');	?>
<?php	//Hooks::getInstance()->perform('insert_before_body');	?>
<?php	do_action('insert_before_body');	?>
	<body<?php if (!empty($this->data['bodyId'])) { echo ' id="'. $this->data['bodyId']. '"'; } if (!empty($this->data['bodyClass'])) { echo ' class="'. $this->data['bodyClass']. '"'; } ?>>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	private function headModal()
	{
		ob_start();
		?>
	<!--	<div id="pageUnload"></div>-->
<?php	//Hooks::getInstance()->perform('insert_before_header_modal');	?>
<?php	do_action('insert_before_header_modal');	?>
	<!-- Modal -->
		<div class="modal fade clearfix" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
<!--div class="modal fade" id="edit-pp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editppLabel" aria-hidden="true">
</div>-->
			<div class="modal-dialog">
				<div class="modal-content">
					<!--<div class="m-3">Loading...</div>-->
					<div class="modal-header">
						<h5 class="modal-title" id="myModalLabel"><span><?=t('Modal Title')?></span></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body"><?=t('Modal body')?></div>
					<div class="modal-footer">
		<?php
/*			if (isset($this->data['before']))
			{
				if (is_array($this->data['before']))
				{
					foreach ($this->before as $btn)
					{
						echo $btn. "\n";
					}
				} else {
					echo $this->before. "\n";
				}
		}*/
//		Hooks::getInstance()->perform('insert_header_modal_buttons');
		do_action('insert_header_modal_buttons');
		?>
						<button type="button" name="modal-button" value="cancel" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="<?=t('Cancel')?>"><?=t('Cancel')?></button>
						<button type="button" name="modal-button" value="proceed" class="btn btn-primary" aria-label="<?=t('Proceed')?>"><?=t('Proceed')?></button>
		<?php
/*			if (isset($this->after))
			{
				if (is_array($this->after))
				{
					foreach ($this->after as $btn)
					{
						echo $btn. "\n";
					}
				} else {
					echo $this->after. "\n";
				}
			}*/
//		Hooks::getInstance()->perform('append_header_modal_buttons');
		do_action('append_header_modal_buttons');
		?>
					</div>
				</div>
			</div>
		</div>
<?php	//Hooks::getInstance()->perform('append_after_header_modal');	?>
<?php	do_action('append_after_header_modal');	?>
	<!-- Modal dynamic content -->
	<!--	<div class="modal fade" id="myModal2" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog" id="include" data-include="comment">
			</div>
		</div>-->
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	private function noScript()
	{
		ob_start();
//		Hooks::getInstance()->perform('insert_before_header_noscript');	? >
		do_action('insert_before_header_noscript');	?>
<noscript class="xsticky-top my_sticky_top">
<?php	//Hooks::getInstance()->perform('insert_header_noscript_top');	?>
<?php	do_action('insert_header_noscript_top');	?>
			<div class="banner">
				<p><?=t('_nojs_banner')?></p>
			</div>
<?php	//Hooks::getInstance()->perform('insert_header_noscript_bottom');	?>
<?php	do_action('insert_header_noscript_bottom');	?>
</noscript>
<?php	//Hooks::getInstance()->perform('append_after_header_noscript');	?>
<?php	do_action('append_after_header_noscript');	?>
		<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
		//document.querySelector('.navbar').nextElementSibling.style.marginTop = '3em'
	}

	private function headRequest()
	{
		global $store;
//		$value = htmlentities($store->load('DEBUG_SHOW_REQ', defined('DEBUG_SHOW_REQ') ? DEBUG_SHOW_REQ : NULL));
		$value = 1;
		if ($value && defined('DEBUG_SHOW_COOKIES') && DEBUG_SHOW_COOKIES) {
			$cookie = getServerValue('HTTP_COOKIE');
			if (empty($cookie))
				$cookie = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_STRING | FILTER_REQUIRE_ARRAY);
			if (empty($cookie))
				$cookie = $_COOKIE;
		}
		$logger = new Logger();
		ob_start();
	$log = 'STOPS during DB query !!! Skipped with a fake true value !!!';
	try {
		$logger->log ('Head::out()headRequest() '. $log);
	} catch (Exception $e) {
		error_log ('error_log: Head::out()headRequest() '. $uri. ' ; Exception: '. json_encode($e));
	}
		if ($value)	{
			showRequest($cookie);
		}
		$out = ob_get_contents();
		ob_end_clean();
	$log = 'NO ERROR';
	try {
		//$logger = new Logger();
		$logger->log ('Head::out()headRequest() '. $log);
	} catch (Exception $e) {
		error_log ('error_log: Head::out()headRequest() '. $uri. ' ; Exception: '. json_encode($e));
	}

		return $out;
	}

}
