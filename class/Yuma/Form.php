<?php

declare(strict_types=1);

namespace Yuma;
	class Form {
		private static $prefix = 'form_';
	
		public static function getPrefix() {
			return static::$prefix;
		}
	
		public static function setPrefix($value) {
			static::$prefix = $value;
			return false;
		}
	
		public static function hasPrefix(string $table) {
			return strpos( $table, static::$prefix ) === 0;
		}
	
		public static function addPrefix(string $table) {
			return static::$prefix. $table;
		}
	
		public static function delPrefix(string $table) {
			if (hasPrefix($table)) {
				return "not";
			}
			return do_mbstr('substr', $table, do_mbstr('strlen', static::$prefix) );
		}
	
	}

