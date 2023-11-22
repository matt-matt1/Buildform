<?php

	/*	`*/
$forms = array(
	array(
		'id' => 'admin_form',
		'tag' => 'form',
		'method' => 'post',
		'action' => 'admin',
		'showReq' => false,
		'showDesc' => true,
		'extra' => 'autocomplete="off"',
		'inner' => array(
			array(
				'tag' => 'button',
				'style' => 'position:absolute',
//				'style' => 'height:2.5em',
				//'type' => "button",
				'type' => "submit",
				'name' => 'action',
				'value' => 'admin',
				'class' => 'btn btn-primary adminButton',
				'id' => 'admin-button',
				'innerText' => 'Management',
				'selfClose' => true,
				'inner' => array(
					array(
						'tag' => 'i',
						'class' => 'fa-solid fa-bars-progress fa-2xl',
						'style' => 'padding-left: 0.5em;',
						'selfClose' => true,
					),
				),
			),
		),
	),
	array(
		'id' => 'sent_form',
		'tag' => 'form',
		'method' => 'post',
		'showReq' => false,
		'showDesc' => true,
		'extra' => 'autocomplete="off"',
		'inner' => array(//0
			array(//1
				'tag' => 'fieldset',
				'id' => 'sent',
				'class' => 'main-fieldset',
				'legend' => 'Sent',
				'inner' => array(//2
					array(
						'tag' => 'button',
						'style' => 'position:absolute',
//						'style' => 'height:2.5em',
						//'type' => "button",
						'type' => "submit",
						'name' => 'action',
						'value' => 'add-sent',
						'class' => 'btn btn-primary addButton',
						'id' => 'add-sent',
						'innerText' => 'Add new sent',
						'selfClose' => true,
						'inner' => array(
							array(
								'tag' => 'i',
								'class' => 'fa-solid fa-circle-plus fa-2xl',
								'style' => 'padding-left: 0.5em;',
								'selfClose' => true,
							),
						),
					),
					'sent_id' => array(//4
						'id' => 'sent_id',
						'type' => 'hidden',
//						'id' => true,
//						'noLabel' => true,
						'optional' => true,
						'showOptional' => false,
					),
					'sent_name' => array(//3
						'id' => 'sent_name',
						'type' => 'text',
						'noLabel' => true,
						//'optional' => true,
						'showOptional' => false,
						//'canWipe' => true,
						'placeholder' => 'Sent name - search as you type',
//						'inputWrapClass' => 'has-feedback',
						'default_' => 'Search for a Sent name - start typing - select a suggestion',
						//'default_' => 'Search for a Business name - start typing',
						'defaultExtra' => 'style="margin:0 1em"',
						//'inputWrapClass' => 'input has-feedback form-group autocomplete',
						//'inputWrapClass' => 'input autocomplete',
						'inputWrapClass' => 'input',
						'inputWrapExtra' => 'style="width:100%"',
						'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
						'rowExtra' => 'style="float:left;margin:0 1.25em"',
						'extra' => 'autofocus inputmode="search"',
					),
					'sent_active' => array(
						'id' => 'sent_active',
//						'placeholder' => 'Yes',
						//'noLabel' => true,//'label' => false,
//						'description' => 'Business active',
						'label' => 'Sent active',
//						'after' => 'Business active',
						'type' => 'checkbox',
						'rowClass' => 'row g-2 mb-4 form-switch',
						//'label_class' => 'right',
						'inputClass' => 'big-check',
				//		'class' => 'switch',
						'inputWrapClass' => 'input',
						'rowExtra' => 'style="float:right;margin:0 1.5em"',
						//'row_class' => 'ml-2 col-auto g-2',
						'required' => true,
						'optional' => true,
						'showOptional' => false,
					),
					'sent_email' => array(
						'id' => 'sent_email',
						'placeholder' => 'sales@yumatechnical.com',
						//'noLabel' => true,//'label' => false,
					//	'description' => 'email address',
						'label' => 'email address',
						'default_' => 'email address',
						'type' => 'text',
						'inputmode' => 'email',
						'rowExtra' => 'style="clear:both"',
						'rowClass' => "row mb-2 g-2",
						'inputWrapClass' => 'input col-md-8 col-sm-12',
						//'type' => 'email',
						'optional' => true,
						'showOptional' => false,
					),
					'sent_website' => array(
						'id' => 'sent_website',
						'rowClass' => "row mb-2 g-2",
						'placeholder' => 'https://yumatechnical.com',
						'noLabel' => true,//'label' => false,
					//	'description' => 'Website address',
						'default_' => 'Website address',
						'type' => 'text',
						'rowExtra' => 'style="clear:both"',
						'inputWrapClass' => 'input col-md-8 col-sm-12',
						'optional' => true,
						'showOptional' => false,
					),
					'sent_created' => array(
						'id' => 'sent_created',
						//'row_class' => "row mb-2 g-2",
						'placeholder' => '2016-10-26 12:34:56',
						'noLabel' => true,//'label' => false,
//						'description' => 'Creation date',
						'default_' => 'Creation date',
						'defaultExtra' => 'style="margin:0 1em"',
						//'type' => 'text',
						'type' => 'datetime-local',
						'extra' => 'disabled',
						'optional' => true,
						'showOptional' => false,
						'inputWrapClass' => 'input col-md-12',
						'rowClass' => 'ml-2 col-md-5 g-2',
						'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
					),
					'sent_updated' => array(
						'id' => 'sent_updated',
						'placeholder' => '2023-08-23 09:18:27',
						'noLabel' => true,//'label' => false,
//						'description' => 'Last updated',
						'default_' => 'Last updated',
						'defaultExtra' => 'style="margin:0 1em"',
						//'type' => 'text',
						'type' => 'datetime-local',
						'optional' => true,
						'showOptional' => false,
						'inputWrapClass' => 'input col-md-12',
						'rowClass' => 'ml-2 col-md-5 g-2',
						'rowExtra' => 'style="float:left;margin:0 1.25em"',
					),
					'sent_note' => array(
						//'name' => 'note',
						'id' => 'sent_note',
						'rowClass' => "row mb-2 g-2",
						'placeholder' => 'This is a good sent.',
						'noLabel' => true,//'label' => false,
//						'description' => 'Notes',
						'default_' => 'Notes',
						'type' => 'textarea',
						'rowClass' => "row mb-2 g-2 row-cols-2 textarea5",
						'optional' => true,
						'showOptional' => false,
//						'default_extra' => 'style="margin:0 1em"',
						'inputWrapClass' => 'input col-md-12',
//						'row_class' => 'ml-2 col-auto g-2',
						'rowExtra' => 'style="clear:both;margin:0 1.125em"',
					),
					array(
						'id' => 'contacts',
						'tag' => 'fieldset',
						//'class' => 'contacts',
						'legend' => 'Contacts',
//					),
						'inner' => array(
							'Add-new-contact' => array(
								'tag' => 'button',
								'style' => 'position:absolute',
	//							'style' => 'height:2.5em',
								//'type' => "button",
								'type' => "submit",
								'name' => 'action',
								'value' => 'add-contact',
								'class' => 'btn btn-secondary addButton',
								'id' => 'add-contact',
								'innerText' => 'Add new contact',
								'selfClose' => true,
								'inner' => array(
									array(
										'tag' => 'i',
										'class' => 'fa-solid fa-circle-plus fa-2xl',
										'style' => 'padding-left: 0.5em;',
										'selfClose' => true,
									),
								),
							),
							'fieldset0' => array(
								'tag' => 'fieldset',
								'id' => 'div-contact_0',
								'class' => 'contact',
								'inner' => array(
									'contact_id_0' => array(
										'id' => 'contact_id_0',
										'inputClass' => 'contact_id',
										'name' => 'contact_id[]',
										//'name' => 'contact[id][]',
										'type' => 'hidden',
				//						'noLabel' => true,
										'optional' => true,
										'showOptional' => false,
									),
									'contact_first_0' => array(
										'id' => 'contact_first_0',
										'name' => 'contact_first[]',
										//'name' => 'contact[first][]',
										'rowClass' => 'ml-2 col-md-5 g-2',
										'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
										'placeholder' => 'Yuma',
										'noLabel' => true,//'label' => false,
									//	'description' => 'Contact first name',
										'default_' => 'Contact first name',
										'defaultExtra' => 'style="margin:0 1em"',
										'hideDescription' => false,
										'type' => 'text',
										//'default' => '',
										'inputWrapClass' => 'input col-md-12',
										//'input_wrap_class' => 'input col-md-6',
										//'required' => true,
										'optional' => true,
										'showOptional' => false,
									),
									'contact_last_0' => array(
										'id' => 'contact_last_0',
										'name' => 'contact_last[]',
										//'name' => 'contact[last][]',
										'placeholder' => 'Ttechnical',
										'noLabel' => true,//'label' => false,
									//	'description' => 'Contact last name',
										'default_' => 'Contact last name',
										'defaultExtra' => 'style="margin:0 1em"',
										'type' => 'text',
										'noLabel' => true,
										'inputWrapClass' => 'input col-md-12',
										//'input_wrap_class' => 'input col-md-6',
										'rowClass' => 'ml-2 col-md-5 g-2',
										'rowExtra' => 'style="float:left;margin:0 1.25em"',
										'optional' => true,
										'showOptional' => false,
									),
									'contact_type_0' => array(
										'id' => 'contact_type_0',
										'name' => 'contact_type[]',
										//'name' => 'contact[type][]',
										'type' => 'select',
										'inputClass' => 'contact_type',
										'noLabel' => true,
										'optional' => true,
										'showOptional' => false,
				//						'description' => 'Contact method',
										'default_' => 'Contact method',
										'defaultExtra' => 'style="margin:0 1em"',
										'inputWrapClass' => 'input col-md-12',
										'rowClass' => 'ml-2 col-md-3 g-2',
										'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
									),
									'contact_number_0' => array(
										'id' => 'contact_number_0',
										'name' => 'contact_number[]',
										//'name' => 'contact[number][]',
										'placeholder' => '+1 (647) 956-6145',
										'noLabel' => true,
				//						'description' => 'Contact number',
										'default_' => 'Contact number',
										'defaultExtra' => 'style="margin:0 1em"',
										//'type' => 'text',
										'type' => 'tel',
										'inputmode' => "tel",
										'optional' => true,
										'showOptional' => false,
										'inputWrapClass' => 'input col-md-12',
										'rowClass' => 'ml-2 col-md-4 g-2',
										'rowExtra' => 'style="float:left;margin:0 1.25em"',
									),// end this field
									'contact_created_0' => array(
										'id' => 'contact_created_0',
										'name' => 'contact_created[]',
										//'name' => 'contact[date][]',
										'placeholder' => '2014-10-26 23:59:59',
										'noLabel' => true,
				//						'description' => 'Contact number',
										'default_' => 'Contact date',
										'defaultExtra' => 'style="margin:0 1em"',
										//'type' => 'text',
						'type' => 'datetime-local',
										'optional' => true,
										'showOptional' => false,
										'inputWrapClass' => 'input col-md-12',
										'rowClass' => 'ml-2 col-md-4 g-2',
										'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
									),// end this field
									'contact_updated_0' => array(
										'id' => 'contact_updated_0',
										'name' => 'contact_updated[]',
//										'placeholder' => '2023-08-23 09:18:27',
										'noLabel' => true,//'label' => false,
				//						'description' => 'Last updated',
										'default_' => 'Last updated',
										'defaultExtra' => 'style="margin:0 1em"',
										//'type' => 'text',
						'type' => 'datetime-local',
										'optional' => true,
										'showOptional' => false,
										'inputWrapClass' => 'input col-md-12 autocomplete-contacts',
										'rowClass' => 'ml-2 col-md-5 g-2',
										'rowExtra' => 'style="float:left;margin:0 1.25em"',
									),
									'deleteMe_0' => array(
										'tag' => 'button',
										'type' => 'button',
										'id' => 'deleteMe_0',
										'name' => 'removeContact',
										'style' => 'height:2.5em',
										'innerText' => 'Remove',
										'class' => 'btn btn-danger deleteMe hide',
										'selfClose' => true,
										'inner' => array(
											array(
												'tag' => 'i',
												'class' => 'fa-solid fa-circle-minus fa-2xl',
												'style' => 'padding-left: 0.5em;',
												'selfClose' => true,
											),
										),
									),
								),// end this inner
							),// end div-inner
						),// end div
					),// end fieldset-inner
				),// end this fieldset
			),// fieldset-inner
			array(
				'submit' => array(
					'id' => 'submit',
//					'name' => 'submit',
					'value' => 'Submit',
					'type' => 'submit',
				),// submit field
			),
			array(
				'reset' => array(
					'id' => 'reset',
//					'name' => 'submit',
					'value' => 'Clear all',
//					'style' => 'float:right',
					'type' => 'reset',
				),// submit field
			),
/*			array(
				'remove' => array(*/
/*							<td class="symbol"><a data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=$dbname
?>" data-title="<?=$title?>" data-body="<?=$body
?>" data-modal-button-del="value:proceed" data-modal-button-add="node:button,type:submit,name:modal-button,value:deletedb,class:btn btn-danger no-button,aria-label:<?=t('Delete')?>,data-url:<?=$href. $dbname?>?remove,innerText:<?=t('Delete')/ *,data-url:<?=$href?>* /
?>" class="symbol modal-trigger" href="<?=$href. $dbname
?>?remove" title="<?=sprintf( t("_Remove database %s"), / *squote(* /$dbname/ *)* / );
?>"><span class="symbol delete"></span></a></td>*/
/*					'tag' => 'a',
					'data-bs-toggle' => 'modal',
					'data-bs-target' => '#myModal',
					'data-name' => '',
					'data-modal-button-del' => 'value:proceed',
					'data-modal-button-add' => 'node:button,type:submit,name:modal-button,value:deletedb,class:btn btn-danger no-button,aria-label:,data-url:?remove,innerText:,data-url:',
					'class' => 'symbol modal-trigger',
					'href' => '?remove',
					'title' => '',
					'inner' => array(
						array(
							'tag' => 'span',
							'class' => 'symbol delete',
							'selfClose' => true,
						),
					),*/
/*					'tag' => 'button',
					'id' => 'remove-business',
					'type' => "button",
					'class' => 'danger',
//					'name' => 'submit',
					'value' => 'remove',
					'innerText' => 'Remove this business',*/
					//'type' => 'submit',
/*				),// submit field
 			),
 */		),// form inner
	),// end this form
);

if (!function_exists('addContactFieldSet')) :
function addContactFieldSet(array &$array, int $id=1)
{
	echo 'PROBLEM: output from getInnerOfArray function != $contact_inner (in addContactFieldSet - business/forms.php)'. "<br>\n";
//	$contact_inner = Array();
	$contact_inner = getInnerOfArray($array, 'id', 'contacts');
//	unset($contact_inner['Add-new-contact']);
	echo 'contact_inner: <pre>'. print_r($contact_inner, true). '</pre>'. "\n";
//	return;
	$contact_innerBAK = array(
		array(
			'tag' => 'fieldset',
			'id' => 'div-contact_0',
			'class' => 'contact',
			'inner' => array(
				'contact_id' => array(
					'id' => 'contact_id_0',
					'inputClass' => 'contact_id',
					'name' => 'contact_id[]',
					//'name' => 'contact[id][]',
					'type' => 'hidden',
//					'noLabel' => true,
					'optional' => true,
					'showOptional' => false,
				),
				'contact_first_0' => array(
					'id' => 'contact_first_0',
					'name' => 'contact_first[]',
					//'name' => 'contact[first][]',
					'rowClass' => 'ml-2 col-md-5 g-2',
					'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
					'placeholder' => 'Yuma',
					'noLabel' => true,//'label' => false,
				//	'description' => 'Contact first name',
					'default_' => 'Contact first name',
					'defaultExtra' => 'style="margin:0 1em"',
					'hideDescription' => false,
					'type' => 'text',
					//'default' => '',
					'inputWrapClass' => 'input col-md-12',
					//'input_wrap_class' => 'input col-md-6',
					//'required' => true,
					'optional' => true,
					'showOptional' => false,
				),
				'contact_last_0' => array(
					'id' => 'contact_last_0',
					'name' => 'contact_last[]',
					//'name' => 'contact[last][]',
					'placeholder' => 'Ttechnical',
					'noLabel' => true,//'label' => false,
				//	'description' => 'Contact last name',
					'default_' => 'Contact last name',
					'defaultExtra' => 'style="margin:0 1em"',
					'type' => 'text',
					'noLabel' => true,
					'inputWrapClass' => 'input col-md-12',
					//'input_wrap_class' => 'input col-md-6',
					'rowClass' => 'ml-2 col-md-5 g-2',
					'rowExtra' => 'style="float:left;margin:0 1.25em"',
					'optional' => true,
					'showOptional' => false,
				),
				'contact_type_0' => array(
					'id' => 'contact_type_0',
					'name' => 'contact_type[]',
					//'name' => 'contact[type][]',
					'type' => 'select',
					'inputClass' => 'contact_type',
					'noLabel' => true,
					'optional' => true,
					'showOptional' => false,
//					'description' => 'Contact method',
					'default_' => 'Contact method',
					'defaultExtra' => 'style="margin:0 1em"',
					'inputWrapClass' => 'input col-md-12',
					'rowClass' => 'ml-2 col-md-3 g-2',
					'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
				),
				'contact_number_0' => array(
					'id' => 'contact_number_0',
					'name' => 'contact_number[]',
					//'name' => 'contact[number][]',
					'placeholder' => '+1 (647) 956-6145',
					'noLabel' => true,
//					'description' => 'Contact number',
					'default_' => 'Contact number',
					'defaultExtra' => 'style="margin:0 1em"',
					//'type' => 'text',
					'type' => 'tel',
					'inputmode' => "tel",
					'optional' => true,
					'showOptional' => false,
					'inputWrapClass' => 'input col-md-12',
					'rowClass' => 'ml-2 col-md-4 g-2',
					'rowExtra' => 'style="float:left;margin:0 1.25em"',
				),// end this field
				'contact_created_0' => array(
					'id' => 'contact_created_0',
					'name' => 'contact_created[]',
					//'name' => 'contact[date][]',
					'placeholder' => '2014-10-26 23:59:59',
					'noLabel' => true,
//					'description' => 'Contact number',
					'default_' => 'Contact date',
					'defaultExtra' => 'style="margin:0 1em"',
					//'type' => 'text',
						'type' => 'datetime-local',
					'optional' => true,
					'showOptional' => false,
					'inputWrapClass' => 'input col-md-12',
					'rowClass' => 'ml-2 col-md-4 g-2',
					'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
				),// end this field
				'contact_updated_0' => array(
					'id' => 'contact_updated_0',
					'name' => 'contact_updated[]',
					//'name' => 'contact[date][]',
					'placeholder' => '2014-10-26 23:59:59',
					'data-defaultValue' => '2014-10-26 23:59:59',
					'noLabel' => true,
//					'description' => 'Contact number',
					'default_' => 'Contact updated',
					'defaultExtra' => 'style="margin:0 1em"',
					//'type' => 'text',
						'type' => 'datetime-local',
					'optional' => true,
					'showOptional' => false,
					'inputWrapClass' => 'input col-md-12 autocomplete-contacts',
					'rowClass' => 'ml-2 col-md-4 g-2',
					'rowExtra' => 'style="float:left;margin:0 1.25em"',
				),// end this field
				'deleteMe_0' => array(
					'tag' => 'button',
					'type' => 'button',
					'id' => 'deleteMe_0',
					'name' => 'removeContact',
					'style' => 'height:2.5em',
					'innerText' => 'Remove',
					'class' => 'btn btn-danger deleteMe hide',
					'selfClose' => true,
					'inner' => array(
						array(
							'tag' => 'i',
							'class' => 'fa-solid fa-circle-minus fa-2xl',
							'style' => 'padding-left: 0.5em;',
							'selfClose' => true,
						),
					),
				),
			),// end this inner
		),// end div-inner
	);
/*	// attach options from types database table to the contact_types in the form
	$types = ContactType::getAll();
	$value = array();
	foreach ($types as $k => $v) {
		foreach ($v as $key => $val) {
			$id = $v['type_id'];
			$value[$id] = $v['name'];
		}
	}
	//		echo '<pre>contact_type options : '. print_r($value, true). '</pre>';
	insertInArray($contact_inner, 'contact_type_', $value, 'options');
	//		echo 'forms:<pre>'. print_r($forms, true). '</pre>';
 */
	//unset($contact_inner['inner']['Add-new-contact']);
		insertInArray($contact_innerBAK, 'deleteMe_0', 'btn btn-danger deleteMe', 'class');
	$fixed = preg_replace_json('/("id"\s*:\s*"\D+)_\d+"/i', '$1_'. $id. '"', $contact_innerBAK);
	//$ins = array('fieldset'. $id => $fixed[0]);
//	$ins = $fixed[0];
//	echo 'going to insert: <pre>'. print_r($ins, true). '</pre>'. "\n";
	appendToArrayInner($array, 'id', 'contacts', $fixed[0], 'fieldset'. $id);
//	echo 'now array: <pre>'. print_r($array, true). '</pre>'. "\n";
}
endif;
