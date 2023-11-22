<?php

// common
Scripts::getInstance()->enqueue(Array( 'name' => 'tablerow', 'src' => BASE. 'js/tablerow.js', 'version' => filemtime('js/tablerow.js') ));
//Link::style(Array( 'name' => 'business', 'href' => BASE. 'css/table.css', 'version' => filemtime('css/table.css') ));
//Link::style(Array( 'name' => 'pagination', 'href' => BASE. 'css/pagination.css', 'version' => filemtime('css/pagination.css') ));
//Link::style(Array( 'name' => 'tabbar', 'href' => BASE. 'css/tabbar.css', 'version' => filemtime('css/tabbar.css') ));
/*
	Scripts::getInstance()->enqueue(Array( 'name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MakeCopy', 'src' => BASE. 'js/MakeCopy.js', 'version' => filemtime('js/MakeCopy.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'resetForm', 'src' => BASE. 'js/resetForm.js', 'version' => filemtime('js/resetForm.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'business', 'src' => BASE. 'js/business.js', 'version' => filemtime('js/business.js') ));
	//Link::style(Array( 'name' => 'autocomplete', 'href' => BASE. '../business/autocomplete.css', 'version' => filemtime('../business/autocomplete.css') ));
	Link::style(Array( 'name' => 'business', 'href' => BASE. 'css/business.css', 'version' => filemtime('css/business.css') ));
 */	
if (isset($_GET['checked'])) {
	$checked = filter_input(INPUT_GET, 'checked', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
	foreach ($checked as $k => $v) {
		if ($v == 'on') {
			$checked_rows[] = substr($k, 3);
		}
	}
}
$bus = new Business;
//$bus = new Business;
// if table-action
if (isset($_GET['table-action'])) {
	if (!isset($_GET['checked'])) {
		$note = new Note(Array(
			'type' => Note::error,
			'message' => sprintf( t("_Cannot %s rows - no rows selected"), $_GET['table-action']),
		));
		echo $note->display();
		return;
	}
	switch ($_GET['table-action']) {
	case 'delete':
		$bus->deleteRows($checked_rows);
/*		$note = new Note(Array(
			'type' => Note::notice,
			'message' => sprintf( t("_Deleting rows %s"), $rows),
		));
		echo $note->display();*/
		break;
	/*case 'edit':
		$note = new Note(Array(
			'type' => Note::notice,
			'message' => sprintf( t("_Editing rows %s"), implode(',', $checked_rows)),
		));
		echo $note->display();
		break;*/
	return;
	}
} else {
	$offset = 0;
	$limit = 125;
	$businesses = $bus->getAll(array('offset' => $offset, 'limit' => $limit));
//	echo 'count($businesses) = '. count($businesses). "<br>\n";
/*	while (count($businesses) >= ($limit+$offset)) {
		$offset += $limit;
		$businesses[] = $bus->getAll(array('offset' => $offset, 'limit' => $limit));
	}*/
//	$businesses = getBusinesses();
	$tbl1data = $bus->getAll();
	if (empty($tbl1data)) {
/*		$note = new Note(Array(
			'type' => Note::error,
			'message' => t('_Cannot get documents data'),
			'details' => sprintf( t('_Query %s'), $bus->getLastSQL() ),
		));
		echo $note->display();*/
	}
	$tbl1 = new Table(array(
		'id' => 'businesses',
		'class' => 'zebra',
		'actions' => array(
			'pre' => "&lsh;",/*↰ - UP ARROW WITH TIP LEFT - U+021B0 UNICODE - &#x21b0; HEX CODE - &#8624; HTML CODE - &lsh; HTML ENTITY - \21B0*/
			'extra' => 'style="transform:rotate(180deg)"',
			'inner' => array(
				array(
					'text' => 'Delete',
					'title' => 'Delete',
					'value' => 'delete',
					'class' => 'no-button',
				),
			/*	array(
					'text' => 'Edit',
					'title' => 'Edit',
					'value' => 'edit',
					'class' => 'no-button',
				),*/
			),
		),
		'omit' => array('business_id', 'post_code', 'province', 'address_line2', 'date_first', 'date_updated', 'note', 'active'),
		'data_id' => 'business_id',
		//'data' => $businesses,
		'data' => $tbl1data,
	//	'caption' => 'businesses'
	));

	$usr = new Users;
	$tbl2data = $usr->getAll();
	if (empty($tbl2data)) {
/*		$note = new Note(Array(
			'type' => Note::error,
			'message' => t('_Cannot get documents data'),
			'details' => sprintf( t('_Query %s'), $usr->getLastSQL() ),
		));
		echo $note->display();*/
	}
	$tbl2 = new Table(array(
		'id' => 'users',
		'class' => 'zebra',
		'actions' => array(
			'pre' => "&lsh;",
			'extra' => 'style="transform:rotate(180deg)"',
			'inner' => array(
				array(
					'text' => 'Delete',
					'title' => 'Delete',
					'value' => 'delete',
					'class' => 'no-button',
				),
			/*	array(
					'text' => 'Edit',
					'title' => 'Edit',
					'value' => 'edit',
					'class' => 'no-button',
				),*/
			),
		),
		'omit' => array('user_id',),
/*		'omit' => 'user_id',*/
		'data_id' => 'user_id',
		'data' => $tbl2data,
	//	'caption' => 'businesses'
	));

	$doc = new Documents;
	$tbl3data = $doc->getAll();
	if (empty($tbl3data)) {
/*		$note = new Note(Array(
			'type' => Note::error,
			'message' => t('_Cannot get documents data'),
			'details' => sprintf( t('_Query %s'), $doc->getLastSQL() ),
		));
		echo $note->display();*/
	}
	$tbl3 = new Table(array(
		'id' => 'documents',
		'class' => 'zebra',
		'actions' => array(
			'pre' => "&lsh;",
			'extra' => 'style="transform:rotate(180deg)"',
			'inner' => array(
				array(
					'text' => 'Delete',
					'title' => 'Delete',
					'value' => 'delete',
					'class' => 'no-button',
				),
			/*	array(
					'text' => 'Edit',
					'title' => 'Edit',
					'value' => 'edit',
					'class' => 'no-button',
				),*/
			),
		),
		'omit' => array('document_id',),
		'data_id' => 'document_id',
		'data' => $tbl3data,//(new Documents)->getAll(),
	//	'caption' => 'businesses'
	));

	$snt = new Sent;
	$tbl4data = $snt->getAll();
//	if (empty($tbl4data)) {
//		$note = new Note(Array(
//			'type' => Note::error,
//			'message' => t('_Cannot get sent data'),
//			'details' => sprintf( t('_Query %s'), $snt->getLastSQL() ),
//		));
//		echo $note->display();
//	}
	$tbl4 = new Table(array(
		'id' => 'sent',
		'class' => 'zebra',
		'actions' => array(
			'pre' => "&lsh;",
			'extra' => 'style="transform:rotate(180deg)"',
			'inner' => array(
				array(
					'text' => 'Delete',
					'title' => 'Delete',
					'value' => 'delete',
					'class' => 'no-button',
				),
//				array(
//					'text' => 'Edit',
//					'title' => 'Edit',
//					'value' => 'edit',
//					'class' => 'no-button',
//				),
			),
		),
		'omit' => array('sent_id',),
		'data_id' => 'sent_id',
		'data' => $tbl4data,
	//	'caption' => 'businesses'
	));
//	echo 'sent table object:<pre>'. print_r($tbl4, true). '</pre>'. "\n";

	$tabs = new TabBar(array(
		'data' => array(
			array(
				'heading' => 'Businesses',
				'content' => $tbl1->render(),
			),
			array(
				'heading' => 'Users',
				'content' => $tbl2->render(),
			),
			array(
				'heading' => 'documents',
				'content' => $tbl3->render(),
			),
			array(
				'heading' => 'sent',
				'content' => $tbl4->render(),
			),
		),
	));
//	echo 'business/table.php: ';
	$tabs->render5();
	?><form><?php
	// display table
//	echo $tbl->render();
?></form><?php
}
