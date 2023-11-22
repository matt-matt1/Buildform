<?php

include_once ('businessform.php');
/*
if (isset($_POST['addnew'])) {
	//Redirect( BASE. 'addnew' );
	include ('business/addnew.php');
}
 */
// attach the select options made from the list of types (in the database) into the "contact_types" array in the form
$types = ContactType::getAll();
$value = array();
foreach ($types as $k => $v) {
	foreach ($v as $key => $val) {
		$id = $v['type_id'];
		$value[$id] = $v['name'];
	}
}
//		echo '<pre>contact_type options : '. print_r($value, true). '</pre>';
insertInArray($forms, 'contact_type_', $value, 'options');
//		echo 'forms:<pre>'. print_r($forms, true). '</pre>';

$errors = array();

// when the form is submitted, process the fields
	//echo '<pre>form = '. print_r($form, true). '</pre>';
if (isset($_POST['action'])) {
/*	if (isset($_POST['business_name'])) {
		$ask = new Note(Array(
			'type' => Note::noticet,
			'canIgnore' => true,
			'ignore' => 'Save',
			'message' => sprintf( t("_Do you want to save changes to %s?"), filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT)),
		));
		echo $ask->display();
	}*/
	switch ($_POST['action']) {
/*	case 'admin':
		Redirect( BASE. 'business/admin' );
		break;*/
	case 'add-contact':
		addContactFieldSet();
		return;
		break;
	}
}
if (isset($_POST['business_name'])) {
	foreach ($forms as $form => $vals) {
//		echo '<pre>$arr : '. $arr. '</pre>';
//		echo '<pre>$sect : '. print_r($sect, true). '</pre>';
		if (isset($vals['inner']) && is_array($vals['inner'])) {
			//		echo '<pre>$arr[\'fields\'] : '. print_r($sect/*arr['fields']*/, true). '</pre>';
//			echo 'root fields';
			validateFields($vals['inner']);
		}
		if (isset($vals['fieldsets']) && is_array($vals['fieldsets'])) {
//			echo '<pre>arr[fieldsets] : '. print_r($sect/*arr['fieldsets']*/, true). '</pre>';
			foreach ($vals['fieldsets'] as $fieldset => $val) {
				if (isset($val['inner']) && is_array($val['inner'])) {
//					echo 'in fieldsets fields';
					validateFields($val['inner']);
				}
			}
		}
	}
	$bus = new Business;
	$bus->getFromPOST();
	$id = $bus->insert();
	//$msg = is_integer(filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT)) ? 'Inserted new' : 'Updated';
	$msg = !is_numeric(filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT)) ? 'Inserted new' : 'Updated';
//	echo $msg. ' business: <pre>'. print_r($array, true). '</pre>'. "<br>\n";
	$doneNote = new Note(Array(
		'type' => Note::notice,
		'message' => sprintf( t("_{$msg} business %s"), filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT)),
		'details' => "<br>\n". sprintf( t('_Query %s'), $bus->getLastSQL() ),
	));
		//1999-12-31 23:59:59
	$contacts = filter_input(INPUT_POST, 'contact_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
	$row = 0;
	foreach ($contacts as $k => $v) {
		$contact = new Contact;
		$contact->getFromPOST($id);
		$contact->insert($id, $row);
		$doneNote->set(array(
			'message' => $doneNote->getParams()['message']. ', '. sprintf( t("_with contact %s"), $row )
		));
		$doneNote->set(array(
			'details' => $doneNote->getParams()['details']. "<br>\n". sprintf( t('_Query %s'), $contact->getLastSQL() )
		));
		$row++;
	}
	echo $doneNote->display();
//	}
	return;
//	if (isset($_POST['action'])) {
//	/*	if (isset($_POST['business_name'])) {
//			$ask = new Note(Array(
//				'type' => Note::noticet,
//				'canIgnore' => true,
//				'ignore' => 'Save',
//				'message' => sprintf( t("_Do you want to save changes to %s?"), filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT)),
//			));
//			echo $ask->display();
//		}*/
//		switch ($_POST['action']) {
///*		case 'add-business':
//			Redirect( BASE. 'business' );
//			break;*/
//		case 'add-contact':
//			addContactFieldSet();
//			break;
//		}
//	}
} else {	// not submitted yet
/*	$url = rtrim(getServerValue('REQUEST_URI'), '/');	// get the REQUEST_URI without the trailing slash (if any)
//	$url = getServerValue('PHP_SELF');
	$array = explode('/', $url);	// separate the URL into parts
*/
	if (!isset($_GET['business'])/*end($array) == 'business'*/) {
		insertInArray($forms, 'business_name', 'input autocomplete', 'inputWrapClass');
	} elseif (is_numeric($_GET['business'])) {	// if last url segment is a number get that business
		$bus = new Business;
		$business = $bus->getById(intval($_GET['business']))[0];
		foreach ($business as $k => $v) {
			insertInArray($forms, $k, $v, 'value');		// insert the business values
		}
		$contact = new Contact;
		$contacts = $contact->getForBusinessId($business['business_id']);	// get the associated contacts
		//echo '<pre>contacts : '. print_r($contacts, true). '</pre>';
		$i = 0;
		foreach ($contacts as $contact) {
			if ($i > 0) {
				//echo 'adding contact#'. $i. "<br>\n";
				addContactFieldSet($forms, $i);		// append a new contact fieldset
			}
	//		echo '<pre>contact: '. print_r($contact, true). '</pre>';
			$array = array('contact_id' => 'contact_id_', 'first' => 'contact_first_', 'last' => 'contact_last_', 'type_id' => 'contact_type_', 'number' => 'contact_number_', 'date_created' => 'contact_date_');
			foreach ($contact as $key => $val) {
				//echo '<pre>contacts looking for "'. $key. '" inserting value='. $val. '</pre>';
				if (isset($array[$key])) {
					$findkey = $array[$key]. $i;	// translate the keys (from the database) with the fieldset id
					//contact_first_0 = contact_first_0
	//			echo 'contact#'. $i. ' - looking for "'. $findkey. '" inserting value='. $val. "<br>\n";
					insertInArray($forms, $findkey, $val, 'value');		// insert the values
				}
			}
			$i++;
		}
	}
}

/**
 * Perform basic validation for fields
 */
if (!function_exists('validateFields')) :
function validateFields($fields)
{
	//	echo '<pre>processField($fields) : '. print_r($fields, true). '</pre>';
	foreach ($fields as $k => $v) {
	//	echo '<pre>processField($fields-$k) : '. print_r($k, true). '</pre>';
	//	echo '<pre>processField($fields-$v) : '. print_r($v, true). '</pre>';
		if (isset($v['name'])) {
	//		echo 'validating : '. $v['name']. ' :'. "<br>\n";
			if (isset($v['required']) && $v['required']) {
	//			echo 'validating : '. $v['name']. ' :'. "\n";
				if (isset($v['description'])) {
					$miss = $v['description'];
				}
				if (!isset($miss) && isset($v['label'])) {
					$miss = $v['label'];
				}
				if (!isset($miss) && isset($v['name'])) {
					$miss = $v['name'];
				}
				if (isset($miss)) {
					$errors[] = "Missing '{$miss}'";
				}
			}
		}
	}
}
endif;

/***********************************************/
/**
 * Output the from(s) to the screen,
 * using the value in the global array
 */
/***********************************************/
	Scripts::getInstance()->enqueue(Array( 'name' => 'autocomplete', 'src' => BASE. 'js/autocomplete.js', 'version' => filemtime('js/autocomplete.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MakeCopy', 'src' => BASE. 'js/MakeCopy.js', 'version' => filemtime('js/MakeCopy.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'resetForm', 'src' => BASE. 'js/resetForm.js', 'version' => filemtime('js/resetForm.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'business', 'src' => BASE. 'js/business.js', 'version' => filemtime('js/business.js') ));
	//Link::style(Array( 'name' => 'autocomplete', 'href' => BASE. '../business/autocomplete.css', 'version' => filemtime('../business/autocomplete.css') ));
	Link::style(Array( 'name' => 'business', 'href' => BASE. 'css/business.css', 'version' => filemtime('css/business.css') ));
//	Link::style(Array( 'name' => 'autocomplete', 'href' => BASE. 'css/autocomplete.css', 'version' => filemtime('css/autocomplete.css') ));
	//Styles::getInstance()->enqueue(Array( 'name' => 'autocomplete', 'href' => BASE. 'css/autocomplete.css', 'version' => filemtime('css/autocomplete.css') ));
	/*echo Breadcrumbs::here();*/	?>
<!--<head>
	<link rel="stylesheet" href="basic.css" type="text/css">
</head>-->
<?php
/**
 * Draw form
 */
	if (isset($_POST['business_name']) && !empty($errors)) {
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
		renderFields($form['inner']);
?>	</form>
<?php
	}//end foreach form
