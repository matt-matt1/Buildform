<?php

namespace Yuma;

if (!class_exists ('Yuma\Logger')) {
	class Logger //extends Singleton
	{
	//	protected static $instance;
		public $msg;
		
		public function __construct ($msg=null)
		{
			if (isset($msg) && !empty($msg)) {
				$this->msg = $msg;
			}
		}
		
		public function log($msg=null)
		{
			if (isset($msg) && !empty($msg)) {
				$this->msg = $msg;
			}
			if (!isset($this->msg)) {
				return;
			}
			error_log( $this->msg );
		}
		
		public function logWithTrace($msg=null)
		{
			if (isset($msg) && !empty($msg)) {
				$this->msg = $msg;
			}
			if (!isset($this->msg)) {
				return;
			}
			error_log( $this->msg. "\n". $this->getTrace() );
		}
		
		private function getTrace()
		{
			ob_start();
			debug_print_backtrace();
			return ob_get_clean();
		}
	}
}

