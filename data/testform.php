<?php

	Scripts::getInstance()->enqueue(Array( 'name' => 'autocomplete2', 'src' => BASE. 'js/autocomplete2.js', 'version' => filemtime('js/autocomplete2.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MakeCopy', 'src' => BASE. 'js/MakeCopy.js', 'version' => filemtime('js/MakeCopy.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'resetForm', 'src' => BASE. 'js/resetForm.js', 'version' => filemtime('js/resetForm.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'business', 'src' => BASE. 'js/business.js', 'version' => filemtime('js/business.js') ));
	//Link::style(Array( 'name' => 'business', 'href' => BASE. 'css/business.css', 'version' => filemtime('css/business.css') ));
	
// attach the select options made from the list of types (in the database) into the "contact_types" array in the form
$types = ContactType::getAll();
$value = array();
foreach ($types as $k => $v) {
	foreach ($v as $key => $val) {
		$id = $v['type_id'];
		$value[$id] = $v['name'];
	}
}
include_once ('userform.php');
insertInArray($forms, 'contact_type_', $value, 'options');
$form_usr = new HTMLForm($forms);	

				//echo '<div class="panel">'. $form_usr->render(). '</div>';
				echo $form_usr->render();
