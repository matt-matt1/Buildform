<?php
declare(strict_types=1);

namespace Yuma\HTML;
use function \Yuma\do_mbstr;

class Link extends Styles {

	/**
	 * Link a Stylesheet file to the current HTML document - array form
	 */
	public static function style( array $params )
	{
		/*$defaults = Array(
			'name' => '',
			'href' => '',
			'version' => '',
			'media' => '',//string $media='all'
			'preload' => false,
			'defer' => false,
			'cross_domain' => '',
			'show_comment' => false,
			'data-minify' => true,
			'id' => '',
			'integrity' => "",
			'cross_origin' => "",
		);*/
//		$sty = new Styles;
		//return Styles::getInstance()->enqueue( $params );
//		return $sty->enqueue( $params );
		return (new Styles)->enqueue( $params );
//		return get_parent_class($this)->enqueue( $params );
//		return call_user_func(array(__CLASS__, 'enqueue'), $params);
	}

	/**
	 * Creates an HTML string to link a given filename (including full path) as a style
	 */
	public static function styleFromFilename( string $filename )
	{
//		$sty = new Styles;
		if (strpos($filename, ':') !== true)
		{
			$parts = explode(DIRECTORY_SEPARATOR, $filename);
			$now = false;
			$file = '';
			$pieces = explode('.', $parts[count($parts)-1]);
			$ext = array_pop($pieces);
			$name = implode('.', $pieces);
			foreach($parts as $p) {
				if ($p == 'css') {
					$now = true;
				}
				if ($now) {
					$file .= $p. DIRECTORY_SEPARATOR;
				}
			}
			$file = do_mbstr('substr', $file, 0, -1);
			//static::style( $name, BASE. $file, filemtime($filename) );
			//Styles::getInstance()->enqueue( $name, BASE. $file, filemtime($filename) );
//			return $sty->enqueue(Array( 'name' => $name, 'href' => BASE. $file, 'version' => filemtime($filename) ));
			return (new Styles)->enqueue(Array( 'name' => $name, 'href' => BASE. $file, 'version' => filemtime($filename) ));
	//		return $this->enqueue(Array( 'name' => $name, 'href' => BASE. $file, 'version' => filemtime($filename) ));
		//	return Styles::enqueue(Array( 'name' => $name, 'href' => BASE. $file, 'version' => filemtime($filename) ));
//			return call_user_func(array(__CLASS__, 'enqueue'), Array( 'name' => $name, 'href' => BASE. $file, 'version' => filemtime($filename) ));
		} else {
			//Styles::getInstance()->enqueue( $name, $filename );
//			return $sty->enqueue(Array( 'name' => $name, 'href' => $filename ));
			//return (new Styles)->enqueue(Array( 'name' => $name, 'href' => $filename ));
			return (new Styles)->enqueue(Array( 'href' => $filename ));
	//		return $this->enqueue(Array( 'name' => $name, 'href' => $filename ));
		//	return Styles::enqueue(Array( 'name' => $name, 'href' => $filename ));
//			return call_user_func(array(__CLASS__, 'enqueue'), Array( 'name' => $name, 'href' => BASE. $file, 'version' => filemtime($filename) ));
		}
	}

	/**
	 * Returns an array of the styles as HTML strings
	 */
	public function getStyles() {
		return Styles::getInstance()->getAll();
	}

	public function wlwmanifest() {
		$icon = Array(
			'rel' => "wlwmanifest",
			'type' => "application/wlwmanifest+xml",
			'href' => "https://www.mydarlingvegan.com/wp-includes/wlwmanifest.xml",
		);
	}

	public function editURI() {
		$icon = Array(
			'rel' => "EdirURI",
			'type' => 'application/rsd+xml',
			'title' => "RSD",
			'href' => "https://somesite.com/xmlrpc.php?rcd"
		);
	}

	public function api() {
		$icon = Array(
			'rel' => "https://api.w.org/",
			'href' => "https://www.mydarlingvegan.com/wp-json/"
		);
	}

	public function shortlink() {
		$icon = Array(
			'href' => 'https://somesite.com/?query=value',
		);
	}

	public function conanical() {
		$icon = Array(
			'href' => 'https://somesite.com/subdirectory/',
		);
	}

	public function dnsPrefetch() {// rel=preconnect
		$icon = Array(
			'rel' => 'dns-prefetch',
			'href' => 'https://subdomain.somesite.com/',
		);
	}

	public function alternate() {
		$icon = Array(
			'rel' => 'alternate',
			'type' => 'application/rss+xml',
			'title' => 'Site title - subtitle',
			'href' => 'https://somesite.com/feed/',
		);
	}

	public function gStatic() {
		$icon = Array(
			'crossorigin' => true,
			'rel' => 'preconnect',
			'href' => 'https://fonts.gstatic.com',
		);
	}

	public function manifest() {
		$icon = Array(
			'href' => 'size.webmanifest',
		);
	}

	public function pinback() {
		$icon = Array(
			'href' => 'xlmrpc.php',
		);
	}

	public function preFont() {
		$icon = Array(
			'rel' => 'preload',
			'as' => 'font',
			'href' => 'regulat.woff2',
		);
	}

	public function maskIcon() {
		$icon = Array(
			'href' => 'tab.svg',
		);
	}

	public function icon() {
		$sicon = Array(
			'rel' => 'shortcut icon',
			'href' => 'favicon-32x32.ico',
		);
		$icon = Array(
			'rel' => 'icon',
			'sizes' => '32x32',
			'href' => 'favicon-32x32.png',
		);
		$apple = Array(
			'rel' => 'apple-touch-icon',
			'sizes' => '180x180',
			'href' => 'apple-touch-icon.png',
		);
		return "not implemented yet";
	}

}
