<?php
declare(strict_types=1);

namespace Yuma\HTML\Head;
use Yuma\HTML\Head;
use Yuma\HTML\Meta;
use Yuma\HTML\Head_interface;
use function Yuma\do_mbstr;
use function Yuma\t;
/**
 * Social meta tags that are used exclusively for article for the head section
 * Produces:
<meta name="article:card" content="summary_large_image">
<meta name="article:site" content="@ProtonMail">
<meta name="article:title" content="Proton Drive">
<meta name="article:description" content="Proton Drive allows you to securely store and share your sensitive documents and access them anywhere.">
<meta name="article:image" content="https://drive.proton.me/assets/proton-og-image.png">
<meta name="article:image:alt" content="The shiny Proton Drive logo">
 */

/* Put the following into ./lang/<language code>.php:
	'_article:card' => "Determines the type of card to use. These are the options:
• summary—provides * a Summary Card, which has a small square image alongside summary text.
• summary_large_image—provides * a Summary Card again with a larger image, more suitable when the image is rectangular.
• app—provides * an App Card, only relevant if you develop mobile applications.
• player—provides * a Player Card, with embedded video clips, audio streams and other media",
	'_article:site' => "The article handle for your website or business, shown in the card footer. eg. @takanomi",
	'_article:title' => "The title of the object.",
	'_article:description' => "The description of the object. eg. Proton Drive allows you to securely store and share your sensitive documents and access them anywhere.",
	'_article:creator' => "article username of the content creator or author. *Only relevant when 'card' has been set to 'summary_large_image'.",
	// *** NOTE: animated GIFs won't appear animated on article *** //
	'_article:image' => "The full URL for the associated image. eg. https://drive.proton.me/assets/proton-og-image.png",
	'_article:image:alt' => "Provides alternative text for the image. eg. The shiny Proton Drive logo",
 */

class Article extends Head implements Head_interface {
	private $done_init = false;

	/**
	 * Appends the variables for this class into the parent's data array
	 */
	function __construct()
	{
/*		$pre = 'Article::__construct() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
 */
		if (!$this->done_init)
		{
			$this->articlePub = "";
			$this->articleSect = "";
			$this->articleTag = array();
			$this->done_init = true;
		}
		$this->prepare();
	}

	/**
	 * Populates the article head tags
	 */
	public function prepare()
	{
/*		$pre = 'Article::prepare() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
 */
		if (!defined('SHOW_ARTICLE_TAGS') || !SHOW_ARTICLE_TAGS)
			return;
//		parent::getInstance()->
		$publisher = t("_article:publisher");
		//$publisher = t("_article:publisher");
		if (isset($this->articlePub) && !empty($this->articlePub))
		{
			$publisher = $this->articlePub;
		}
		if (do_mbstr('substr', $publisher, 0, 1) !== '_' /*&& !Meta::getInstance()->has('og.title')*/)
		{
//			$title = static::$page_title;
			Meta::getInstance()->add( 'article.title', 'name', 'article:title', $publisher );
		}
		$section = t('_article:section');
		if (isset($this->articleSect) && !empty($this->articleSect))
		{
			$section = $this->articleSect;
		}
		if (do_mbstr('substr', $section, 0, 1) !== '_')
		{
			Meta::getInstance()->add( 'article.section', 'name', 'article:section', $section );
		}
/*		$audio = t('article:audio');
		if (is_array($audio))
		{
			foreach ($audio as $key => $o)
			{
				static::placeAudio($o, $key);
			}
		} else if (mb_substr($audio, 0, 1) !== '_')
		{
			static::placeAudio($audio);
		}*/
/*		$site = t('_article:site');
		if (!empty($article_site))
		{
			$site = static::$article_site;
		}
		if (mb_substr($site, 0, 1) !== '_')
		{
			Meta::getInstance()->add( 'article.site', 'name', 'article:site', $site );
		}
		$description = t('_article:description');
		if (!empty($article_desc))
		{
			$description = static::$article_desc;
		}
		if (mb_substr($description, 0, 1) !== '_' && !Meta::getInstance()->has('og:description'))
		{
			Meta::getInstance()->add( 'article.description', 'name', 'article:description', $description );
		}*/
		$tag = t('_article:tag');
		if (isset($this->articleTag) && !empty($this->articleTag))
		{
			$tag = $this->articleTag;
		}
		if (is_array($tag))
		{
			foreach ($tag as $key => $t)
			{
//				static::placeTag($o, $key);
				Meta::getInstance()->add( 'article.tag', 'name', 'article:tag', $t );
			}
		} else if (do_mbstr('substr', $tag, 0, 1) !== '_')
		{
/*			static::placeImage($image);*/
			Meta::getInstance()->add( 'article.tag', 'name', 'article:tag', $tag );
		}
/*		$video = t('article:video');
		if (do_mbstr('b_substr', $video, 0, 1)) !== '_')
		{
			Meta::add( 'article.video', 'property', 'article:video', $video );
			$url = t('article:video:url');	// UNUSED
			if (mb_substr($url, 0, 1) !== '_')
			{
				Meta::add( 'article_video_url', 'property', 'article:video:url', $url );
			}
			$surl = t('article:video:secure_url');
			if (mb_substr($alt, 0, 1) !== '_')
			{
				Meta::add( 'article.video.secure_url', 'property', 'article:video:secure_url', $surl );
			}
			$alt = t('article:video:alt');
			if (mb_substr($alt, 0, 1) !== '_')
			{
				Meta::add( 'article.video.alt', 'property', 'article:video:alt', $alt );
			}
			$type = t('article:video:type');
			if (mb_substr($type, 0, 1) !== '_')
			{
				Meta::add( 'article.video.type', 'property', 'article:video:type', $type );
			}
			$w = t('article:video:width');
			if (mb_substr($w, 0, 1) !== '_')
			{
				Meta::add( 'article.video.width', 'property', 'article:video:width', $w );
			}
			$h = t('article:video:height');
			if (mb_substr($h, 0, 1) !== '_')
			{
				Meta::add( 'article.video.height', 'property', 'article:video:height', $h );
			}
		}*/
	}
/*
	private static function placeImage($img, $key='')
	{
//		$image = isset($img['url']) ? $img['url'] : $img;
		Meta::add( 'article.image'. $key, 'name', 'article:image', $image );
		if (isset($img['secure_url']))
		{
			Meta::add( 'article.image._secure_url'. $key, 'name', 'article:image:secure_url', $img['secure_url'] );
		} else {
			$surl = t('article:image:secure_url');
			if (mb_substr($surl, 0, 1) !== '_')
			{
				Meta::add( 'article.image.secure_url', 'name', 'article:image:secure_url', $surl );
			}
		}
		if (isset($img['alt']))
		{
			Meta::add( 'article.image.alt'. $key, 'name', 'article:image:alt', $img['alt'] );
		} else {
			$alt = t('article:image:alt');	// A description of what is in the image
			if (mb_substr($alt, 0, 1) !== '_')
			{
				Meta::add( 'article.image.alt', 'name', 'article:image:alt', $alt );
			}
		}
		if (isset($img['type']))
		{
			Meta::add( 'article.image.type'. $key, 'name', 'article:image:type', $img['type'] );
		} else {
			$type = t('article:image:type');	// MIME
			if (mb_substr($type, 0, 1) !== '_')
			{
				Meta::add( 'article.image.type', 'name', 'article:image:type', $type );
			}
		}
		if (isset($img['width']))
		{
			Meta::add( 'article.image.width'. $key, 'name', 'article:image:width', $img['width'] );
		} else {
			$w = t('article:image:width');	// The number of pixels
			if (mb_substr($w, 0, 1) !== '_')
			{
				Meta::add( 'article.image.width', 'name', 'article:image:width', $w );
			}
		}
		if (isset($img['height']))
		{
			Meta::add( 'article.image.height'. $key, 'name', 'article:image:height', $img['height'] );
		} else {
			$h = t('article:image:height');	// The number of pixels
			if (mb_substr($h, 0, 1) !== '_')
			{
				Meta::add( 'article.image.height', 'name', 'article:image:height', $h );
			}
		}
	}*/

}
