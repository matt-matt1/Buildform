<?php
//namespace Yuma;
	/*
	class MyClass extends Singleton {
		//public static $instance;
		protected static $instance;
	}
	$theInstance = MyClass::getInstance();
	 */
	abstract class DontUse_Singleton {
	
	//	abstract protected static $instance;	# classes that extend this class must define an instance variable
	
		protected function __clone() {	# we don't permit cloning the singleton (like $x = clone $v)
			//throw new Exception("Can't clone a singleton");
		}
		//public function __wakeup() {}
	
		/**
		 * Returns the cuurent class instance, or
		 *	creates a new instance, if none already
		 */
		public static function getInstance()
		{
			if (!isset(static::$instance)) {
				static::$instance = new static();
			}
	
			return static::$instance;
	/*		if (!isset(self::$instance)) {
				self::$instance = new static();
			}
	
			return self::$instance;*/
	//		return (self::$instance === null) ? self::$instance = new static() : self::$instance;
		}
	
	}

