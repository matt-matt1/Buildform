<?php
namespace Yuma;
use \PDO;
use Yuma\HTML\Breadcrumbs;
use Yuma\HTML\Scripts;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	if ( !isset($_GET['database']) ) {
		Redirect( BASE. 'databases' );
	}
	if ( isset($_GET['database']) && !isset($_GET['form']) ) {
		Redirect( BASE. 'database/'. $_GET['database'] );
	}
	$dbname = $_GET['database'];
	// *****
	// Pressed OK (after form is created) - user acknowledged notice
	// *****
	if (isset($_POST['pressed']) && $_POST['pressed'] === 'ok') {	// list all databases, if select ok from notice
		Redirect( BASE. 'database/'. $dbname );
	}
	if (isset($_POST['pressed']) && $_POST['pressed'] === 'ignore') {
/*		if (isset($_POST['fname']))
			$form = $_POST['fname'];
		if (empty($form) && isset($_GET['form']))
			$form = $_GET['form'];*/
		$form = ((isset($_POST['fname'])) ? $_POST['fname'] : (isset($_GET['form']))) ? $_GET['form'] : '';
		Redirect( BASE. 'database/'. $dbname. '/form/'. $form );
	}
	$scr = new Scripts;
	echo makeHead(array( 'page_title' => t('_frm_desc'), 'body_id' => 'form', 'body_class' => "", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
?>
	<!-- begin page contents -->
	<div class="container">
		<div class="panel">
<?php
/*****************************************************************************
 * logic
 */
	//if ($_POST['form-element'] && is_array($_POST['form-element']) && isset($_GET['form']) && !empty($_GET['form']))
	if (isset($_POST['submit']) && isset($_POST['form-element']) /*&& $_POST['form-element']*/ && is_array($_POST['form-element']) && isset($_POST['fname']) && !empty($_POST['fname']))
	{
		//$form = $_GET['form'];
		$form = $_POST['fname'];
		$ele = $_POST['form-element'];
		foreach ($ele as $k => $v)
		{//INSERT INTO bf_trax.form_enter VALUES(NULL,'label', '0px', '0px', '107px', '30px','');
		//id, tag, top, left, width, height, text
			$qry = "INSERT INTO {$dbname}.form_{$form} (";
			//$qry .= '`id`,`tag`,`top`,`left`,`width`,`height`,`text`';
			//$qry .= 'tag, top, `left`, width, height, text';
			foreach ($v as $key => $val)
			{
				$qry .= enquote($key). ', ';
			}
			$qry = substr ($qry, 0, -2);
			//$qry .= 'VALUES (NULL, ';
			$qry .= ') VALUES (';
			foreach ($v as $key => $val)
			{
				$qry .= squote($val). ', ';
			}
/*			if (count($v) == 5)
			{
				$qry .= '"", ';
			}*/
			$pos = do_mbstr('strrpos', $qry, ',') - do_mbstr('strlen', $qry) /*- 3*/;
//			$pos = strrpos($qry, ',') - do_mbstr('strlen', $qry) - 2;
			$qry = substr ($qry, 0, /*-2*/$pos);
			$qry .= ')';
			$all[$k] = $qry;
			//echo 'sql = '. $qry. "<br>\n";
			try {
				$db->run($qry);
				unset($_GET['edit']);
				Redirect( BASE. 'database/'. $dbname. '/form/'. $form );
		//		$fail = false;
			//	$_GET['form'] = $_POST['fname'];
			} catch (PDOException $e) {
				$note = new Note(Array(
					'type' => Note::warning,
					'canIgnore' => true,
					'message' => sprintf( t("_Cannot create form %s"), $form ),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
				));
				echo $note->display();
		//		$fail = true;
		//		break;
			}
		}
/*		if (!$fail)
		{
//			echo 'all: <pre>'. print_r($all, true). '</pre>';
			unset($_GET['edit']);*/
/*			$note = new Note(Array(
				'type' => Note::notice,
				'message' => sprintf( t("_Created %s"), $form ),
				'ok' => t('_List all'),
				'canIgnore' => true,
				'post' => Array(
					'dbname' => $_POST['dbname'],
					'fname' => $_POST['fname'],
				),
				'ignore' => sprintf( t('_Goto %s'), $form ),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
			));
			echo $note->display();
			Redirect( BASE. 'database/'. $dbname. '/form/'. $form );*/
/*		}*/
	}
	//if ($_GET['form'] === $GLOBALS['data']['ADD_FRM'])	// selected to add a form
	if ($_GET['form'] === ADD_FRM)	// selected to add a form
	{
		if (isset($_POST['fname']) && !empty($_POST['fname'])) {	// if posted a non-blank name
			$form = $_POST['fname'];
			$qry = "CREATE TABLE {$dbname}.form_{$form} ("
				. 'id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, '
				. 'tag VARCHAR(100), '
/*				. 'top INT(6) UNSIGNED, '
				. 'right INT(6) UNSIGNED, '
				. 'bottom INT(6) UNSIGNED, '
				. '`left` INT(6) UNSIGNED, '*/
				. 'top VARCHAR(10), '
				. '`left` VARCHAR(10), '
				. 'width VARCHAR(10), '
				. 'height VARCHAR(10), '
				. 'text VARCHAR(100)'
				. ')';
			try {
				$db->run($qry);
				$_GET['form'] = $_POST['fname'];
				$note = new Note(Array(
//					'details' => "<br>\nQuery: {$qry}",
					'type' => Note::notice,
					'message' => sprintf( t("_Created %s"), $_POST['fname'] ),
					'ok' => t('_List all'),
					'canIgnore' => true,
					'post' => Array(
						'dbname' => $_GET['database'],
						'fname' => $_POST['fname'],
					),
					'ignoreSlug' => '?edit',
					'ignore' => sprintf( t('_Goto %s'), $_POST['fname']),
					'ignore_class' => "ok",
//					'details' => "<br>\nQuery: {$qry}",
				));
				echo $note->display();
			} catch (PDOException $e) {
				$note = new Note(Array(
					'type' => Note::warning,
					'canIgnore' => true,
					'message' => sprintf( t("_Cannot create form %s"), $_POST['fname'] ),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
				));
				echo $note->display();
			}
		} else {
//			enScript(Array('name' => "Sortable", 'put_in_header' => true, 'src' => "https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js", 'integrity' => "sha512-zYXldzJsDrNKV+odAwFYiDXV2Cy37cwizT+NkuiPGsa9X1dOz04eHvUWVuxaJ299GvcJT31ug2zO4itXBjFx4w==", 'crossorigin' => "anonymous", 'referrerpolicy' => "no-referrer"));
			//enScript( Array( 'name' => 'addEvent', 'src' => BASE. 'js/addEvent.js', 'version' => filemtime('js/addEvent.js'), 'show_comment' => true ));
			//enScript( Array( 'name' => 'addClass', 'src' => BASE. 'js/addClass.js', 'version' => filemtime('js/addClass.js'), 'show_comment' => true ));
			$scr->enqueue(Array( 'name' => 'form', 'src' => BASE. 'js/form.js', 'version' => filemtime('js/form.js') ));
			$scr->enqueue(Array( 'name' => 'dragresize', 'src' => BASE. 'js/dragresize_commented.js', 'version' => filemtime('js/dragresize_commented.js') ));
//			Scripts::getInstance()->enqueue(Array( 'name' => 'key', 'src' => BASE. 'js/key.js', 'version' => filemtime('js/key.js') ));
			$scr->enqueue(Array( 'name' => 'mousetrap.min', 'src' => BASE. 'js/mousetrap.min.js', 'version' => filemtime('js/mousetrap.min.js') ));
//			enScript( Array( 'name' => 'dragdrop', 'src' => BASE. 'js/dragdrop.js', 'version' => filemtime('js/dragdrop.js'), 'show_comment' => true ));
			display_form();		// no name posted, so display the form
		}
	} else {
		$pos = strpos( $_GET['form'], 'edit' );
		if ($pos !== false || isset($_GET['edit']))										// db is to be edited
		{
//			Scripts::getInstance()->enqueueInlineArray(Array( 'name' => 'SignTool-drawing', 'code' => 'var drawing = new SignTool("main_form");', 'put_in_header' => false, 'show_comment' => false ));
//			enScript(Array( 'name' => "Sortable", 'put_in_header' => true, 'src' => "https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js", 'integrity' => "sha512-zYXldzJsDrNKV+odAwFYiDXV2Cy37cwizT+NkuiPGsa9X1dOz04eHvUWVuxaJ299GvcJT31ug2zO4itXBjFx4w==", 'crossorigin' => "anonymous", 'referrerpolicy' => "no-referrer" ));
//			enScript(Array( 'name' => 'addEvent', 'src' => BASE. 'js/addEvent.js', 'version' => filemtime('js/addEvent.js'), 'show_comment' => false ));
//			enScript(Array( 'name' => 'addClass', 'src' => BASE. 'js/addClass.js', 'version' => filemtime('js/addClass.js'), 'show_comment' => false ));
			//enScript(Array( 'name' => 'form', 'src' => BASE. 'js/form.js', 'versimn' => filemtime('js/form.js'), 'show_comment' => false ));
//			enScript(Array( 'name' => 'SignTool', 'src' => BASE. 'js/SignTool.js', 'version' => fileatime('js/SignTool.js'), 'show_comment' => false ));
//			enScript(Array( 'name' => 'dragdrop', 'src' => BASE. 'js/dragdrop.js', 'version' => fileatime('js/dragdrop.js'), 'show_comment' => false ));
	//		Scripts::getInstance()->enqueue(Array( 'name' => 'element', 'src' => BASE. 'js/Element.js', 'version' => filemtime('js/Element.js') ));
			//Scripts::getInstance()->enqueue(Array( 'name' => 'dragDunn', 'src' => BASE. 'js/dragDunn.js', 'version' => filemtime('js/dragDunn.js') ));
			//Scripts::getInstance()->enqueue(Array( 'name' => 'resize', 'src' => BASE. 'js/resize.js', 'version' => filemtime('js/resize.js') ));
//			Scripts::getInstance()->enqueue(Array( 'name' => 'animate', 'src' => BASE. 'js/animate.js', 'version' => filemtime('js/animate.js') ));
			//$scr->enqueue(Array( 'name' => 'drags', 'src' => BASE. 'js/drags.js', 'version' => filemtime('js/drags.js') ));
			$scr->enqueue(Array( 'name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js') ));
			//$scr->enqueue(Array( 'name' => 'key', 'src' => BASE. 'js/key.js', 'version' => filemtime('js/key.js') ));
			$scr->enqueue(Array( 'name' => 'mousetrap.min', 'src' => BASE. 'js/mousetrap.min.js', 'version' => filemtime('js/mousetrap.min.js') ));
			$scr->enqueue(Array( 'name' => 'dragresize', 'src' => BASE. 'js/dragresize_commented.js', 'version' => filemtime('js/dragresize_commented.js') ));
			//Scripts::getInstance()->enqueue(Array( 'name' => 'subjx', 'src' => BASE. '../node_modules/subjx/dist/js/subjx.js', 'version' => filemtime('../node_modules/subjx/dist/js/subjx.js') ));
			$scr->enqueue(Array( 'name' => 'form', 'src' => BASE. 'js/form.js', 'version' => filemtime('js/form.js') ));
			//Scripts::getInstance()->enqueue(Array( 'name' => 'Drag', 'src' => BASE. 'js/Drag.js', 'version' => filemtime('js/Drag.js') ));
			//Scripts::getInstance()->enqueue(Array( 'name' => 'drag', 'src' => BASE. 'js/drag.js', 'version' => filemtime('js/drag.js') ));
			$form = ($pos > 0) ? do_mbstr('substr', $_GET['form'], 0, $pos-1) : $_GET['form'];
			$qry = 'SELECT * FROM '. $_GET['database']. '.form_'. $form;
			try {
				display_edit_form( $form, $db->run($qry)->fetchAll() );	// display editing form 
			} catch (PDOException $e) {	// cannoe select
				$note = new Note(Array(
					'type' => 'error',
					'message' => sprintf( t("_Failed to edit form %s"), $form ),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
				));
				echo $note->display();
			}
		} else {
			$pos = strpos( $_GET['form'], 'remove' );
			if ($pos !== false || isset($_GET['remove']))			// db is to be removed
			{
				$form = ($pos !== false) ? do_mbstr('substr', $_GET['form'], 0, $pos-1) : $_GET['form'];
				$qry = 'SELECT * FROM '. $_GET['database']. '.form_'. $form;
				try {
					if (!isset($_GET['bypass_form_has_rows_check']) && PREVENT_DELETE_FORM_IF_HAS_ROWS && !empty( $db->run($qry)->fetchAll() ))	// check if db has existing forms
					//if (!isset($_GET['bypass_form_has_rows_check']) && $GLOBALS['data']['PREVENT_DELETE_FORM_IF_HAS_ROWS'] && !empty( $db->run($qry)->fetchAll() ))	// check if db has existing forms
					{
						$note = new Note(Array(
							'type' => 'error',
							'message' => sprintf( t("_Cannot remove non-empty form %s"), $form ),
							'canIgnore' => true,
							'ignore' => t('Bypass check'),
							'ignoreSlug' => 'bypass_form_has_rows_check',//&bypass_form_has_columns_check',
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					} else {									// actually drop database, if no inners
//						$qry = 'DESCRIBE '. $_GET['database']. '.'. $form;
						try {
							if (!isset($_GET['bypass_form_has_columns_check']) && PREVENT_DELETE_FORM_IF_HAS_COLUMNS && !empty( $db->run($qry)->fetchAll() ))	// check if db.form has existing columns
							//if (!isset($_GET['bypass_form_has_columns_check']) && $GLOBALS['data']['PREVENT_DELETE_FORM_IF_HAS_COLUMNS'] && !empty( $db->run($qry)->fetchAll() ))	// check if db.form has existing columns
							{
								$note = new Note(Array(
									'type' => 'error',
									'message' => sprintf( t("_Cannot remove non-empty form %s"), $form ),
									'canIgnore' => true,
									'ignore' => t('Bypass check'),
									'ignoreSlug' => 'bypass_form_has_columns_check',
									'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
								));
								echo $note->display();
							} else {
								$qry = 'DROP TABLE '. $_GET['database']. '.form_'. $form;
								try {
									$db->run($qry);
									$note = new Note(Array(		// draw notice after drop
										'type' => Note::notice,
										'message' => sprintf( t("_Deleted %s"), $form ),
										'ok' => t('_List all'),
									));
									echo $note->display();
								} catch (PDOException $e) {	// cannot drop db
									$note = new Note(Array(
										'type' => 'error',
										'message' => sprintf( t("_Failed to drop form %s"), $form ),
										'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
									));
									echo $note->display();
								}
							}
						} catch (PDOException $e) {	// cannot show tables
							$note = new Note(Array(
								'type' => 'error',
								'message' => sprintf( t("_Cannot get information for form %s"), $form ),
								'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
							));
							echo $note->display();
						}
					}	//	/if empty
				} catch (PDOException $e) {	// cannot show forms
					$note = new Note(Array(
						'type' => 'error',
						'message' => sprintf( t("_Cannot get information for form %s"), $form ),
						'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				}
			} else {
				if (!hasPrefix($_GET['database']) && !isset($_POST['pressed'])) {
					$note = new Note(Array(
						'type' => Note::error,
						'ok' => 'return',
						'canIgnore' => true,
						'message' => sprintf( t("_Not a forms database %s"), $_GET['database'] ),
					));
					echo $note->display();
				} else {
					$dbname = delPrefix($_GET['database']);
//		Scripts::getInstance()->enqueue(Array( 'name' => "addEvent", 'src' => BASE. "js/addEvent.js", 'version' => filemtime('js/addEvent.js') ));
//		Scripts::getInstance()->enqueue(Array( 'name' => "addClass", 'src' => BASE. "js/addClass.js", 'version' => filemtime('js/addClass.js') ));
		$scr->enqueue(Array( 'name' => "trigmodal", 'src' => BASE. "js/trigmodal.js", 'version' => filemtime('js/trigmodal.js'), 'requires' => Array('bootstrap@5.0.1') ));
//		enScript(Array( 'name' => "adds", 'src' => BASE. "js/adds.js", 'version' => filemtime('js/adds.js'), 'requires' => Array('bootstrap@5.0.1') ));
//		enScript(Array( 'name' => "dels", 'src' => BASE. "js/dels.js", 'version' => filemtime('js/dels.js'), 'requires' => Array('bootstrap@5.0.1') ));
					$qry = 'SELECT * FROM '. addPrefix($dbname). '.form_'. $_GET['form'];	// get records from form/form
					try {
						display_rows( $db->run($qry)->fetchAll(PDO::FETCH_ASSOC) );
		/*				$qry = 'INSERT INTO buildForms.recent (name) VALUES ('. squote($dbname). ')';
						try {
							$db->run($qry);
						} catch (PDOException $e) {
						}*/
		/*				$qry = 'SHOW TABLES';
						try {
							display_rows( $db->run($qry)->fetchAll(PDO::FETCH_COLUMN) );
						} catch (PDOException $e) {
						//if (isset($results->errorInfo)) {
							$note = new Note(Array(
								'type' => 'error',
								'message' => sprintf( t("_Cannot list tables in database %s"), PREFIX. $dbname ),//$_GET['databases'])
								'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
							));
							echo $note->display();
						}*/
					} catch (PDOException $e) {
						$note = new Note(Array(
							'type' => 'error',
							'message' => sprintf( t("_Cannot get information for form %s"), $_GET['form'] ),
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					}
				}
			}
	/*
?>
);</script>
	<form>
		<div class="input_field">
			<div class="label">
				<label for="dbname"><?php l('_Database name'); ?>:</label>
			</div>
			<div class="input">
				<input type="text" id="dbname" name="dbname">
			</div>
		</div>
	</form>
<?php
	/ *
	$menu = new ButtonMenu([
		new Button('first', 'slug1'),
		new Button('second', 'slug2'),
		new Button('thrid', 'slug3')]);
	echo $menu->HTML();
	 */
		}
	}
?>
	<!-- ending page contents -->
		</div>
	</div>
	<!-- end page contents -->
	<!--<script src="js/rusure.js<?php echo '?'. fileatime('js/rusure.js'); ?>"></script>-->
<?php
/*****************************************************************************
 * functions
 */
	/**
	 * Display form to gether name of new form to create
	 */
	function display_form() {
?>
			<script>const takenDBNames = [
<?php
		if (is_array($results)) {
			foreach( $results as $row ) {
				//if (!in_array( $row[0], $GLOBALS['data']['RESERVED'] )) {
				if (!in_array( $row[0], RESERVED )) {
					echo "\t'{$row[0]}',\n";
				}
			}
		}
?>
];</script>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<span class="xthis-page"><?=l('_Add a new form')?></span>
					<?php //echo sprintf( t('_Database %s'), delPrefix($dbname)); ?>
<!--					<span class="xthis-page"><?php printf( t('_Add form to database %s'), delPrefix($_GET['database']) ); ?></span>-->
				</h1>
				<form method="post" id="new_form" name="new_form">
					<input type="hidden" name="dbname" value="<?=$_GET['database']?>">
					<!--<input type="hidden" name="action" value="<?=$_GET['form']?>">-->
					<input type="hidden" name="fname" value="<?=$_GET['form']?>">
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
								<label for="tbname"><?=l('_Form name')?>:</label>
							</div>
							<div class="input col-75">
								<input type="text" id="fname" name="fname" autofocus>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
								<div id="btn-panel" class="nav">
									<ul>
<?php
		//echo '<pre>'. print_r(AForm::ELEMENTS, true). '</pre>';
		$i = 0;
		foreach (AForm::ELEMENTS as $e)
		{
?>
<!--										<li id="elem_<?=$i++?>" class="list-group-item btn gbtn can-drag" draggable="true" title="<?=l($e['text'])?>"><span class="symbol" data-tag="<?=htmlentities($e['tag'])?>"><?=$e['display']?></span></li>-->
										<li id="elem_<?=$i++?>" class="list-group-item btn gbtn" title="<?=l($e['text'])?>"><span class="symbol" data-tag="<?=htmlentities($e['tag'])?>" data-value="<?=htmlentities($e['value'])?>"><?=$e['display']?></span></li>
<?php
		}
?>
									</ul>
								</div>
							</div>
							<div class="input col-75">
								<div id="main_form" class="NOdrsElement NOdrsMoveHandle">
									<div class="grid-container">
									</div>
<!--									<p id="form_placeholder" class="hide-start-drag">--><?//=t('_drag_instructions')?><!--</p>-->
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
			<!--					<label for="submit"><?php l('_Submit'); ?>:</label>-->
		&nbsp;
							</div>
							<div class="submit">
								<input type="submit" id="submit" name="submit" value="<?=l('_Add form')?>">
							</div>
						</div>
					</div>
				</form>
			</div>
<!--			<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js" integrity="sha512-zYXldzJsDrNKV+odAwFYiDXV2Cy37cwizT+NkuiPGsa9X1dOz04eHvUWVuxaJ299GvcJT31ug2zO4itXBjFx4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<!--		<script>
		const elems = document.querySelectorAll('.list-group-item');
		const dest = document.getElementById('main_form');
		/*new Sortable(dest, {
			animation: 150,
			ghostClass: 'blue-background-class'
		});*/
		elems.forEach(el => {
			el.addEventListener('dragstart', dragStart)
			el.addEventListener('dragend', dragEnd)
		});
		dest.addEventListener('dragover', dragOver);
		//dest.addEventListener('dragenter', dragEnter);
		//dest.addEventListener('dragleave', dragLeave);
		dest.addEventListener('drop', dragDrop);

		let dragItem = null;
		function dragOver(e) {
			e.preventDefault();
			//console.log('drag over');
		}
		function dragStart() {
			//console.log('drag started');
			dragItem = this;
			//setTimeout(() => this.className = 'invisible', 0);
		}
		function dragEnd() {
			//console.log('drag ended');
			this.className = 'list-group-item';
			dragItem = null;
		}
		function dragDrop() {
			//console.log('drag dropped');
			this.append(dragItem);
			//cursor = crosshairs;
		}
			</script>-->
<?php
//		Link::style( $name, BASE. $file, filemtime($filename) );
//function enqueueScript( string $name, string $filename, string $version='', bool $put_in_header=false, bool $is_async=false, string                $cross_domain='', bool $showComment=false ) {
		//enqueueScript( 'addEvent', BASE. 'js/addEvent.js', fileatime('js/addEvent.js'), false, false, '', true );
/*		enScript( Array(
			'name' => 'addEvent',
			'src' => BASE. 'js/addEvent.js',
			'version' => fileatime('js/addEvent.js'),
			'put_in_header' => false,
			'show_comment' => true
		));
		enScript( Array(
			'name' => 'dragdrop',
			'src' => BASE. 'js/dragdrop.js',
			'version' => fileatime('js/dragdrop.js'),
			'put_in_header' => false,
			'show_comment' => true
		));*/
	}

	/**
	 * List rows in the given form
	 */
	//function display_rows($results) {
	function display_rows($forms) {
		$dbname = $_GET['database'];
		//$table = isset($_GET['table']) ? $_GET['table'] : $_GET['form'];
		$form = $_GET['form'];
		$href = BASE. 'database/'. $dbname. '/form/'. $form. '?edit';
?>
			<div id="xpane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<a style="display: inline" data-href="<?=$href?>" title="<?=l('_Edit form')?>" class="js-enable" disabled="true"><span class="symbol btn gbtn edit right"><?php //printf( t('_Form %s'), $form); ?></span></a>
					<form>
						<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
						<span><?=sprintf( t('_Form %s'), '<input class="highlight" type="text" name="rename" size="'. strlen($form). '" value="'. $form. '" />')?></span>
<!--						<span class="xthis-page"><?=sprintf( t('_Database %s'), '<input class="highlight" type="text" name="rename" size="'. strlen(delPrefix($dbname)). '" value="'. delPrefix($dbname). '" />')?></span>-->
<!--						<span class="xthis-page"><?=sprintf( t('_Records in form %s'),
'<input class="xhide highlight" type="text" name="rename" size="'. strlen($form). '" value="'. $form. '" />')?></span>-->
					</form>
<!--					<span class="xthis-page"><?php printf( t('_Form %s'), $form); ?></span>-->
				</h1>
<!--				<table id="<?php echo $form; ?>_rows" class="tables zebra">-->
				<div id="main_form" class="pane NOdrsElement NOdrsMoveHandle">
<!--					<div id="grid-container">
					</div>-->
<?php
			foreach ($forms as $k => $v)
			{
				print_r($forms, true);
				if (isset($v['top']) && isset($v['left']) && isset($v['width']) && isset($v['height']) && isset($v['tag']) /*&& isset($v['text'])*/) {
?>
					<div style="position: absolute; top: <?=$v['top']?>; left: <?=$v['left']?>; width: <?=$v['width']?>; height: <?=$v['height']?>">
					<!--<div style="position: relative; top: <?=$v['top']?>; left: <?=$v['left']?>; width: <?=$v['width']?>; height: <?=$v['height']?>">-->
						<<?=$v['tag']?>>
<?php				//echo '<'. $v['tag']. '>';
					if (isset($v['text'])) {
?>
							<span><?=$v['text']?></span>
<?php
					}
					if (!isset($v['selfclose']) || !$v['selfclose']) {
?>
						<?php echo '</'. strtok($v['tag'], ' '). '>'; ?>
<?php
					}
?>
					</div>
<?php
				}
			}
?>
				</div><!--canvas-->
<?php
	}

	/**
	 * Display current contents of form and allow editing
	 */
	function display_edit_form( $form, $contents )
	{
/*?>
			<script>const takenDBNames = [
<?php
		if (is_array($contents)) {
			foreach( $contents as $row ) {
				//if (!in_array( $row[0], $GLOBALS['data']['RESERVED'] )) {
				if (!in_array( $row[0], RESERVED )) {
					echo "\t'{$row[0]}',\n";
				}
			}
			}*/
		$href = BASE. 'database/'. $_GET['database']. '/form/'. $form;/*
?>
			];</script>*/ ?>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<a style="display: inline" href="<?=$href?>" title="<?=l('_View form')?>"><span class="symbol btn gbtn right view"></span></a>
					<form>
<!--						<span class="xthis-page"><?=sprintf( t('_Records in form %s'),
'<input class="xhide highlight" type="text" name="rename" size="'. strlen($form). '" value="'. $form. '" />')?></span>-->
						<span class="xthis-page"><?=sprintf( t('_Form %s'),
'<input class="xhide highlight" type="text" name="rename" size="'. strlen($form). '" value="'. $form. '" />')?></span>
					</form>
<!--					<span class="xthis-page"><?php printf( t('_Form %s'), $form); ?></span>-->
				</h1>
<!--				<h1>
					<span class="xthis-page"><?=sprintf( t('_Edit form %s'), $form)?></span>
					<a style="display: inline" href="<?=$href?>" title="<?=sprintf( t('_View %s'), $form)?>"><span class="symbol btn gbtn right view"></span></a>
				</h1>-->
				<form method="post" id="edit_form" name="edit_form">
					<input type="hidden" name="dbname" value="<?=$_GET['database']?>">
					<!--<input type="hidden" name="action" value="<?=$_GET['form']?>">-->
					<input type="hidden" name="fname" value="<?=$_GET['form']?>">
<!--					<div class="row">
						<div class="input_field">
							<div class="label col-25">
								<label for="tbname"><?=l('_New form name')?>:<br>
								<small><?php l('_optional'); ?></small></label>
							</div>
							<div class="input col-75">
								<input type="text" id="fname" name="fname" value="<?=$form?>">
							</div>
						</div>
					</div>-->
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
								<div id="btn-panel" class="nav">
									<ul>
<?php
		$i = 0;
		foreach (AForm::ELEMENTS as $e)	// toolbox element items
		{
?>
										<li id="elem_<?=$i++?>" class="list-group-item btn gbtn can-drag" draggable="true" title="<?=l($e['text'])?>"><span class="symbol" data-tag="<?=str_replace('\"', '"', $e['tag'])?>"<?php if (isset($e['value'])) print ' data-value="'. htmlentities($e['value']). '"';?> title="<?=l($e['text'])?>"><?=$e['display']?></span></li>
<!--										<li id="elem_<?=$i++?>" class="list-group-item btn gbtn" title="<?=l($e['text'])?>"><span class="symbol" data-tag="<?=str_replace('\"', '"', $e['tag'])?>" title="<?=l($e['text'])?>"><?=$e['display']?></span></li>-->
<!--										<li id="elem_<?=$i++?>" class="list-group-item btn gbtn" title="<?=l($e['text'])?>"><span class="symbol" data-tag="<?=addslashes($e['tag'])?>" title="<?=l($e['text'])?>"><?=$e['display']?></span></li>-->
<!--										<li id="elem_<?=$i++?>" class="list-group-item btn gbtn" title="<?=l($e['text'])?>"><span class="symbol" data-tag="<?=$e['tag']?>" title="<?=l($e['text'])?>"><?=$e['display']?></span></li>-->
<?php
		}
		$title = t('_Do you really want to do this?');
		//$body = t('_Really remove all elements');
		$body = t('_Remove all elements');
//		$href = BASE. 'database/'. 'aaaa';
		$hover = t('_Remove all elements');
?>
										<li id="elem_remove_all" class="list-group-item btn rbtn" title="<?=$hover?>"><a data-bs-toggle="modal" data-bs-target="#myModal" data-title="<?=$title?>" data-body="<?=$body?>" class="symbol modal-trigger" href="<?=$href?>" title="<?=$hover?>"><span class="symbol delete"> <?//=t('Remove all')?></span></a></li>
									</ul>
								</div>
							</div>
							<div class="input col-75">
								<div id="main_pane">
									<div id="main_ruler_x" class="ruler ruler-x">
										<div id="pointer-l" class="ruler-pointer pointer-l"></div>
										<div id="pointer-r" class="ruler-pointer pointer-r"></div>
										<div class="grid-container grid-pattern">
										</div>
									</div>
									<div id="main_ruler_y" class="ruler ruler-y">
										<div id="pointer-t" class="ruler-pointer pointer-t"></div>
										<div id="pointer-b" class="ruler-pointer pointer-b"></div>
										<div class="grid-container grid-pattern">
										</div>
									</div>
									<div id="main_form" class="edit-form drsElement drsMoveHandle">
										<div class="grid-container grid-pattern">
										</div>
<?php
		if (empty($contents)) {
?>
<!--									<p id="form_placeholder" class="hide-start-drag">--><?//=t('_drag_instructions')?><!--</p>-->
<?php
		} else {
			foreach ($contents as $k => $v)
			{
//				print_r($forms, true);
				if ( isset($v['top']) && isset($v['left']) && isset($v['width']) && isset($v['height']) && isset($v['tag']) ) {
?>
									<!--<div id="form-element-<?=$k?>" class="form-element" draggable=true style="position: relative; top: <?=$v['top']?>; left: <?=$v['left']?>; width: <?=$v['width']?>; height: <?=$v['height']?>">-->
										<div id="form-element-<?=$k?>" class="form-element" draggable="true" style="position: absolute; top: <?=$v['top']?>; left: <?=$v['left']?>; width: <?=$v['width']?>; height: <?=$v['height']?>">
											<<?=$v['tag']?>>
<?php				//echo '<'. $v['tag']. '>';
					if (isset($v['text'])) {
											/*	<span><?=$v['text']?></span>*/
?>
												<input id="form-element-<?=$k?>-text" name="form-element[<?=$k?>][text]" value="<?=$v['text']?>">
<?php
					}
					if (!isset($v['selfclose']) || !$v['selfclose']) {
?>
						<?php echo '</'. strtok($v['tag'], ' '). '>'; ?>
<?php
					}
?>
					</div>
<?php
				}
			}
		}
?>
									</div>
								</div><!--canvas-->
							</div>
						</div>
					</div>
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
			<!--					<label for="submit"><?php l('_Submit'); ?>:</label>-->
		&nbsp;
							</div>
							<div class="submit">
								<input type="submit" id="submit" name="submit" value="<?=l('_Save changes')?>">
							</div>
						</div>
					</div>
				</form>
			</div>
<?php
	}
