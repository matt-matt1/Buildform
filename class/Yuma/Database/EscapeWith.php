<?php

declare(strict_types=1);
namespace Yuma\Database;

class EscapeWith {
	public function __construct( $str="", $char='`' ) {
		trim( $str, $char );
        return $char. $str. $char;
	}
}
