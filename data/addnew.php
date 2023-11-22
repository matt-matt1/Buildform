<?php

	/*	`*/
$forms = array(
	array(
		'id' => 'form1',
		'method' => 'post',
	//	'action' => 'form.php'
		'show_req' => false,
		'show_desc' => true,
		'fieldsets' => array(
			array(
				'class' => 'business',
				'legend' => 'Business',
				'fields' => array(
					'business_id' => array(
						'id' => 'business_id',
//						'name' => 'business_id',
						'type' => 'hidden',
//						'id' => true,
//						'noLabel' => true,
						'optional' => true,
						'show_optional' => false,
						'value' => 1,
					),
					'business_name' => array(
						'id' => 'business_name',
						//'name' => 'business_name',
						'placeholder' => 'Yuma Technical Inc.',
						'noLabel' => true,
						'default_extra' => 'style="margin:0 1em"',
						'default_' => 'Business name',
					//	'description' => 'Business name',
						'hide_description' => false,
						'type' => 'text',
//						'default' => '',
						'required' => true,
						'row_class' => "xrow mb-2 g-3 col-md-6",
						//'row_class' => "row mb-2 g-2",
						'row_extra' => 'style="float:left;margin:0 1.5em"',
						'input_wrap_class' => 'input xcol-md-6',
					//	'optional' => true,
						'show_optional' => false,
					),
					'active' => array(
						'name' => 'active',
						'id' => 'active',
						'placeholder' => 'Yes',
					//	'noLabel' => true,//'label' => false,
//						'description' => 'Business active',
						'label' => 'Business active',
						'type' => 'checkbox',
						'row_class' => 'row g-2 mb-4 form-switch',
						//'label_class' => 'right',
						'input_class' => 'big-check',
				//		'class' => 'switch',
						'input_wrap_class' => 'input',
						'row_extra' => 'style="float:right;margin:0 1.5em"',
						//'row_class' => 'ml-2 col-auto g-2',
						'required' => true,
					//	'optional' => true,
						'show_optional' => false,
					),
					'email' => array(
						'id' => 'email',
						//'name' => 'email',
						'placeholder' => 'sales@yumatechnical.com',
						'noLabel' => true,//'label' => false,
					//	'description' => 'email address',
						'default_' => 'email address',
						'type' => 'text',
						'row_extra' => 'style="clear:both"',
						'row_class' => "row mb-2 g-2",
						'input_wrap_class' => 'input col-md-8 col-sm-12',
						//'type' => 'email',
						'optional' => true,
						'show_optional' => false,
					),
					'street_address' => array(
						'name' => 'street_address',
						'id' => 'street_address',
						'placeholder' => '24 Hancock Cresent',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Street address',
						'default_' => 'Street address',
						'input_wrap_class' => 'input col-md-12',
						'type' => 'text',
						'row_class' => "row mb-2 g-2",
						'optional' => true,
						'show_optional' => false,
					),
					'address_line' => array(
						'name' => 'address_line',
						'id' => 'address_line',
						'placeholder' => 'Suite 201, Upper Level',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Address line2',
						'default_' => 'Address line2',
						'input_wrap_class' => 'input col-md-12',
						'type' => 'text',
						'row_class' => "row mb-2 g-2",
						'optional' => true,
						'show_optional' => false,
					),
					'city' => array(
						'name' => 'city',
						'id' => 'city',
						'placeholder' => 'Scarborough',
						'noLabel' => true,//'label' => false,
					//	'description' => 'City / Town',
						'default_' => 'City / Town',
						'default_extra' => 'style="margin:0 1em"',
						'type' => 'text',
						'row_class' => "xrow mb-2 g-3 col-auto",
//						'input_class' => 'input',
						'input_wrap_class' => 'input',
						'row_extra' => 'style="float:left;margin:0 1.25em"',
						'optional' => true,
						'show_optional' => false,
					),
					'province' => array(
						'name' => 'province',
						'id' => 'province',
						'placeholder' => 'ON',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Province / State',
						'default_' => 'Province / State',
						'default_extra' => 'style="margin:0 1em"',
						'input_wrap_class' => 'input',
						'row_extra' => 'style="float:left;margin:0 1.25em"',
						'row_class' => 'ml-2 col-auto g-2',
						'type' => 'text',
						'optional' => true,
						'show_optional' => false,
					),
					'post_code' => array(
						'name' => 'post_code',
						'id' => 'post_code',
						'placeholder' => 'M1R 2A3',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Postcode',
						'default_' => 'Postcode',
						'default_extra' => 'style="margin:0 1em"',
						//'default_class' => 'g-2',
						'input_wrap_class' => 'input',
						'row_extra' => 'style="float:left;margin:0 1.5em"',
						'row_class' => 'ml-2 col-auto g-2',
						'type' => 'text',
						'optional' => true,
						'show_optional' => false,
					),
					'website' => array(
						'name' => 'website',
						'id' => 'website',
						'row_class' => "row mb-2 g-2",
						'placeholder' => 'https://yumatechnical.com',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Website address',
						'default_' => 'Website address',
						'type' => 'text',
						'row_extra' => 'style="clear:both"',
						'input_wrap_class' => 'input col-md-8 col-sm-12',
						'optional' => true,
						'show_optional' => false,
					),
/*					'contactfieldset' => array(
						//'noInput' => true,
						'fieldset' => 'open',
						'id' => 'open_contactfieldset',
						'legend' => '',
),*/
					'contact_id' => array(
						'name' => 'contact_id',
					//	'id' => 'contact_id',
//						'id' => true,
						'type' => 'hidden',
//						'noLabel' => true,
						'optional' => true,
						'show_optional' => false,
					),
					'contact_first' => array(
						'name' => 'contact_first',
					//	'id' => 'contact_first',
						'row_class' => "xrow mb-2 g-3 col-auto",
						'row_extra' => 'style="float:left;margin:0 1.25em"',
						'placeholder' => 'Yuma',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Contact first name',
						'default_' => 'Contact first name',
						'default_extra' => 'style="margin:0 1em"',
						'hide_description' => false,
						'type' => 'text',
						//'default' => '',
						'input_wrap_class' => 'input',
						//'input_wrap_class' => 'input col-md-6',
						//'required' => true,
						'optional' => true,
						'show_optional' => false,
					),
					'contact_last' => array(
						'name' => 'contact_last',
						//'id' => 'contact_last',
						'placeholder' => 'Ttechnical',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Contact last name',
						'default_' => 'Contact last name',
						'default_extra' => 'style="margin:0 1em"',
						'type' => 'text',
						'noLabel' => true,
						'input_wrap_class' => 'input',
						//'input_wrap_class' => 'input col-md-6',
						'row_class' => 'ml-2 col-auto g-2',
						'row_extra' => 'style="float:left;margin:0 1.25em"',
						'optional' => true,
						'show_optional' => false,
					),
/*
				$opts = '';//'<option selected disabled>'. t('_Select'). '<option>';
				foreach ($dbs as $base) {
					$opts .= '<option ';
					if ($dbname == $base)
						$opts .= 'disabled ';
					$opts .= 'value="'. delPrefix($base). '">'. delPrefix($base). '</option>';
				}
				$dblist = array(
					'type' => 'select',
					'id' => "move". $a,
					'name' => 'move'. $a,
					'noLabel' => true,
					'placeholder' => t('_move to database'),
					'title' => t('_move this table in another database'),
//					'label' => SQL::COLEXTRAS[2]['text'],//t('_Collation'),
//					'label_title' => SQL::COLEXTRAS[2]['title'],
//					'value' => $collation,
//					'input_title' => !empty($collation) ? sprintf( t('_was %s'), $collation ) : '',
//					'row_class' => 'row g-2 mb-4',
//					'label_class' => 'list_dbs',
					'input_class' => 'list_dbs',
//					'input_wrap_class' => 'input col-md-3',
					'extra' => 'style="width: 12em;" data-table="'. $table. '" data-disable="'. delPrefix($dbname). '" data-href="'. $move. '"',
//					'extra' => 'style="width: 12em;"><a class="symbol"><span class="symbol"><i class="fa-solid fa-right-long-to-line"></i></span></a',
//					'type' => 'select',
//					'roptions' => Select::drawOptions(SQL::COLLATION, '', $collation),
					'optional' => true,
					'show_optional' => false,
/ *					'input2' => array(
//						'type' => 'submit',
//						'input_wrap_class' => 'xsymbol',
//						'input_class' => 'no-button xsymbol',
//						'value' => t('_go'),
						'extra' => '><a class="symbol"><span class="symbol"><i class="fa-solid fa-right-long-to-line"></i></span></a'
					),
//					'roptions' => '<option selected disabled>'. t('_move to'). '</option>',
 * /
					'roptions' => $opts
 				);
*/
					'contact_type' => array(
						//'id' => 'contact_type',
						'type' => 'select',
						'noLabel' => true,
						'optional' => true,
						'show_optional' => false,
/*						'options' => array(
							array(
								'name' => '',
								'value' => '',
							),
						),*/
//						'description' => 'Contact method',
						'default_' => 'Contact method',
						'default_extra' => 'style="margin:0 1em"',
						'input_wrap_class' => 'input',
						'row_class' => 'ml-2 col-auto g-2',
						'row_extra' => 'style="float:left;margin:0 1.5em"',
					),
					'contact_number' => array(
						'name' => 'contact_number',
						//'id' => 'contact_number',
						'placeholder' => '+1 (647) 956-6145',
						'noLabel' => true,
//						'description' => 'Contact number',
						'default_' => 'Contact number',
						'default_extra' => 'style="margin:0 1em"',
						'type' => 'text',
						'optional' => true,
						'show_optional' => false,
						'input_wrap_class' => 'input',
						'row_class' => 'ml-2 col-auto g-2',
						'row_extra' => 'style="float:left;margin:0 1.25em"',
					),
					'addcontact' => array(
						'type' => 'button',
						'id' => 'addcontact',
						'row_extra' => 'style="clear:both;/*float:right*/"',
						'row_class' => 'row col-auto',
						'value' => 'Add a contact',
					),
	/*				'contactfieldset' => array(
						'noInput' => true,
						'fieldset' => 'close',
						//'noLabel' => true,
						'id' => 'close_contactfieldset',
						'row_extra' => 'style="clear:both"',
	),*/
					'date_first' => array(
						'name' => 'date_first',
						'id' => 'date_first',
						//'row_class' => "row mb-2 g-2",
						'placeholder' => '2016-10-26 12:34:56',
						'noLabel' => true,//'label' => false,
//						'description' => 'Creation date',
						'default_' => 'Creation date',
						'default_extra' => 'style="margin:0 1em"',
						'type' => 'text',
						'optional' => true,
						'show_optional' => false,
						'input_wrap_class' => 'input col-md-12',
						'row_class' => 'ml-2 col-md-5 g-2',
						'row_extra' => 'style="clear:both;float:left;margin:0 1.25em"',
					),
					'date_updated' => array(
						'name' => 'date_updated',
						'id' => 'date_updated',
						'placeholder' => '2023-08-23 09:18:27',
						'noLabel' => true,//'label' => false,
//						'description' => 'Last updated',
						'default_' => 'Last updated',
						'default_extra' => 'style="margin:0 1em"',
						'type' => 'text',
						'optional' => true,
						'show_optional' => false,
						'input_wrap_class' => 'input col-md-12',
						'row_class' => 'ml-2 col-md-5 g-2',
						'row_extra' => 'style="float:left;margin:0 1.25em"',
					),
					'note' => array(
						'name' => 'note',
						'id' => 'note',
						'row_class' => "row mb-2 g-2",
						'placeholder' => 'This is a good business.',
						'noLabel' => true,//'label' => false,
//						'description' => 'Notes',
						'default_' => 'Notes',
						'type' => 'textarea',
						'row_class' => "row mb-2 g-2 row-cols-2 textarea5",
						'optional' => true,
						'show_optional' => false,
//						'default_extra' => 'style="margin:0 1em"',
						'input_wrap_class' => 'input col-md-12',
//						'row_class' => 'ml-2 col-auto g-2',
						'row_extra' => 'style="clear:both;margin:0 1.125em"',
					),
				),// end fieldsets-fields
			),// end this fieldset
		),// end fieldsets
		'fields' => array(
			'submit' => array(
				'id' => 'submit',
//				'name' => 'submit',
				'value' => 'Submit',
				'type' => 'submit',
			),
		),
	),// end this form
);

// attach option from types database table to the contact_types in the form
$qry = 'SELECT `type_id` AS id,`name` FROM `yumatech_organise`.`types` WHERE 1;';
try {
	$type_options = $db->run($qry)->fetchAll();
//	unset($_GET['remame']);
/*	$note = new Note(Array(		// draw notice after drop
		'type' => Note::notice,
		'message' => sprintf( t("_Renamed %s"), / *delPrefix($dbname)* /$_GET['database'] ),
		'ok' => t('_List databases'),
		'canIgnore' => true,
		'post' => Array(
			//'dbname' => delPrefix($_POST['database']),
			'dbname' => $_POST['rename'],
		),
		'ignore' => sprintf( t('_Goto %s'), $_POST['rename']),
	//	'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
	));
	echo $note->display();*/
//	Redirect( BASE. 'database/'. addPrefix($name) );
//	Redirect( BASE. 'databases' );
} catch (PDOException $e) {
	$note = new Note(Array(
		'type' => Note::error,//'error',
		'message' => t("_Cannot get types"),
		'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
	));
	echo $note->display();
	$type_options = array(
		array(
			'id' => 1,
			'value_' => 'phone',
			'name' => 'Phone',
		),
		array(
			'id' => 2,
			'value_' => 'fax',
			'name' => 'Fax',
		),
		array(
			'id' => 3,
			'value_' => 'cell-phone',
			'name' => 'Cell phone',
		),
	);
} finally {
//$forms[0]['fieldsets'][0]['fields']['contact_type']['options'] = $type_options;
//$forms[0]['fieldsets'][0]['fields']['contact_type']['roptions'] = print_r($type_options, true);
	$opts = array();
	foreach ($type_options as $k => $v) {
		//echo print_r($type_options, true);
		//echo print_r($k, true);
		//echo print_r($v, true);
		if (isset($v['name']) && isset($v['id'])) {
			$val = $v['id'];
			$opts[$val] = $v['name'];
		}
	}
	$forms[0]['fieldsets'][0]['fields']['contact_type']['options'] = $opts;
	//$forms[0]['fieldsets'][0]['fields']['contact_type']['roptions'] = print_r($opts, true);
}

$id = 1;
$qry = 'SELECT * FROM `yumatech_organise`.`business` WHERE business_id='. $id. ';';
try {
	$business = $db->run($qry)->fetch();
	foreach ($business as $k => $v) {
		if (isset($forms[0]['fieldsets'][0]['fields'][$k]) && !isset($forms[0]['fieldsets'][0]['fields'][$k]['value'])) {
			$forms[0]['fieldsets'][0]['fields'][$k]['value'] = $v;
		}
	}
	$qry = 'SELECT * FROM `yumatech_organise`.`contact` WHERE business_id='. $id. ';';
	try {
		$business = $db->run($qry)->fetchAll();
		foreach ($business as $key => $val) {
			foreach ($val as $k => $v) {
				if (isset($forms[0]['fieldsets'][0]['fields'][$k]) && !isset($forms[0]['fieldsets'][0]['fields'][$k]['value'])) {
					$forms[0]['fieldsets'][0]['fields'][$k]['value'] = $v;
				}
			}
		}
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::error,//'error',
			'message' => sprintf( t("_Cannot get contacts for '%s'"), $business['business_name'] ),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
		));
		echo $note->display();
	}
} catch (PDOException $e) {
	$note = new Note(Array(
		'type' => Note::error,//'error',
		'message' => t("_Cannot get business"),
		'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
	));
	echo $note->display();
}

$errors = array();

/**
 * Perform basic validation for fields
 */
function validateFields($fields)
{
	//	echo '<pre>processField($fields) : '. print_r($fields, true). '</pre>';
	foreach ($fields as $k => $v) {
	//	echo '<pre>processField($fields-$k) : '. print_r($k, true). '</pre>';
	//	echo '<pre>processField($fields-$v) : '. print_r($v, true). '</pre>';
		if (isset($v['name'])) {
			echo 'validating : '. $v['name']. ' :'. "<br>\n";
			if (isset($v['required']) && $v['required']) {
				echo 'validating : '. $v['name']. ' :'. "\n";
				if (isset($v['description'])) {
					$miss = $v['description'];
				}
				if (!$miss && isset($v['label'])) {
					$miss = $v['label'];
				}
				if (!$miss && isset($v['name'])) {
					$miss = $v['name'];
				}
				$errors[] = "Missing '{$miss}'";
			}
		}
	}
}

/**
 *
 */
function makeSQL(string $tbl='business', string $id='business_id', array $els=array('business_name', 'email', 'street_address', 'address_line', 'city', 'province', 'post_code', 'website', 'date_first', 'date_updated', 'note', 'active'))
{
//	$tbl = 'business';
//	$els = array('business_name', 'email', 'street_address', 'address_line', 'city', 'province', 'post_code', 'website', 'date_first', 'date_updated', 'note', 'active');
	/*
		INSERT INTO `business`(`business_id`, `business_name`, `email`, `street_address`, `address_line2`, `city`, `province`, `post_code`, `website`, `date_first`, `date_updated`, `note`, `active`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]','[value-12]','[value-13]')
	*/
	if (!filter_input(INPUT_POST, $id)) {		// add new db entry
		$sql = 'INSERT INTO `'. $tbl. '` (';
//		$ins = '';
	//	echo '<pre>processField($fields) : '. print_r($fields, true). '</pre>';
/*		foreach ($fields as $k => $v) {
		//echo '<pre>processField($v) : '. print_r($v, true). '</pre>';
//			if (!isset($v['name']) || empty($v['name']) || (isset($v['id']) && $v['id']) || $v['name'] == 'submit') {
			if (!isset($v['name']) || !in_array($els, $v['name'])) {
				continue;
			} else {
//				$ins .= '`'. $v['name']. '`, ';
				$sql .= '`'. $v['name']. '`, ';
			}
		}*/
		foreach ($els as $el) {
			$sql .= '`'. $el/*v['name']*/. '`, ';
		}
/*		if (empty($ins)) {
			return;
		}
		$ins = substr($ins, 0, -2);*/
		$sql = substr($sql, 0, -2). ') VALUES ('; // remove last comman and space ; close fields section ; start values section
//		$sql .= $ins. ') VALUES (';	// remove last comman and space ; close fields section ; start values section
		//foreach ($POST as $k => $v) {
		//	if ($k ==
/*		foreach ($fields as $k => $v) {
			if (!isset($v['name']) || empty($v['name']) || (isset($v['id']) && $v['id']) || $v['name'] == 'submit') {
				continue;
			}
			$filter = isset($v['filter']) ? $v['filter'] : FILTER_DEFAULT;
			$sql .= (isset($v['array']) && $v['array']) ?
				"'". filter_input(INPUT_POST, $v['name'], $filter, FILTER_REQUIRE_ARRAY). "', " :
				"'". filter_input(INPUT_POST, $v['name'], $filter). "', ";
		}
		$sql = substr($sql, 0, -2);
		$sql .= ')';	// remove last comman and space ; close values section
 */
		foreach ($els as $el) {
/*			$filter = isset($v['filter']) ? $v['filter'] : FILTER_DEFAULT;
			$sql .= (isset($v['array']) && $v['array']) ?
				"'". filter_input(INPUT_POST, $v['name'], $filter, FILTER_REQUIRE_ARRAY). "', " :
				"'". filter_input(INPUT_POST, $v['name'], $filter). "', ";*/
			$try = filter_input(INPUT_POST, $el, FILTER_DEFAULT);
			if (!$try) {
				$try = filter_input(INPUT_POST, $el, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
			}
			$sql .= "'{$try}', ";
		}
/*		if (empty($ins)) {
			return;
		}
		$ins = substr($ins, 0, -2);*/
		$sql = substr($sql, 0, -2). ');'; // remove last comman and space ; close values section
	} else {
/*
UPDATE `business` SET `business_id`='[value-1]',`business_name`='[value-2]',`email`='[value-3]',`street_address`='[value-4]',`address_line2`='[value-5]',`city`='[value-6]',`province`='[value-7]',`post_code`='[value-8]',`website`='[value-9]',`date_first`='[value-10]',`date_updated`='[value-11]',`note`='[value-12]',`active`='[value-13]' WHERE 1
*/
		$sql = 'UPDATE `'. $tbl. '` SET ';
/*		foreach ($fields as $k => $v) {
			if (!isset($v['name']) / *|| (isset($v['id']) && !$v['id'])* /) {
				continue;
			}
			$sql .= '`'. $v['name']. '`='. "'";
			$sql .= "'";
		}
		$sql = substr($sql, 0, -2). ') VALUES (';	// remove last comman and space ; close fields section ; start values section
*/
		foreach ($els as $el) {
			$sql .= '`'. $v['name']. '`=';
/*			$filter = isset($v['filter']) ? $v['filter'] : FILTER_DEFAULT;
			$sql .= (isset($v['array']) && $v['array']) ?
				"'". filter_input(INPUT_POST, $v['name'], $filter, FILTER_REQUIRE_ARRAY). "', " :
				"'". filter_input(INPUT_POST, $v['name'], $filter). "', ";*/
			$try = filter_input(INPUT_POST, $el, FILTER_DEFAULT);
			if (!$try) {
				$try = filter_input(INPUT_POST, $el, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
			}
			$sql .= "'{$try}', ";
		}
		$sql = substr($sql, 0, -2). ' WHERE '. $id. '='. filter_input(INPUT_POST, $id). ';'; // remove last comman and space ; close values section ; add WHERE clause
	}
	return $sql;
//	echo 'doing '. $sql. ';'. "<br>\n";
}

// when the form is submitted, process the fields
	//echo '<pre>form = '. print_r($form, true). '</pre>';
if (isset($_POST['submit'])) {
	foreach ($forms as $form => $vals) {
//		echo '<pre>$arr : '. $arr. '</pre>';
//		echo '<pre>$sect : '. print_r($sect, true). '</pre>';
		if (isset($vals['fields']) && is_array($vals['fields'])) {
			//		echo '<pre>$arr[\'fields\'] : '. print_r($sect/*arr['fields']*/, true). '</pre>';
//			echo 'root fields';
			validateFields($vals['fields']);
		}
		if (isset($vals['fieldsets']) && is_array($vals['fieldsets'])) {
//			echo '<pre>arr[fieldsets] : '. print_r($sect/*arr['fieldsets']*/, true). '</pre>';
			foreach ($vals['fieldsets'] as $fieldset => $val) {
				if (isset($val['fields']) && is_array($val['fields'])) {
//					echo 'in fieldsets fields';
					validateFields($val['fields']);
				}
			}
		}
	}
	$sql = makeSQL('business', 'business_id', array('business_name', 'email', 'street_address', 'address_line', 'city', 'province', 'post_code', 'website', 'date_first', 'date_updated', 'note', 'active'));
//	$sql = makeSQL();
	echo 'doing '. $sql. "<br>\n";
	$sql = makeSQL('contact', 'contact_id', array('contact_first', 'contact_last', 'contact_type', 'contact_number'));
	echo 'doing '. $sql. "<br>\n";
}


/***********************************************/
/**
 * Output the from(s) to the screen,
 * using the value in the global array
 */
/*function renderField(array $v)
{
	ob_start();
?>	<div class="form_field">
<?php
	if (isset($v['label']) && !empty($v['label'])) {
?>		<label <?=$v['label']?>"<?php if (isset($v['id'])) echo ' for="'. $v['id']. '"' ?>>
<?php
	}
	if (/ *$show_req &&* / isset($v['required'])) {
?>		<span class="req"></span>
<?php
	}
	if (isset($v['type']) && $v['type'] == 'select') {
?>		<select<?php
		echo ' name="'. (isset($v['name']) ? $v['name'] : 'name_'. $i). '"';
		if (isset($v['placeholder'])) {
			echo ' placeholder="'. $v['placeholder']. '"';
		}
		if (isset($v['class'])) {
			echo ' class="'. $v['class']. '"';
		}
		if (isset($v['value'])) {
			echo ' value="'. $v['value']. '"';
		}
		echo ">\n";
		if (isset($v['options']) && is_array($v['options'])) {
			foreach ($v['options'] as $n => $opt) {
?>			<option<?php
				echo ' id="'. (isset($opt['id']) ? $opt['id'] : 'option_'. ($n + 1)). '"';
				if (isset($opt['selected']) && $opt['selected']) {
					echo ' selected="selected"';
				}
				if (isset($opt['class'])) {
					echo ' class="'. $opt['class']. '"';
				}
//				if (isset($opt['value'])) {
//					echo ' value="'. $opt['value']. '"';
//				}
				echo '>';
				if (isset($opt['name'])) {
					echo $opt['name'];
				}
				echo "</option>\n";
			}
		}
		echo "\t\t</select>\n";
	} elseif (isset($v['type']) && $v['type'] == 'textarea') {
		// not implemented
	} else {
		$i = 0;
?>
		<input type="<?=(isset($v['type']) ? $v['type'] : 'text')?>"<?php
		echo ' name="'. (isset($v['name']) ? $v['name'] : 'name_'. $i). '"';
		if (isset($v['placeholder'])) {
			echo ' placeholder="'. $v['placeholder']. '"';
		}
		if (isset($v['class'])) {
			echo ' class="'. $v['class']. '"';
		}
		if (isset($v['value'])) {
			echo ' value="'. $v['value']. '"';
		}
		if (isset($v['autofocus']) / *&& $v['autofocus']* /) {
			echo ' autofocus="'. $v['autofocus']. '"';
		}
		echo ">\n";
		$i++;
	}
	if (/ *$show_desc &&* / isset($v['description']) && (!isset($v['hide_description']) || !$v['hide_description'])) {
?>		<div class="description"><?=$v['description']?></div>
<?php
	}
?>	</div>
<?php
	$out = ob_get_contents();
	ob_clean();
	return $out;
}*/
/***********************************************/
	Scripts::getInstance()->enqueue(Array( 'name' => 'addcontact', 'src' => BASE. 'js/addcontact.js', 'version' => filemtime('js/addcontact.js') ));
	echo Breadcrumbs::here();	?>
<!--<head>
	<link rel="stylesheet" href="basic.css" type="text/css">
</head>-->
<?php
/**
 * Draw form
 */
	if (isset($_POST['submit'])) {
		echo '<div class="errors">'. "\n";
		foreach ($errors as $err) {
			echo "\t<p class=\"error\">{$err}</p>\n";
		} 
		echo "</div>\n";
	}
	foreach ($forms as $num => $form) {
//			echo '<pre>form : '. print_r($form/*arr['fieldsets']*/, true). '</pre>';
?>	<form id="<?php echo isset($form['id']) ? $form['id'] : 'form_'. ($num+1);?>"<?php
//		if (isset($form['id'])) {
//			echo ' id="'. $form['id']. '"';
//		}
		if (isset($form['method'])) {
			echo ' method="'. $form['method']. '"';
		}
		if (isset($form['class'])) {
			echo ' class="'. $form['class']. '"';
		}
		if (isset($form['action'])) {
			echo ' action="'. $form['action']. '"';
		}
		echo ">\n";
		if (isset($form['fieldsets']) && is_array($form['fieldsets'])) {
			foreach ($form['fieldsets'] as $fieldset => $val) {
				echo Fieldset::open($val);
				if (isset($val['fields']) && is_array($val['fields'])) {
//			echo '<pre>fieldset : '. print_r($val/*form['fieldsets']*/, true). '</pre>';
					$i = 0;
					foreach ($val['fields'] as $k => $v) {
//						if (isset($v['type']) && $v['type'] == 'hidden') {
//							continue;
//						}
						//echo "$k ";
						if (isset($v['fieldset'])) {
							if (!isset($v['id'])) {
								$v['id'] = 'fieldset_'. $i;
							}
							if ($v['fieldset'] == 'open') {
								echo Fieldset::open($v);
							} elseif ($v['fieldset'] == 'close') {
								echo Fieldset::close();
							}
						} else {
							if (!isset($v['id'])) {
								$v['id'] = 'field_'. $i;
							}
							echo Field::out($v);
						}
//						renderField($v);
						$i++;
					}// end foreach fields
				}// end if fields
				echo Fieldset::close();
			}// end foreach fieldsets
		}// end if fieldset
		if (isset($form['fields']) && is_array($form['fields'])) {
			foreach ($form['fields'] as $k => $v) {
				//echo renderField($v);
				echo Field::out($v);
			}
		}
?>	</form>
<?php
	}//end foreach form
?>
<!--		Password:
		<input type="password" name="password"/>
		<span style="color:red;">*</span>
		<br>
		<br> 
		Gender:
		<input type="radio" 
		       value="Male" 
		       name="gender"> Male
		<input type="radio" 
		       value="Female"
		       name="gender">Female
		<br>
		<br>
		<input type="submit" value="Submit" name="submit" />
	</form>
</fieldset>-->
