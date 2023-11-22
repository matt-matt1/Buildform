<?php
declare(strict_types=1);

namespace Yuma\HTML\Head;
use Yuma\HTML\Head;
use Yuma\HTML\Meta;
use Yuma\HTML\Head_interface;
use function \Yuma\do_mbstr;
use function \Yuma\t;
/**
 * Social meta tags that are used exclusively for Twitter for the head section
 * Produces:
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@ProtonMail">
<meta name="twitter:title" content="Proton Drive">
<meta name="twitter:description" content="Proton Drive allows you to securely store and share your sensitive documents and access them anywhere.">
<meta name="twitter:image" content="https://drive.proton.me/assets/proton-og-image.png">
<meta name="twitter:image:alt" content="The shiny Proton Drive logo">
 */

/* Put the following into ./lang/<language code>.php:
	'_twitter:card' => "Determines the type of card to use. These are the options:
• summary—provides * a Summary Card, which has a small square image alongside summary text.
• summary_large_image—provides * a Summary Card again with a larger image, more suitable when the image is rectangular.
• app—provides * an App Card, only relevant if you develop mobile applications.
• player—provides * a Player Card, with embedded video clips, audio streams and other media",
	'_twitter:site' => "The Twitter handle for your website or business, shown in the card footer. eg. @takanomi",
	'_twitter:title' => "The title of the object.",
	'_twitter:description' => "The description of the object. eg. Proton Drive allows you to securely store and share your sensitive documents and access them anywhere.",
	'_twitter:creator' => "Twitter username of the content creator or author. *Only relevant when 'card' has been set to 'summary_large_image'.",
	// *** NOTE: animated GIFs won't appear animated on Twitter *** //
	'_twitter:image' => "The full URL for the associated image. eg. https://drive.proton.me/assets/proton-og-image.png",
	'_twitter:image:alt' => "Provides alternative text for the image. eg. The shiny Proton Drive logo",
 */

class Twitter extends Head implements Head_interface
{
	private $done_init = false;

	/**
	 * Appends the variables for this class into the parent's data array
	 */
	function __construct()
	{
/*		$pre = 'Twitter::__construct() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
 */
		if (!$this->done_init)
		{
			$this->twitterTitle = '';
			$this->twitterDesc = '';
			$this->twitterSite = '';
			$this->twitterCard = '';
			$this->twitterImage = array();
			$this->done_init = true;
		}
//		parent::__construct();
		$this->prepare();
	}

	/**
	 * Populates the article head tags
	 */
	public function prepare()
	{
/*		$pre = 'Twitter::prepare() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
 */
		$meta = new Meta;
		if (!defined('SHOW_TWITTER_TAGS') || !SHOW_TWITTER_TAGS)
			return;
//		parent::getInstance()->
		$title = t("_twitter:title");
		if (isset($this->twitterTitle) && !empty($this->twitterTitle))
		{
			$title = $this->twitterTitle;
		}
		if (do_mbstr('substr', $title, 0, 1) !== '_' && !$meta->has('og.title'))
		{
//			$title = static::$page_title;
			$meta->add( 'twitter.title', 'name', 'twitter:title', $title );
		}
		$card = t('_twitter:card');
		if (isset($this->twitterCard) && !empty($this->twitterCard))
		{
			$card = $this->twitterCard;
		}
		if (do_mbstr('substr', $card, 0, 1) !== '_')
		{
			$meta->add( 'twitter.card', 'name', 'twitter:card', $card );
		}
/*		$audio = t('twitter:audio');
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
		$site = t('_twitter:site');
		if (isset($this->twitterSite) && !empty($this->twitterSite))
		{
			$site = $this->twitterSite;
		}
		if (do_mbstr('substr', $site, 0, 1) !== '_')
		{
			$meta->add( 'twitter.site', 'name', 'twitter:site', $site );
		}
		$description = t('_twitter:description');
		if (isset($this->twitterDesc) && !empty($this->twitterDesc))
		{
			$description = $this->twitterDesc;
		}
		if (do_mbstr('substr', $description, 0, 1) !== '_' && !$meta->has('og:description'))
		{
			$meta->add( 'twitter.description', 'name', 'twitter:description', $description );
		}
		$image = t('_twitter:image');
		if (isset($this->twitterImage) && !empty($this->twitterImage))
		{
			$la = $this->twitterImage;
		}
/*		if (is_array($image))
		{
			foreach ($image as $key => $o)
			{
				static::placeImage($o, $key);
			}
		} else*/ if (do_mbstr('substr', $image, 0, 1) !== '_')
		{
/*			static::placeImage($image);*/
			$meta->add( 'twitter.image', 'name', 'twitter:image', $image );
		}
/*		$video = t('twitter:video');
		if (mb_substr($video, 0, 1) !== '_')
		{
			Meta::add( 'twitter.video', 'property', 'twitter:video', $video );
			$url = t('twitter:video:url');	// UNUSED
			if (mb_substr($url, 0, 1) !== '_')
			{
				Meta::add( 'twitter_video_url', 'property', 'twitter:video:url', $url );
			}
			$surl = t('twitter:video:secure_url');
			if (mb_substr($alt, 0, 1) !== '_')
			{
				Meta::add( 'twitter.video.secure_url', 'property', 'twitter:video:secure_url', $surl );
			}
			$alt = t('twitter:video:alt');
			if (mb_substr($alt, 0, 1) !== '_')
			{
				Meta::add( 'twitter.video.alt', 'property', 'twitter:video:alt', $alt );
			}
			$type = t('twitter:video:type');
			if (mb_substr($type, 0, 1) !== '_')
			{
				Meta::add( 'twitter.video.type', 'property', 'twitter:video:type', $type );
			}
			$w = t('twitter:video:width');
			if (mb_substr($w, 0, 1) !== '_')
			{
				Meta::add( 'twitter.video.width', 'property', 'twitter:video:width', $w );
			}
			$h = t('twitter:video:height');
			if (mb_substr($h, 0, 1) !== '_')
			{
				Meta::add( 'twitter.video.height', 'property', 'twitter:video:height', $h );
			}
		}*/
	}
/*
	private static function placeImage($img, $key='')
	{
		$image = isset($img['url']) ? $img['url'] : $img;
		Meta::add( 'twitter.image'. $key, 'name', 'twitter:image', $image );
		if (isset($img['secure_url']))
		{
			Meta::add( 'twitter.image._secure_url'. $key, 'name', 'twitter:image:secure_url', $img['secure_url'] );
		} else {
			$surl = t('twitter:image:secure_url');
			if (mb_substr($surl, 0, 1) !== '_')
			{
				Meta::add( 'twitter.image.secure_url', 'name', 'twitter:image:secure_url', $surl );
			}
		}
		if (isset($img['alt']))
		{
			Meta::add( 'twitter.image.alt'. $key, 'name', 'twitter:image:alt', $img['alt'] );
		} else {
			$alt = t('twitter:image:alt');	// A description of what is in the image
			if (mb_substr($alt, 0, 1) !== '_')
			{
				Meta::add( 'twitter.image.alt', 'name', 'twitter:image:alt', $alt );
			}
		}
		if (isset($img['type']))
		{
			Meta::add( 'twitter.image.type'. $key, 'name', 'twitter:image:type', $img['type'] );
		} else {
			$type = t('twitter:image:type');	// MIME
			if (mb_substr($type, 0, 1) !== '_')
			{
				Meta::add( 'twitter.image.type', 'name', 'twitter:image:type', $type );
			}
		}
		if (isset($img['width']))
		{
			Meta::add( 'twitter.image.width'. $key, 'name', 'twitter:image:width', $img['width'] );
		} else {
			$w = t('twitter:image:width');	// The number of pixels
			if (mb_substr($w, 0, 1) !== '_')
			{
				Meta::add( 'twitter.image.width', 'name', 'twitter:image:width', $w );
			}
		}
		if (isset($img['height']))
		{
			Meta::add( 'twitter.image.height'. $key, 'name', 'twitter:image:height', $img['height'] );
		} else {
			$h = t('twitter:image:height');	// The number of pixels
			if (mb_substr($h, 0, 1) !== '_')
			{
				Meta::add( 'twitter.image.height', 'name', 'twitter:image:height', $h );
			}
		}
	}*/

}
