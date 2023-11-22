<?php
namespace Yuma;

/**
 * Function that gets the current class instance and calls the class method,
 *	hides the class structures - to increase readability
 */
function enqueueStyle( string $name, string $filename, string $version='', string $media='all', bool $preload=false, bool $defer=false ) {
	Styles::getInstance()->enqueue( $name, $filename, $version, $media, $preload, $defer );
}
