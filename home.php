<?php /*<meta http-equiv="refresh" content="0;url=home.php"> */ ?>
<?php

namespace Yuma;
//use Yuma\HTML\Doc;
//use Yuma\HTML\Link;
//use Yuma\HTML\Meta;
//use Yuma\HTML\Breadcrumbs;

	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
/*
<body style="height:100%;margin:0;padding:0;">
	<iframe width=100% height=100% src="home.php"></iframe>
</body>
*
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: home.php');
	die();
 */

//	Link::style(Array( 'name' => "home", 'href' => "css/style.css", 'version' => filemtime("css/style.css") ));
	echo makeHead( t("_Welcome to BuildForm"), "home" );
?>
	<div class="panel split2" style="display: inline-flex;">
		<div id="pane1" class="pane of2">
<?php
	//echo '<--'. $langArray['New']. '-->'. "\n";
//	echo '<--'. Lang::getInstance()->getLoad(). ' : '. t('New'). '-->'. "\n";
//list all databases
/*
$results = $db->run('SHOW DATABASES')->fetchAll();
$menu = new ButtonMenu([], 1);
foreach( $results as $row ) {
	//echo print_r( $row, true ). "\n";
//	echo $row[0]. "\n";
	$menu->add( new Button( $row[0], "", 2 ) );
}
echo $menu->HTML();
*/
//show mysql.users table
/*
$results = $db->run('SELECT * FROM mysql.user')->fetchAll();
foreach( $results as $key => $value ) {
	if (!is_array($value)) {
		echo "{$key} = {$value}\n";
	} else {
		echo "({$key})\n";
		foreach( $value as $k => $v ) {
			echo "{$k} = {$v}, ";
		}
		echo "\n";
	}
}
*/
	//public function __construct( array $btns, bool $newlines=true, int $indents=1, bool $canAdd=false, string $wrap='', string $class="" ) {
/*	$menu = new ButtonMenu(Array(
		'content' => Array(
			new Button(Array(
				'text' => t('New'),
				'slug' => 'begin',
				'desc' => t('new_desc'),
				'title' => t('new_desc'),
				'indents' => 3)),
			new Button(Array(
				'text' => t('Open...'),
				'slug' => 'use',
				'desc' => t('use_desc'),
				'indents' => 3)),
			new Button(Array(
				'text' => t('Migrate...'),
				'slug' => 'migrate',
				'desc' => t('migrate_desc'),
				'indents' => 3)),
			new Button(Array(
				'text' => t('Remove...'),
				'slug' => 'remove',
				'desc' => t('remove_desc'),
				'indents' => 3)),
			new Button(Array(
				'text' => t('Settings'),
				'slug' => '?load=settings',
				'desc' => t('settings_desc'),
				'indents' => 3)),
		),
		'newlines' => true,
		'indents' => 2,
		'canAdd' => false,
		'wrap' => 'div class="nav"',
		'class' => ''));
		//], true, 1, false, 'div class="homeNav"', '');
		//])->HTML();
	echo $menu->HTML();*/
	$buttons = Array(
			Array(
				'text' => t('New'),
				'slug' => BASE. 'database/'. ADD_DB,
				'desc' => t('new_desc'),
				//'title' => t('new_desc'),
			),
			Array(
				'text' => t('Open...'),
				'slug' => BASE. 'databases',
				'desc' => t('use_desc'),
				'indents' => 3,
			),
/*			Array(
				'text' => t('Migrate...'),
				'slug' => 'migrate',
				'desc' => t('migrate_desc'),
				'indents' => 3,
			),*/
			Array(
				'text' => t('Remove...'),
				'slug' => 'remove',
				'desc' => t('remove_desc'),
				'indents' => 3,
			),
			Array(
				'text' => t('Settings'),
				'slug' => '?load=settings',
				'desc' => t('settings_desc'),
				'indents' => 3,
			),
		);
?>
			<div class="nav">
				<ul class="btnMenu vert">
<?php
	foreach($buttons as $btn) {
?>
					<a href="<?php echo $btn['slug']; ?>"<?php if (isset($btn['title']) && !empty($btn['title'])) { ?> title="<?php echo $btn['title']; ?>"<?php } ?>><li><?php echo $btn['text']; ?></li></a></li>
<?php
	}
?>
				</ul>
			</div>
		</div>
		<div class="gutter">
		</div>
		<div id="pane2" class="pane of2">
			<!--<h3><?php l('_Recents'); ?></h3>-->
			<table id="recents" class="zebra">
				<caption><?php l('_Recents'); ?></caption>
				<tbody>
<?php /* use ButtonMenu instead add size, fillWithBlanks */
global $db;
	$qry = 'USE buildForms';
	try {
		$db->run($qry);
		$recents = $db->run('SELECT * FROM recent')->fetchAll();
		//$recents = $db->run('SELECT * FROM buildForms.recent')->fetchAll();
//		$recents = $db->query('SELECT * FROM recent')->fetchAll();
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::warning,
			'message' => t("_Cannot retrieve recents"),
			'details' => implode(' - ', (array)$e->errorInfo). "<br><p>query - {$qry}</p>",
		));
		echo $note->display();
	}
	//$recents = Array(/*"1", "22222"*/);
	if (is_array($recents)) {
		foreach($recents as $k => $recent) {
		//echo "\t\t\t\t\t". '<tr><td><a href="'. $recent. '</td></tr>'. "\n";
?>
					<tr><td><a href="<?php echo BASE. 'database/'. addPrefix($recent['name']); ?>"><?php echo $recent['name']; ?></a></td></tr>
<?php
		}
		$i=count($recents);
	} else {
		$i = 0;
	}
	for(; $i<5; $i++) {
		//echo "\t\t\t\t\t". '<tr><td> &nbsp; </td></tr>'. "\n";
/*		if (empty($recents) && $i == 0) {
?>
					<tr><td><span class='empty'><?php l('Recents'); ?></span></td></tr>
<?php
		} else {*/
?>
					<tr><td>&nbsp;</td></tr>
<?php
		/*}*/
	}

/*	$recents = new ButtonMenu(Array(
		'content' => Array(),
		'newlines' => true,
		'indents' => 4,
		'canAdd' => false,
		//'wrap' => 'tr',
		'size' => 5,
		'fillBlanks' => true,
		'class' => ''));
	echo $recents->HTML();*/
?>
				</tbody>
			</table>
		</div>
	</div>
