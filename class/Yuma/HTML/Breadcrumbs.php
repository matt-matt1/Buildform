<?php

namespace Yuma\HTML;
use function Yuma\t;
use function Yuma\enquote;
use function Yuma\delPrefix;

class Breadcrumbs //extends Singleton
{
//	protected static $instance;
	private static $larray = array();
	private static $rarray = array();
//	private static $div = '>';//'≻'SUCCEEDS\227B
//	private static $div = '≻';//SUCCEEDS\227B
	private static $div = '❯';//HEAVY RIGHT-POINTING ANGLE QUOTATION MARK ORNAMENT\276F
	//private static $wrap = 'p class="breadcrumbs"';
	//private static $wrap = 'ul class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList"';
	private static $wrap = 'ol class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList"';
	//private static $wrap = 'nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb"';
	//private static $wrap = 'ul class="cd-breadcrumb triangle xcustom-icons" itemscope itemtype="https://schema.org/BreadcrumbList"';
	private static $itemWrap = 'li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"';
	//private static $itemEnd = 'meta itemprop="position" content="1" /';
	private static $itemEnd = 'meta itemprop="position" content="%s" /';
	//private static $encase = '<div id="pane1" class="pane">'."\n".'<nav class="navbar navbar-expand-lg navbar-light bg-light">'."\n".'				<div class="container-fluid" <nav="" aria-label="breadcrumb">';
	private static $encase = '<nav class="navbar navbar-expand-lg navbar-light bg-light">'."\n".'<div class="container-fluid">'. "\n". '<nav class="breadcrumbs" aria-label="breadcrumb">';
	//private static $close = '</div></nav></div>';
	private static $close = '</nav></div></nav>';
	protected static $param;

/*
	public function __construct(array $params=null)
	{
		global $metaHTML;
		if (!isset($metaHTML) || empty($metaHTML)) {
			$metaHTML = array();
		}
		if (isset($params) && !empty($params)) {
			$this->add($params);
		}
	}
*/
	public static function getDiv() {
		return static::$div;
	}

	public static function setDiv(string $div) {
		static::$div = $div;
	}

	public static function getWrap() {
		return static::$wrap;
	}

	public static function setWrap(string $tagInner) {
		static::$wrap = $tagInner;
	}

	public static function getCrumbs() {
		return static::$array;
	}

	//public static function add([Breadcrumb] $crumbs) {
	public static function add(array $crumbs) {
		static::$larray = array_merge(static::$larray, $crumbs);
	}

	public static function radd(array $crumbs) {
		static::$rarray = array_merge(static::$rarray, $crumbs);
	}

	public static function out() {
		if (isset(static::$encase) && !empty(static::$encase)) {
			$out = static::$encase;
		}
		//$out = '<div class="breadcrumbs-left">';
		$out .= "\t".'<' . trim(static::$wrap, '<>') . '>'. "\n";
		$out .= static::draw();
		$out .= "\t".'</';
		$out .= trim(strtok(static::$wrap, ' '), '</');
		$out .= '>'. "\n";
//		$out .= '</div>'. "\n";
/*		if (!empty(static::$rarray))
		{
			$out .= '<div class="breadcrumbs-right">';
			$out .= "\t".'<' . trim(static::$wrap, '<>') . '>'. "\n";
			$out .= static::rdraw();
			$out .= "\t".'</';
			$out .= trim(strtok(static::$wrap, ' '), '</');
			$out .= '>'. "\n";
			$out .= '</div>'. "\n";
		}*/
			//$out .= "\t".'</';
			//$out .= trim(strtok(static::$wrap, ' '), '</');
			//$out .= '>'. "\n";
		if (isset(static::$close) && !empty(static::$close)) {
			$out .= static::$close;
		}
		return $out;
	}

	private static function draw() {
		$out = '';//'<span class="breadcrubs-left">';
		$llast = count(static::$larray) - 1;
		$i = 0;
		//echo '<pre>'. print_r(static::$larray, true). '</pre>';
		foreach(static::$larray as $bc) {
			if (isset($bc->param['right']) && $bc->param['right'])
			{
				--$llast;
			}
		}
		//echo '<!-- last='. $llast. ' -->';
		foreach(static::$larray as $bc) {
			$b = $bc->param;
		//	$out .= '<!--<pre>'. print_r($b, true). '</pre>-->';
//			if (isset($b['text']) && !empty($b['text'])) {
			$out .= '<'. trim(static::$itemWrap, '<>');
			$out .= ' class="';
//			$out .= 'breadcrumb-item';
			if (isset($b['class']) && !empty($b['class'])) {
				$out .= ' '. $b['class'];
//			} else {
//				$out .= ' class="breadcrumb-item';
			}
			if ($llast == $i)
			{
				$out .= ' active';
			}
			if (isset($b['right']) && $b['right'])
			{
				$out .= ' right';
			//	$out .= ' style="float: right;';
			}
			$out .= '"';
			if ($llast == $i)
			{
				$out .= 'aria-current="page"';
			}
			$out .= '>';
//			}
			if (isset($b['link']) && !empty($b['link']) && ($llast > $i || (isset($b['highlight']) && $b['highlight']))) {
				$out .= '<a href="'. trim($b['link']). '"';
			} else {
				$out .= '<span';
			}
			$out .= ' itemprop="item"';
			if (isset($b['id']) && !empty($b['id'])) {
				$out .= ' id="'. $b['id']. '"';
			}
			if (isset($b['title']) && !empty($b['title'])) {
				$out .= ' title="'. $b['title']. '"';
			}
			$out .= ' class="breadcrumb';
			//$out .= ' last-'. $llast;
			if (isset($b['linkClass']) && !empty($b['linkClass'])) {
				$out .= ' '. $b['class'];
			}
			$out .= '">';
			if (isset($b['svg_b']) && !empty($b['svg_b'])) {
				$out .= '<svg viewBox="0 0 10 40"><path fill-rule="evenodd" d="M10 20L0 0v40z"></path></svg>';
			}
			$out .= '<span itemprop="name">';
			if (isset($b['icon']) && !empty($b['icon'])) {
				$out .= $b['icon'];
			}
			if (isset($b['text']) && !empty($b['text'])) {
				$out .= $b['text'];
			}
			if ((!isset($b['icon']) || empty($b['icon'])) && (!isset($b['text']) || empty($b['text']))) {
				$out .= '['. $b['id']. ']';
			}
			$out .= '</span>';
			if (isset($b['svg_a']) && !empty($b['svg_a'])) {
				$out .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 40" src="/gfx/svg/triangle.svg"><path fill-rule="evenodd" d="M10 20L0 0v40z"></path></svg>';
			}
			$out .= (isset($b['link']) && !empty($b['link']) && ($llast > $i || (isset($b['highlight']) && $b['highlight']))) ? '</a>' : '</span>';
			//$out .= '<'. trim(static::$itemEnd, '<>'). '>';
			if (strpos(static::$itemEnd, '%s') !== false)
			{
				$out .= '<'. sprintf( trim(static::$itemEnd, '<>'), $i+1 ). '>';
			} else {
				$out .= '<'. trim(static::$itemEnd, '<>'). '>';
			}
			$out .= '</'. trim(strtok(static::$itemWrap, ' '), '</'). '>'. "\n";
/*			if ($llast > $i) {
				//$out .= static::$div;
				$out .= '<span class="div"></span>';
	//			}
		}*/
			$i++;
		}
		return $out/*. '</span>'*/;
	}

	private static function rdraw() {
		$out = '';//'<span class="breadcrumbs-right">';
		$rlast = count(static::$rarray) - 1;
		$i = 0;
		//echo '<pre>'. print_r(static::$rarray, true). '</pre>';
		foreach(static::$rarray as $bc) {
			$b = $bc->param;
/*			if (isset($b['highlight']) && $b['highlight'])
			{
				$last = $last - 2;
			}*/
			//$out .= '<pre>'. print_r($b, true). '</pre>';
//			if (isset($b['text']) && !empty($b['text'])) {
				$out .= '<'. trim(static::$itemWrap, '<>'). '>';
//			}
				if (isset($b['link']) && !empty($b['link']) /*&& $rlast > $i*/) {
					$out .= '<a href="'. trim($b['link']). '"';
				} else {
					$out .= '<span';
				}
				if (isset($b['id']) && !empty($b['id'])) {
					$out .= ' id="'. $b['id']. '"';
				}
				if (isset($b['title']) && !empty($b['title'])) {
					$out .= ' title="'. $b['title']. '"';
				}
				if (isset($b['class']) && !empty($b['class'])) {
					$out .= ' class="last'. $rlast. ' '. $b['class']. '"';
				}
				$out .= '>';
			if (isset($b['icon']) && !empty($b['icon'])) {
				$out .= $b['icon'];
			}
			if (isset($b['text']) && !empty($b['text'])) {
				$out .= $b['text'];
			}
				$out .= (isset($b['link']) && !empty($b['link']) /*&& $rlast > $i*/) ? '</a>' : '</span>';
				//$out .= '<'. trim(static::$itemEnd, '<>'). '>';
				if (strpos(static::$itemEnd, '%s') !== false)
				{
					$out .= '<'. sprintf( trim(static::$itemEnd, '<>'), $i+1 ). '>';
				} else {
					$out .= '<'. trim(static::$itemEnd, '<>'). '>';
				}
				$out .= '</'. trim(strtok(static::$itemWrap, ' '), '</'). '>'. "\n";
				if ($rlast > $i) {
					//$out .= static::$div;
					$out .= '<span class="div"></span>';
	//			}
			}
			$i++;
		}
		return $out/*. '</span>'*/;
	}

	/**
	 * Sets breadcrumbis (in-place if the condition is true)
	 * @param $crumbs array	Containing the breadcrumb(s) to be added
	 * @param $condition	Condition for adding the above breadcrumb
	 */
	public static function register(array $crumbs, $condition=true) {
		if ($condition) {
			static::add($crumbs);
		}
	}

	/**
	 * Sets all the breadcrumbs for this application
	 */
	public static function here() {
/*		$page = $_SERVER['PHP_SELF'];
		$currentpage = ucwords(str_replace("-"," ",(basename($page,".php"))));
		$currentdir = ucwords(basename(dirname($page)));
		$topdir = basename(dirname(dirname($page)));
		echo '<!-- page='. $page. ', currentpage='. $currentpage. ', currentdir='. $currentdir. ', topdir='. $topdir. '. -->';*/
/*		$text = t('_'. ucwords($_GET['page']));
		if ($text[0] === '_') {*/
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'dbs',
					'class' => "breadcrumb-item",
					'text' => t('_Databases'),
					'title' => t('_Databases'),
					'link' => BASE. 'databases',
					'linkClass' => 'btn-breadcrumb btn',
					//'class' => ''
				)),
			));
/*		} else {
			static::add(array(
				new Breadcrumb(Array(
					'id' => $_GET['page'],
					'text' => t('_'. ucwords($_GET['page'])),
					'title' => t('_'. ucwords($_GET['page'])),
					'link' => BASE. '/'. $_GET['page'],
//					'linkClass' => 'btn-breadcrumb btn',
					//'class' => ''
				)),
			));
		}*/
//		static::add(array(
			//new Breadcrumb('id', 'text', 'title', 'link'),
/*			new Breadcrumb(Array(
				'id' => 'home',
				'class' => 'breadcrumb-item',
				//'text' => t('_Home'),
				//'text' => ' ',
				'title' => t('_Home'),
				'link' => BASE. '/',
				//'linkClass' => 'breadcrumb',
				//'icon' => '<span class="symbol home"></span>',
				'icon' => '<i class="fa fa-home nofa-2x" aria-hidden="true"></i>',
				'linkClass' => 'btn-breadcrumb btn',
				//'class' => 'symbol home')),
		)),*/
			/*new Breadcrumb(Array(
				'id' => 'dbs',
				'text' => t('_Databases'),
				'title' => t('_Databases'),
				'link' => BASE. '/databases',
				'linkClass' => 'btn-breadcrumb btn',
				//'class' => ''
			)),*/
//			new Breadcrumb(Array(
//				'id' => 'dbs',
//				'text' => t('_'. ucwords($_GET['page'])),
//				'title' => t('_'. ucwords($_GET['page'])),
//				'link' => BASE. '/'. $_GET['page'],
////				'linkClass' => 'btn-breadcrumb btn',
				//'class' => ''
//			)),
//		));
		if ((isset($_GET['database']) && !empty($_GET['database'])) /*|| isset($_GET[ADD_DB])*/ /*&& $_GET['database'] !== $GLOBALS['data']['ADD_DB']*/) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'database_'. $_GET['database'],
					'class' => "breadcrumb-item",
					'text' => ($_GET['database'] === ADD_DB) ? delPrefix(strtok($_GET['database'], '?')) : enquote(delPrefix(strtok($_GET['database'], '?'))),
					//'text' => ($_GET['database'] === $GLOBALS['data']['ADD_DB']) ? delPrefix(strtok($_GET['database'], '?')) : enquote(delPrefix(strtok($_GET['database'], '?'))),
					'title' => ($_GET['database'] === ADD_DB) ? ADD_DB : sprintf( t('_Database %s!'), delPrefix(strtok($_GET['database'], '?'))),
					//'title' => ($_GET['database'] === $GLOBALS['data']['ADD_DB']) ? $GLOBALS['data']['ADD_DB'] : sprintf( t('_Database %s!'), delPrefix(strtok($_GET['database'], '?'))),
					//'link' => BASE. '/database/'. delPrefix($_GET['database']),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?'),
					//'class' => ''
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		if (isset($_GET['databases']) && $_GET['databases'] == ADD_DB) {
			$parts = str_split(ADD_DB);//explode('', ADD_DB);
			$first = strtoupper($parts[0]);
			array_shift($parts);
			$rest = strtolower(implode('', $parts));
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'add_database',
					'class' => "breadcrumb-item",
					'text' => $first. $rest,
					'title' => $first. $rest,
				)),
			));
		}
		if (isset($_GET['tables']) /*&& !empty($_GET['tables'])*/) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'tabs',
					'class' => "breadcrumb-item",
					'text' => t('_Tables'),
					'title' => t('_Tables'),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?')/*. '/tables/'*/,
					//'class' => 'symbol dbtabs'
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		if (isset($_GET['table']) /*|| isset($_GET['tables'])*//*&& $_GET['table'] !== ADD_TBL*/) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'tabs',
					'class' => "breadcrumb-item",
					'text' => t('_Tables'),
					'title' => t('_Tables'),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?')/*. '/tables/'*/,
					//'class' => 'symbol dbtabs'
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		if (isset($_GET['table'])) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'table_'. $_GET['table'],
					'class' => "breadcrumb-item",
					'text' => ($_GET['table'] === ADD_TBL) ? strtok($_GET['table'], '?') : enquote(strtok($_GET['table'], '?')),
					//'text' => ($_GET['table'] === $GLOBALS['data']['ADD_TBL']) ? strtok($_GET['table'], '?') : enquote(strtok($_GET['table'], '?')),
					'title' => ($_GET['table'] === ADD_TBL) ? ADD_TBL : sprintf( t('_Table %s'), strtok($_GET['table'], '?')),
					//'title' => ($_GET['table'] === $GLOBALS['data']['ADD_TBL']) ? $GLOBALS['data']['ADD_TBL'] : sprintf( t('_Table %s'), strtok($_GET['table'], '?')),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?'). '/table/'. strtok($_GET['table'], '?'),
					//'class' => ''
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
			if (isset($_GET[ADD_ROW])) {
				$parts = str_split(ADD_ROW);//explode('', ADD_DB);
				$first = strtoupper($parts[0]);
				array_shift($parts);
				$rest = strtolower(implode('', $parts));
				static::add(array(
					new Breadcrumb(Array(
						'id' => 'add_record',
						'class' => "breadcrumb-item",
						'text' => $first. $rest,
						'title' => $first. $rest,
					)),
				));
			}
		}
		if (isset($_GET['tables']) && $_GET['tables'] == ADD_TBL) {
			$parts = str_split(ADD_TBL);//explode('', ADD_DB);
			$first = strtoupper($parts[0]);
			array_shift($parts);
			$rest = strtolower(implode('', $parts));
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'add_table',
					'class' => "breadcrumb-item",
					'text' => $first. $rest,
					'title' => $first. $rest,
				)),
			));
		}
		if (isset($_GET['forms']) /*&& !empty($_GET['tables'])*/) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'forms',
					'class' => "breadcrumb-item",
					'text' => t('_Forms'),
					'title' => t('_Forms'),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?')/*. '/tables/'*/,
					//'class' => 'symbol dbtabs'
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		if (isset($_GET['form']) /*&& $_GET['table'] !== ADD_TBL*/) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'forms',
					'class' => "breadcrumb-item",
					'text' => t('_Forms'),
					'title' => t('_Forms'),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?')/*. '/tables/'*/,
					//'class' => 'xsymbol dbforms'
				'linkClass' => 'btn-breadcrumb btn',
				)),
				new Breadcrumb(Array(
					'id' => 'form_'. $_GET['form'],
					'class' => "breadcrumb-item",
					'text' => ($_GET['form'] === ADD_FRM) ? strtok($_GET['form'], '?') : enquote(explode('?', $_GET['form'])[0]),
					//'text' => ($_GET['form'] === $GLOBALS['data']['ADD_FRM']) ? strtok($_GET['form'], '?') : enquote(explode('?', $_GET['form'])[0]),
					'title' => ($_GET['form'] === ADD_FRM) ? ADD_FRM : sprintf( t('_Form %s'), explode('?', $_GET['form'])[0]),
					//'title' => ($_GET['form'] === $GLOBALS['data']['ADD_FRM']) ? $GLOBALS['data']['ADD_FRM'] : sprintf( t('_Form %s'), explode('?', $_GET['form'])[0]),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?'). '/form/'. strtok($_GET['form'], '?'),
					//'class' => ''
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		if (isset($_GET['forms']) && $_GET['forms'] == ADD_FRM) {
			$parts = str_split(ADD_FRM);//explode('', ADD_DB);
			$first = strtoupper($parts[0]);
			array_shift($parts);
			$rest = strtolower(implode('', $parts));
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'add_form',
					'class' => "breadcrumb-item",
					'text' => $first. $rest,
					'title' => $first. $rest,
				)),
			));
		}
		if (isset($_GET['columns']) || (isset($_GET['page']) && $_GET['page'] == 'columns')) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'cols',
					'class' => "breadcrumb-item",
					'text' => t('_Columns'),
					'title' => t('_Columns'),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?'). '/table/'. strtok($_GET['table'], '?'). '/columns/',
					//'class' => 'symbol dbcols'
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		if (isset($_GET['column']) /*|| isset($_GET['columns'])*/ /*&& $_GET['column'] !== ADD_COL*/) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'cols',
					'class' => "breadcrumb-item",
					'text' => t('_Columns'),
					'title' => t('_Columns'),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?'). '/table/'. strtok($_GET['table'], '?'). '/columns/',
					//'class' => 'symbol dbcols'
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		if (isset($_GET['column']) /*&& $_GET['table'] !== ADD_TBL*/) {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'column_'. $_GET['column'],
					'class' => "breadcrumb-item",
					'text' => ($_GET['column'] === ADD_COL) ? strtok($_GET['column'], '?') : enquote(strtok($_GET['column'], '?')),
					//'text' => ($_GET['column'] === $GLOBALS['data']['ADD_COL']) ? strtok($_GET['column'], '?') : enquote(strtok($_GET['column'], '?')),
					'title' => ($_GET['column'] === ADD_COL) ? ADD_COL : sprintf( t('_Column %s'), strtok($_GET['column'], '?')),
					//'title' => ($_GET['column'] === $GLOBALS['data']['ADD_COL']) ? $GLOBALS['data']['ADD_COL'] : sprintf( t('_Column %s'), strtok($_GET['column'], '?')),
					'link' => BASE. 'database/'. strtok($_GET['database'], '?'). '/table/'. strtok($_GET['table'], '?'). '/column/'. strtok($_GET['column'], '?'),
					//'class' => ''
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		}
		/*$text = t('_'. ucwords($_GET['page']));
		if ($text[0] !== 'Settings') {*/
		if ($_GET['page'] !== 'settings') {
			static::add(array(
				new Breadcrumb(Array(
					'id' => 'settings',
					'class' => "breadcrumb-item",
					'right' => true,
					'highlight' => true,
					//'text' => t('_Settings'),
					//'text' => '',
					//'icon' => '<i class="fa-regular fa-gear"></i>',
					'icon' => '<i class="fa-solid fa-gear"></i>',
					'title' => t('_Settings'),
					'link' => BASE. 'settings/',
					//'class' => 'symbol sett right'
				'linkClass' => 'btn-breadcrumb btn',
				)),
			));
		} else {
			static::add(array(
				new Breadcrumb(Array(
					'id' => $_GET['page'],
					'class' => "breadcrumb-item",
					'text' => t('_'. ucwords($_GET['page'])),
					'title' => t('_'. ucwords($_GET['page'])),
					'link' => BASE. $_GET['page'],
//					'linkClass' => 'btn-breadcrumb btn',
					//'class' => ''
				)),
			));
		}
			//static::radd(array(
		return static::out();
	}

}
