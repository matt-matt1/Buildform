<?php
declare(strict_types=1);

namespace Yuma\HTML\Head;
use Yuma\HTML\Head;
use Yuma\HTML\Meta;
use Yuma\HTML\Head_interface;
use function \Yuma\do_mbstr;
use function \Yuma\t;
/**
 * Open Graph tags for the head section
 * Helps you control what information shows when you post content to your Facebook page, LinkedIn, etc...
 */

/* Put the following into ./lang/<language code>.php:
	'_og:title' => "The title of the object.",
	'_og:description' => "The description of the object. eg. Proton Drive allows you to securely store and share your sensitive documents and access them anywhere.",
	'_og:url' => "The full canonical, permanent URL. eg. https://drive.proton.me/",
	'_og:type' => "website|video|music|movie|profile|...",
	'_og:image' => "The full URL for the associated image. eg. https://drive.proton.me/assets/proton-og-image.png",
	'_og:image:alt' => "Provides alternative text for the image. eg. The shiny Proton Drive logo",
	'_og:image:type' => "MIME. eg. image/png",
	'_og:image:width' => "pixels. eg. 1200",
	'_og:image:height' => "pixels. eg. 630",
	'_og:determiner' => "The word that appears before this object's title in a sentence. An enum of (a, an, the, "", auto). If auto is chosen, the consumer of your data should chose between "a" or "an". Default is "" (blank).",
	'_og:site_name' => "If your object is part of a larger web site, the name which should be displayed for the overall site. e.g., "IMDb".",
	'_og:locale' => "Defaults to en_US. eg. en_US",
	'_og:locale:alternate' => "An array of other locales this page is available in. eg." Array(
		"ca_ES",
    	"cs_CZ",
    	"da_DK",
    	"de_DE",
    	"el_GR",   	"es_LA",   	"fr_FR",   	"fr_CA",   	"hr_HR",   	"hu_HU",   	"id_ID",   	"it_IT",   	"ja_JP",   	"kab_DZ",
    	"nl_NL",   	"pl_PL",   	"pt_BR",   	"pt_PT",   	"ro_RO",   	"ru_RU",   	"tr_TR",   	"uk_UA",   	"zh_CN",	"zh_TW"
	),
	-- or -- an array for multiple -- eg. --
	'_og:image' => array(
		array(
			'url' => "https://drive.proton.me/assets/proton-og-image2.png",
			'alt' => "The shiny Proton Drive logo2",
			'type' => "image/png2",
			'width' => "12002",
			'height' => "6302",
		),
		array(
			'url' => "https://drive.proton.me/assets/proton-og-image3.png",
			'alt' => "The shiny Proton Drive logo3",
			'type' => "image/png3",
			'width' => "12003",
			'height' => "6303",
		),
	),
	'_og:audio' => array(
		array(
			'url' => "https://drive.proton.me/assets/proton-og-audio.wav",
			'alt' => "The shiny Proton Drive sound",
			'type' => "audio/wav",
//			'width' => "12002",
//			'height' => "6302",
		),
		array(
			'url' => "https://drive.proton.me/assets/proton-og-audio2.wav",
			'alt' => "The shiny Proton Drive sound2",
			'type' => "audio/wav2",
		),
	),
	'_og:video' => array(
		array(
			'url' => "https://drive.proton.me/assets/proton-og-video.jpg",
			'alt' => "The shiny Proton Drive video",
			'type' => "video/jpg",
			'width' => "1201",
			'height' => "631",
		),
		array(
			'url' => "https://drive.proton.me/assets/proton-og-video2.jpg",
			'alt' => "The shiny Proton Drive video2",
			'type' => "video/jpg2",
			'width' => "12002",
			'height' => "6302",
		),
	),
*/
class Og extends Head implements Head_interface
{
	private $done_init = false;

	/**
	 * Appends the variables for this class into the parent's data array
	 * Initializes the variables to use, calls the assinging method (prepare)
	 */
	function __construct()
	{
/*		$pre = 'Og::__construct() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
 */
		if (!$this->done_init)	// only assign values once
		{
			$this->ogTitle = "-Open Graph title-";	// real values can be assigned later - then call head's out()
			$this->ogDesc = '';
			$this->ogSite = '';
			$this->ogUrl = '';
			$this->ogType = '';
			$this->ogDeterminer = '';
			$this->ogLocale = '';
			$this->ogLocaleAlt = array();
			$this->ogAid = '';
			$this->ogImage = array();
			$this->ogVideo = array();
			$this->ogAudio = array();
			$this->done_init = true;
		}
//		parent::__construct();
		$this->prepare();
	}

	/**
	 * Prepare OG tags for the head section
	 */
	public function prepare()
	{
/*		$pre = 'Og::__prepare() ';
		$log = 'OK';
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
 */
		if (!isset($GLOBALS['data']['SHOW_OG_TAGS']) && defined('SHOW_OG_TAGS')) {
			$GLOBALS['data']['SHOW_OG_TAGS'] = SHOW_OG_TAGS;
		}
		if (/*!$this->done_hooks && isset($GLOBALS['data']['SHOW_OG_TAGS']) &&*/ !$GLOBALS['data']['SHOW_OG_TAGS']) {
		//if (!defined('SHOW_OG_TAGS') || !SHOW_OG_TAGS)
			return;
		}
		// The title of your object as it should appear within the graph
		$title = t("_og:title");
		if (do_mbstr('substr', $title, 0, 1) === '_') {
//			$title = '';
			if (isset($this->pageTitle) && !empty($this->pageTitle)) {
//				$title .= 'this-pt='. $this->pageTitle;
				$title = $this->pageTitle;
			}
			//if (isset(parent::getInstance()->pageTitle) && !empty(parent::getInstance()->pageTitle)) {
			if (isset(parent::$pageTitle) && !empty(parent::$pageTitle)) {
//			} elseif (isset(parent::getInstance()->pageTitle) && !empty(parent::getInstance()->pageTitle)) {
//				$title .= 'parent='. parent::getInstance()->pageTitle;
				//$title = parent::getInstance()->pageTitle;
				$title = parent::$pageTitle;
//			} if (isset($this->ogTitle) && !empty($this->ogTitle)) {
			} elseif (isset($this->ogTitle) && !empty($this->ogTitle)) {
//				$title .= 'this-og='. $this->ogTitle;
				$title = $this->ogTitle;
				/**/			} else {
				$title = '';
/* */			}
			//$title = implode(', ', $this->data);
		}
		if (!empty($title) /*&& !Meta::getInstance()->has('og.title')*/) {
			$meta = new Meta();
			$meta->add( 'og.title', 'property', 'og:title', $title );
		}
		// A one to two sentence description of your object
/*		$site_name = t('_og:site_name');
		if (null !== $this->og_site)
		{
			$site_name = $this->og_site;
		}
		if (mb_substr($site_name, 0, 1) !== '_')
		{
			Meta::getInstance()->add( 'og.site_name', 'property', 'og:site_name', $site_name );
		}*/
		$meta = new Meta();
		// A one to two sentence description of your object
		$description = t('_og:description');
		if (isset($this->ogDesc) && !empty($this->ogDesc))
		{
			$description = $this->ogDesc;
		}
		if (do_mbstr('substr', $description, 0, 1) !== '_')
		{
			$meta->add( 'og.description', 'property', 'og:description', $description );
		}
		// The canonical URL of your object that will be used as its permanent ID in the graph
		$url = t('_og:url');
		if (isset($this->ogUrl) && !empty($this->ogUrl))
		{
			$url = $this->ogUrl;
		}
		if (do_mbstr('substr', $url, 0, 1) !== '_')
		{
			$meta->add( 'og.url', 'property', 'og:url', $url );
		}
		// The type of your object, e.g., "video.movie". Depending on the type you specify, other properties may also be required
		if (!$meta->has('og.type')) {
			$meta->add( 'og.type', 'property', 'og:type', /*$type*/'website' );
		}

		$url = t('_og:image:url');	// UNUSED - Identical to og:image
		if (do_mbstr('substr', $url, 0, 1) !== '_' && is_string($url))
		{
			$meta->add( 'og.image.url', 'property', 'og:image:url', $url );
		}
		$image = t('_og:image');
		if (isset($this->ogImage) && !empty($this->ogImage))
		{
			$image = $this->ogImage;
		}
		if (is_array($image))
		{
			foreach ($image as $key => $img)
		//		foreach ($imgs as $img)
		//		{
					$this->placeImage($img, $key);
		//		}
		} else if (do_mbstr('substr', $image, 0, 1) !== '_')
		{
			$this->placeImage($image);
/*			{
				$surl = t('og:image:secure_url');
				if (mb_substr($alt, 0, 1) !== '_')
				{
					Meta::add( 'og.image_secure_url', 'property', 'og:image:secure_url', $surl );
				}
				$alt = t('og:image:alt');	// A description of what is in the image
				if (mb_substr($alt, 0, 1) !== '_')
				{
					Meta::add( 'og.image_alt', 'property', 'og:image:alt', $alt );
				}
				$type = t('og:image:type');	// MIME
				if (mb_substr($type, 0, 1) !== '_')
				{
					Meta::add( 'og.image_type', 'property', 'og:image:type', $type );
				}
				$w = t('og:image:width');	// The number of pixels
				if (mb_substr($w, 0, 1) !== '_')
				{
					Meta::add( 'og.image_width', 'property', 'og:image:width', $w );
				}
				$h = t('og:image:height');	// The number of pixels
				if (mb_substr($h, 0, 1) !== '_')
				{
					Meta::add( 'og.image_height', 'property', 'og:image:height', $h );
				}
			}*/
		}
		// A URL to a video file that complements this object
		$video = t('_og:video');
		if (isset($this->ogVideo) && !empty($this->ogVideo))
		{
			$video = $this->ogVideo;
		}
		if (is_array($video))
		{
			foreach ($video as $key => $o)
			{
				$this->placeVideo($o, $key);
			}
		} else if (do_mbstr('substr', $video, 0, 1) !== '_')
		{
			$this->placeVideo($video);
		}
		/*	$url = t('og:video:url');	// UNUSED
			if (mb_substr($url, 0, 1) !== '_')
			{
				Meta::add( 'og.video_url', 'property', 'og:video:url', $video );
			}*/
/*			$surl = t('og:video:secure_url');
			if (mb_substr($surl, 0, 1) !== '_')
			{
				Meta::add( 'og.video_secure_url', 'property', 'og:video:secure_url', $surl );
			}*/
		// A URL to an audio file to accompany this object
		$audio = t('_og:audio');
		if (isset($this->ogAudio) && !empty($this->ogAudio))
		{
			$audio = $this->ogAudio;
		}
		if (is_array($audio))
		{
			foreach ($audio as $key => $o)
			{
				$this->placeAudio($o, $key);
			}
		} else if (do_mbstr('substr', $audio, 0, 1) !== '_')
		{
			$this->placeAudio($audio);
		}
//			Meta::add( 'og.audio', 'property', 'og:audio', $audio );
/*			$url = t('og:audio:url');	// UNUSED
			if (mb_substr($url, 0, 1) !== '_')
			{
				Meta::add( 'og.audio_url', 'property', 'og:audio:url', $url );
			}
			$surl = t('og:audio:secure_url');
			if (mb_substr($surl, 0, 1) !== '_')
			{
				Meta::add( 'og.audio_secure_url', 'property', 'og:audio:secure_url', $surl );
			}*/
/*		$music = t('og:music');
		if (is_array($music))
		{
			foreach ($music as $key => $o)
			{
				static::placeMusic($o, $key);
			}
		} else if (mb_substr($music, 0, 1) !== '_')
		{
			static::placeMusic($music);
		}*/
		// If your object is part of a larger web site, the name which should be displayed for the overall site
		$site_name = t('_og:site_name');
		if (isset($this->ogSite) && !empty($this->ogSite))
		{
			$site_name = $this->ogSite;
		}
		if (do_mbstr('substr', $site_name, 0, 1) !== '_')
		{
			$meta->add( 'og.site_name', 'property', 'og:site_name', $site_name );
		}
		// The word that appears before this object's title in a sentence. An enum of (a, an, the, "", auto)
		$determiner = t('_og:determiner');
		if (isset($this->ogDeterminer) && !empty($this->ogDeterminer))
		{
			$determiner = $this->ogDeterminer;
		}
		if (do_mbstr('substr', $determiner, 0, 1) !== '_')
		{
			$meta->add( 'og.determiner', 'property', 'og:determiner', $determiner );
		}
		// The locale these tags are marked up in. Of the format language_TERRITORY. Default is en_US
		$l = t('_og:locale');
		if (isset($this->ogLocale) && !empty($this->ogLocale))
		{
			$locale = $this->ogLocale;
		}
		if (do_mbstr('substr', $l, 0, 1) !== '_')
		{
			$meta->add( 'og.locale', 'property', 'og:locale', $l );
		}
		// An array of other locales this page is available in
		$la = t('_og:locale:alternate');
		if (isset($this->ogLocaleAlt) && !empty($this->ogLocaleAlt))
		{
			$la = $this->ogLocaleAlt;
		}
		if (is_array($la))
		{
			foreach ($la as $la)
			{
				$meta->add( 'og.locale.alternate'. $la, 'property', 'og:locale:alternate', $la );
			}
		} else {
			if (do_mbstr('substr', $la, 0, 1) !== '_')
			{
				$meta->add( 'og.locale.alternate'. $la, 'property', 'og:locale:alternate', $la );
			}
		}
		$aid = t('_fb:app_id');
		if (isset($this->ogAip) && !empty($this->ogAip))
		{
			$aid = $this->ogAid;
		}
		if (do_mbstr('substr', $aid, 0, 1) !== '_')
		{
			$meta->add( 'fb.app_id', 'property', 'fb:app_id', $aid );
		}
	}

	private function placeImage($img, $key='')
	{
		$image = isset($img['url']) ? $img['url'] : $img;
		$meta = new Meta();
		$meta->add( 'og.image'. $key, 'property', 'og:image', $image );
		if (isset($img['secure_url']))
		{
			$meta->add( 'og.image._secure_url'. $key, 'property', 'og:image:secure_url', $img['secure_url'] );
		} else {
			$surl = t('_og:image:secure_url');
			if (do_mbstr('substr', $surl, 0, 1) !== '_')
			{
				$meta->add( 'og.image.secure_url', 'property', 'og:image:secure_url', $surl );
			}
		}
		if (isset($img['alt']))
		{
			$meta->add( 'og.image.alt'. $key, 'property', 'og:image:alt', $img['alt'] );
		} else {
			$alt = t('_og:image:alt');	// A description of what is in the image
			if (do_mbstr('substr', $alt, 0, 1) !== '_')
			{
				$meta->add( 'og.image.alt', 'property', 'og:image:alt', $alt );
			}
		}
		if (isset($img['type']))
		{
			$meta->add( 'og.image.type'. $key, 'property', 'og:image:type', $img['type'] );
		} else {
			$type = t('_og:image:type');	// MIME
			if (do_mbstr('substr', $type, 0, 1) !== '_')
			{
				$meta->add( 'og.image.type', 'property', 'og:image:type', $type );
			}
		}
		if (isset($img['width']))
		{
			$meta->add( 'og.image.width'. $key, 'property', 'og:image:width', $img['width'] );
		} else {
			$w = t('_og:image:width');	// The number of pixels
			if (do_mbstr('substr', $w, 0, 1) !== '_')
			{
				$meta->add( 'og.image.width', 'property', 'og:image:width', $w );
			}
		}
		if (isset($img['height']))
		{
			$meta->add( 'og.image.height'. $key, 'property', 'og:image:height', $img['height'] );
		} else {
			$h = t('_og:image:height');	// The number of pixels
			if (do_mbstr('substr', $h, 0, 1) !== '_')
			{
				$meta->add( 'og.image.height', 'property', 'og:image:height', $h );
			}
		}
	}

	private function placeAudio($obj, $key='')
	{
		$audio = isset($obj['url']) ? $obj['url'] : $obj;
		$meta = new Meta();
		$meta->add( 'og.audio'. $key, 'property', 'og:audio', $audio );
		if (isset($obj['secure_url']))
		{
			$meta->add( 'og.audio.secure_url'. $key, 'property', 'og:audio:secure_url', $obj['secure_url'] );
		} else {
			$surl = t('_og:audio:secure_url');
			if (do_mbstr('substr', $surl, 0, 1) !== '_')
			{
				$meta->add( 'og.audio.secure_url', 'property', 'og:audio:secure_url', $surl );
			}
		}
		if (isset($obj['type']))
		{
			$meta->add( 'og.audio.type'. $key, 'property', 'og:audio:type', $obj['type'] );
		} else {
			$type = t('_og:audio:type');
			if (do_mbstr('substr', $type, 0, 1) !== '_')
			{
				$meta->add( 'og.audio.type', 'property', 'og:audio:type', $type );
			}
		}
	}

	private function placeVideo($obj, $key='')
	{
		$video = isset($obj['url']) ? $obj['url'] : $obj;
		$meta = new Meta();
		$meta->add( 'og.video'. $key, 'property', 'og:video', $video );
		if (isset($obj['secure_url']))
		{
			$meta->add( 'og.video.secure_url'. $key, 'property', 'og:video:secure_url', $obj['secure_url'] );
		} else {
			$surl = t('_og:video:secure_url');
			if (do_mbstr('substr', $surl, 0, 1) !== '_')
			{
				$meta->add( 'og.video.secure_url', 'property', 'og:video:secure_url', $surl );
			}
		}
		if (isset($obj['alt']))
		{
			$meta->add( 'og.video.alt'. $key, 'property', 'og:video:alt', $obj['alt'] );
		} else {
			$alt = t('_og:video:alt');
			if (do_mbstr('substr', $alt, 0, 1) !== '_')
			{
				$meta->add( 'og.video.alt', 'property', 'og:video:alt', $alt );
			}
		}
		if (isset($obj['type']))
		{
			$meta->add( 'og.video.type'. $key, 'property', 'og:video:type', $obj['type'] );
		} else {
			$type = t('_og:video:type');
			if (do_mbstr('substr', $type, 0, 1) !== '_')
			{
				$meta->add( 'og.video.type', 'property', 'og:video:type', $type );
			}
		}
		if (isset($obj['width']))
		{
			$meta->add( 'og.video.width'. $key, 'property', 'og:video:width', $obj['width'] );
		} else {
			$w = t('_og:video:width');
			if (do_mbstr('substr', $w, 0, 1) !== '_')
			{
				$meta->add( 'og.video.width', 'property', 'og:video:width', $w );
			}
		}
		if (isset($obj['height']))
		{
			$meta->add( 'og.video.height'. $key, 'property', 'og:video:height', $obj['height'] );
		} else {
			$h = t('_og:video:height');
			if (do_mbstr('substr', $h, 0, 1) !== '_')
			{
				$meta->add( 'og.video.height', 'property', 'og:video:height', $h );
			}
		}
	}

/*	private static function placeMusic($obj, $key='')
	{
		$music = isset($obj['url']) ? $obj['url'] : $obj;
		Meta::add( 'og.music'. $key, 'property', 'og:music', $music );
		if (isset($obj['release_date']))
		{
			Meta::add( 'og.music.release_date'. $key, 'property', 'og:music:release_date', $obj['release_date'] );
		} else {
			$surl = t('og:music:release_date');
			if (mb_substr($surl, 0, 1) !== '_')
			{
				Meta::add( 'og.music.release_date', 'property', 'og:music:release_date', $surl );
			}
		}
		if (isset($obj['creator']))
		{
			Meta::add( 'og.music.creator'. $key, 'property', 'og:music:creator', $obj['creator'] );
		} else {
			$surl = t('og:music:creator');
			if (mb_substr($surl, 0, 1) !== '_')
			{
				Meta::add( 'og.music.creator', 'property', 'og:music:creator', $surl );
			}
		}
		if (isset($obj['song']))
		{
			Meta::add( 'og.music.song'. $key, 'property', 'og:music:song', $obj['song'] );
		} else {
			$song = t('og:music:song');
			if (mb_substr($song, 0, 1) !== '_')
			{
				Meta::add( 'og.music.song', 'property', 'og:music:song', $song );
			}
		}
		if (isset($obj['musician']))
		{
			Meta::add( 'og.music.musician'. $key, 'property', 'og:music:musician', $obj['musician'] );
		} else {
			$musician = t('og:music:musician');
			if (mb_substr($musician, 0, 1) !== '_')
			{
				Meta::add( 'og.music.musician', 'property', 'og:music:musician', $musician );
			}
		}
		if (isset($obj['album']))
		{
			Meta::add( 'og.music.album'. $key, 'property', 'og:music:album', $obj['album'] );
		} else {
			$w = t('og:music:album');
			if (mb_substr($w, 0, 1) !== '_')
			{
				Meta::add( 'og.music.album', 'property', 'og:music:album', $w );
			}
		}
		if (isset($obj['duration']))
		{
			Meta::add( 'og.music.duration'. $key, 'property', 'og:music:duration', $obj['duration'] );
		} else {
			$d = t('og:music:duration');
			if (mb_substr($h, 0, 1) !== '_')
			{
				Meta::add( 'og.music.duration', 'property', 'og:music:duration', $d );
			}
		}
	}*/

/*	'og:music' => array(
		array(
			'duration' => "d_uration",	// song's length in seconds
			'album' => "albu_m",	// album this song is from
			'musician' => "mu_sician",	// musician that made this song
			'song' => "son_g",	// song on this album
			'release_date' => "r_elease_dat",	// date the album was released
			'creator' => "creato_r",	// creator of this playlist
		),
		array(
			'' => "",
		),
),*/

}
