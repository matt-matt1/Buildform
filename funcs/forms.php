<?php
namespace Yuma;

	/**
	 * Outputs the HTML form section for a text input field
	 * @param $details array containing:
	 *		'id' => string	the ID of the filed
	 *		'name' => optional string	name of the field use as key in form submission (if not supplied the id is used)
	 *		'label' => optional string	label displayed in association with the input (if not supplied a translation of the id is used)
	 *		'value' => optional		the cuurent value for the input
	 *		'default' => optional string	displays under the label to indicate the original value (if not supplied a translation the id is used)
	 *		'default_' => optional string	overrides the above - no translation
	 *		'isPassword' => optional boolean	indicating whether the input should be masked, no default
	 *		'invalid' => optional string	displayed only if a invalid value was entered
	 *		'input_class' => optional string	used as the classname for the input - multiple classes are space seperated
	 *		'placeholder' => optional string	display this value when the input is empty
	 */
	function textField($details)
	{
		//echo '<!-- textField::details:'. print_r($details, true). ' -->';
		if (!isset($details['id']) || !isset($details['value']) || !is_string($details['value']))
		{
			return;
		}
		$id = $details['id'];
		$name = isset($details['name']) ? $details['name'] : $details['id'];
		$label = isset($details['label']) ? $details['label'] : t('_'. $id);
		$labelClass = isset($details['label_class']) ? $details['label_class'] : "control-label";
		$labelWrap = isset($details['label_wrap_class']) ? $details['label_wrap_class'] : "label col-lg-3 col-9";
		$value = /*isset($details['value']) ?*/ $details['value'] /*: $details[$id]['value']*/;
		if (!isset($details['isPassword']) || !$details['isPassword'])
		{
			$test = t('_default_'. $id);
			$default = (isset($details['default_'])) ?
				$details['default_'] :
				((isset($details['default']) && do_mbstr('substr', $details['default'], 0, 1) !== '_') ?
				t('_default'). ': '. $details['default'] :
				(($test != '_default_'. $id) ?
				t('_default'). ': '. $test :
				((is_string($id) && defined($id)) ?
				t('_default'). ': '. constant($id) :
				null)));
		}
		$invalid = isset($details['invalid']) ? $details['invalid'] : t('_invalid_'. $id);
		$inputClass = isset($details['input_class']) ? $details['input_class'] : '';
		$inputWrap = isset($details['input_wrap_class']) ? $details['input_wrap_class'] : 'input col-lg-3 col-9';
?>
				<!--		<div class="form-group">-->
							<div<?php if (isset($labelWrap) && !empty($labelWrap)) { print ' class="'. $labelWrap. '"'; }?>>
								<label<?php if (isset($labelClass) && !empty($labelClass)) { print ' class="'. $labelClass. '"'; }?> for="<?=$id?>"><?=$label?>:</label>
							</div>
							<div<?php if (isset($inputWrap) && !empty($inputWrap)) { print ' class="'. $inputWrap. '"'; }?>>
								<input type="text" class="form-control<?php if (isset($inputClass) && !empty($inputClass)) { print ' '. $inputClass; }?>" id="<?=$id?>" name="<?=$name?>"<?php if (isset($details['placeholder'])){ echo ' placeholder="'. $details['placeholder']. '"'; }?> value="<?=$value?>">
<?php	if (isset($invalid) && !empty($invalid)) :	?>
								<div class="invalid-feedback"><?=$invalid?></div>
<?php	endif;	?>
<?php	if (isset($default) && !empty($default) && is_string($default) && do_mbstr('substr', $default, 0, 1) !== '_') :	?>
								<span class="default"><?=$default?></span>
<?php	endif;	?>
							</div>
<!--						</div>-->
<?php
	}

	/**
	 * Output the HTML form section for a number form field
	 * @param $details array containing:
	 *		'id' => string	the ID of the filed
	 *		'name' => optional string	name of the field use as key in form submission (if not supplied the id is used)
	 *		'label' => optional string	label displayed in association with the input (if not supplied a translation of the id is used)
	 *		'value' => optional		the cuurent value for the input
	 *		'default' => optional string	displays under the label to indicate the original value (if not supplied a translation the id is used)
	 *		'default_' => option string	overrides the above - no translation
	 *		'invalid' => optional string	displayed only if a invalid value was entered
	 *		'input_class' => optional string	used as the classname for the input - multiple classes are space seperated
	 *		'placeholder' => optional string	display this value when the input is empty
	 */
	function numField($details)
	{
		//echo '<!-- textField::details:'. print_r($details, true). ' -->';
		if (!isset($details['id']) || !isset($details['value']) || !is_string($details['value']))
		{
			return;
		}
		$id = $details['id'];
		$name = isset($details['name']) ? $details['name'] : $details['id'];
		$label = isset($details['label']) ? $details['label'] : t('_'. $id);
		$labelClass = isset($details['label_class']) ? $details['label_class'] : "";
		$labelWrap = isset($details['label_wrap_class']) ? $details['label_wrap_class'] : "label col-lg-3 col-9";
		$value = /*isset($details['value']) ?*/ $details['value'] /*: $details[$id]['value']*/;
		$test = t('_default_'. $id);
		$default = (isset($details['default_'])) ?
			$details['default_'] :
			((isset($details['default'])) ?
			t('_default'). ': '. $details['default'] :
			(($test != '_default_'. $id) ?
			t('_default'). ': '. $test :
			((is_string($id) && defined($id)) ?
			t('_default'). ': '. constant($id) :
			null)));
		$invalid = isset($details['invalid']) ? $details['invalid'] : t('_invalid_'. $id);
		$inputClass = isset($details['input_class']) ? $details['input_class'] : '';
		$inputWrap = isset($details['input_wrap_class']) ? $details['input_wrap_class'] : 'input col-lg-3 col-9';
?>
<!--						<div class="form-group">-->
							<div<?php if (isset($labelWrap) && !empty($labelWrap)) { print ' class="'. $labelWrap. '"'; }?>>
								<label<?php if (isset($labelClass) && !empty($labelClass)) { print ' class="'. $labelClass. '"'; }?> for="<?=$id?>"><?=$label?>:</label>
							</div>
							<div<?php if (isset($inputWrap) && !empty($inputWrap)) { print ' class="'. $inputWrap. '"'; }?>>
								<input type="number" class="form-control<?php if (isset($inputClass) && !empty($inputClass)) { print ' '. $inputClass; }?>" id="<?=$id?>" name="<?=$id?>"<?php if (isset($details['placeholder'])){ echo ' placeholder="'. $details['placeholder']. '"'; } if (isset($value)){ echo ' value="'. $value. '"'; }?>>
<?php	if (isset($invalid) && !empty($invalid)) :	?>
								<div class="invalid-feedback"><?=$invalid?></div>
<?php	endif;	?>
<?php	if (isset($default) && !empty($default) && is_string($default) && do_mbstr('substr', $default, 0, 1) !== '_') :	?>
								<span class="default"><?=$default?></span>
<?php	endif;	?>
							</div>
<!--						</div>-->
<?php
	}

	/**
	 * Outputs the HTML form section for a checkbox field -enclosed by a row division
	 * @param $details array containing:
	 *		'id' => string	the ID of the filed
	 *		'name' => optional string	name of the field use as key in form submission (if not supplied the id is used)
	 *		'label' => optional string	label displayed in association with the input (if not supplied a translation of the id is used)
	 *		'value' => optional		the cuurent value for the input
	 *		'default' => optional string	displays under the label to indicate the original value (if not supplied a translation the id is used)
	 *		'default_' => option string	overrides the above - no translation
	 *		'invalid' => optional string	displayed only if a invalid value was entered
	 *		'label_class' => optional string	used as the classname for the input - multiple classes should be space seperated
	 *		'row_class' => optional string	used as the classname for the whole row - multiple classes should be space seperated
	 *		'after' => optional string	also a label on the other side of the control, usually a longer label description
	 */
	function checkboxRow($details)
	{
		//echo '<!-- checkboxRow::details:'. print_r($details, true). ' -->';
		if (!isset($details['id']))
		{
			return;
		}
		$id = $details['id'];
		$name = isset($details['name']) ? $details['name'] : $details['id'];
		$label = isset($details['label']) ? $details['label'] : t('_'. $id);
		$value = isset($details['value']) ? $details['value'] : $details[$id]['value'];
		$rowClass = isset($details['row_class']) ? $details['row_class'] : "row mb-2 g-2 row-cols-2";
		$labelClass = isset($details['label_class']) ? $details['label_class'] : "form-check-label";
		$labelWrap = isset($details['label_wrap_class']) ? $details['label_wrap_class'] : "label col-3";
		$test = t('_default_'. $id);
		$default = (isset($details['default_'])) ?
			$details['default_'] :
			((isset($details['default'])) ?
			t('_default'). ': '. ($details['default'] ? 'on' : 'off') :
			(($test != '_default_'. $id) ?
			t('_default'). ': '. $test :
			(0 && ((is_bool($id) || is_string($id)) && defined($id)) ?
			t('_default'). ': '. (constant($id) ? 'on' : 'off') :
			null)));
		$after = isset($details['after']) ? $details['after'] : t('_after_'. $id);
		$invalid = isset($details['invalid']) ? $details['invalid'] : t('_invalid_'. $id);
		$inputClass = isset($details['input_class']) ? $details['input_class'] : '';
		$inputWrap = isset($details['input_wrap_class']) ? $details['input_wrap_class'] : 'input col-auto';
?>
						<div class="<?=$rowClass/*row g-2 mb-4 form-switch*/?>">
<!--						<div class="form-group">-->
							<div<?php if (isset($labelWrap) && !empty($labelWrap)) { print ' class="'. $labelWrap. '"'; }?>>
<?php if (strlen($label) > 1) : ?>
								<label class="form-check-label<?php if (isset($labelClass) && !empty($labelClass)) { print ' '. $labelClass;}?>" for="<?=$id?>"><?=$label?>:</label>
<?php endif; ?>
							</div>
							<div<?php if (isset($inputWrap) && !empty($inputWrap)) { print ' class="'. $inputWrap. '"'; }?>>
								<input type="checkbox" class="form-check-input<?php if (isset($inputClass) && !empty($inputClass)) { print ' '. $inputClass; }?>" id="<?=$id?>" name="<?=$name?>"<?php if (isset($value) && $value) { echo ' checked'; } ?>>
<?php	if (isset($invalid) && !empty($invalid)) :	?>
								<div class="invalid-feedback"><?=$invalid?></div>
<?php	endif;	?>
<?php	if (isset($default) && !empty($default) && is_string($default) && do_mbstr('substr', $default, 0, 1) !== '_') :	?>
								<span class="default"><?=$default?></span>
<?php	endif;	?>
							</div>
							<div class="post-input col">
<?php if ($after != '_after_') : ?>
								<label<?php /*if (isset($labelClass) && !empty($labelClass)) { print ' class="'. $labelClass. '"'; }*/?> for="<?=$id?>"><?=$after?></label>
<?php endif; ?>
							</div>
<!--						</div>-->
						</div>
<?php
	}

	/**
	 * Outputs the HTML form section for a text input field -enclosed by a row division
	 * @param $details array containing:
	 *		'id' => string	the ID of the filed
	 *		'name' => optional string	name of the field use as key in form submission (if not supplied the id is used)
	 *		'label' => optional string	label displayed in association with the input (if not supplied a translation of the id is used)
	 *		'value' => optional		the cuurent value for the input
	 *		'default' => optional string	displays under the label to indicate the original value (if not supplied a translation the id is used)
	 *		'default_' => option string	overrides the above - no translation
	 *		'invalid' => optional string	displayed only if a invalid value was entered
	 *		'default_class' => optional string	used as the classname for the original value - multiple classes should be space seperated
	 *		'row_class' => optional string	used as the classname for the whole row - multiple classes should be space seperated
	 */
	function textareaRow($details)
	{
		//echo '<!-- textareaRow::details:'. print_r($details, true). ' -->';
		if (!isset($details['id']))
		{
			return;
		}
		$id = $details['id'];
		$name = isset($details['name']) ? $details['name'] : $details['id'];
		$rowClass = isset($details['row_class']) ? $details['row_class'] : "row mb-2 g-2 row-cols-2";
		$defaultClass = isset($details['default_class']) ? ' '. $details['default_class'] : '';
		$labelClass = isset($details['label_class']) ? ' '. $details['label_class'] : "";
		$labelWrap = isset($details['label_wrap_class']) ? $details['label_wrap_class'] : "label col-lg-3 col-9";
		$inputClass = isset($details['input_class']) ? $details['input_class'] : '';
		$inputWrap = isset($details['input_wrap_class']) ? $details['input_wrap_class'] : 'input col-9';
		$name = isset($details['name']) ? $details['name'] : $details['id'];
		$label = isset($details['label']) ? $details['label'] : t('_'. $id);
		//$value = /*isset($details['value']) ?*/ unserialize($details['value']) /*: $details[$id]['value']*/;
		//$value = !is_array($details['value']) ? unserialize($details['value']) : $details['value'];
		$value = /*!is_array($details['value']) ? unserialize($details['value']) :*/ $details['value'];
		$test = t('_default_'. $id);
		$default = (isset($details['default_'])) ?
			$details['default_'] :
			((isset($details['default'])) ?
			t('_default'). ': '. $details['default'] :
			(($test != '_default_'. $id) ?
			t('_default'). ': '. $test :
			((is_string($id) && defined($id) && is_string(constant($id))) ?
			t('_default'). ': '. constant($id) :
			null)));
		$invalid = isset($details['invalid']) ? $details['invalid'] : t('_invalid_'. $id);
?>
						<div class="<?=$rowClass/*row mb-2 g-2 row-cols-2*/?>">
<!--						<div class="form-group">-->
							<div<?php if (isset($labelWrap) && !empty($labelWrap)) { print ' class="'. $labelWrap. '"'; }?>>
								<label<?php if (isset($labelClass) && !empty($labelClass)) { print ' class="'. $labelClass. '"'; }?> for="<?=$id?>"><?=$label?>:</label>
							<?php	if (isset($default) && !empty($default) && is_string($default) && do_mbstr('substr', $default, 0, 1) !== '_') :	?>
								<span class="default<?=$defaultClass?>"><?=$default?></span>
<?php	endif;	?>
							</div>
							<div<?php if (isset($inputWrap) && !empty($inputWrap)) { print ' class="'. $inputWrap. '"'; }?>>
								<textarea class="form-control<?php if (isset($inputClass) && !empty($inputClass)) { print ' '. $inputClass; }?>" id="<?=$id?>" name="<?=$name?>" rows="5"><?php
	if (is_array($value) && !empty($value))
	{
		foreach ($value as $opt)
		{
			echo "{$opt}\n";
		}
	} else
		echo $value;
								?></textarea>
<?php	if (isset($invalid) && !empty($invalid)) :	?>
								<div class="invalid-feedback"><?=$invalid?></div>
<?php	endif;	?>
							</div>
	<!--					</div>-->
						</div>
<?php
	}

	/**
	 * Outputs the HTML form section for a selection of a single input field -not enclosed by a row division
	 * @param $details array containing:
	 *		'id' => string	the ID of the filed
	 *		'name' => optional string	name of the field use as key in form submission (if not supplied the id is used)
	 *		'label' => optional string	label displayed in association with the input (if not supplied a translation of the id is used)
	 *		'value' => optional		the cuurent value for the input
	 *		'default' => optional string	displays under the label to indicate the original value (if not supplied a translation the id is used)
	 *		'default_' => option string	overrides the above - no translation
	 *		'invalid' => optional string	displayed only if a invalid value was entered
	 *		'input_class' => optional string	used as the classname for the input - multiple classes should be space seperated
	 *		'default_class' => optional string	used as the classname for the original value - multiple classes should be space seperated
	 *		'row_class' => optional string	used as the classname for the whole row - multiple classes should be space seperated
	 */
	function selectField($details)
	{
		//echo '<!-- textareaRow::details:'. print_r($details, true). ' -->';
		if (!isset($details['id']))
		{
			return;
		}
		$id = $details['id'];
		$name = isset($details['name']) ? $details['name'] : $details['id'];
//		$rowClass = isset($details['row_class']) ? $details['row_class'] : "row mb-2 g-2 row-cols-2";
		$defaultClass = isset($details['default_class']) ? ' '. $details['default_class'] : '';
		$labelClass = isset($details['label_class']) ? ' '. $details['label_class'] : "";
		$labelWrap = isset($details['label_wrap_class']) ? $details['label_wrap_class'] : "label col-lg-3 col-9";
		$inputClass = isset($details['input_class']) ? $details['input_class'] : '';
		$inputWrap = isset($details['input_wrap_class']) ? $details['input_wrap_class'] : 'input col-lg-3 col-9';
		$name = isset($details['name']) ? $details['name'] : $details['id'];
		$label = isset($details['label']) ? $details['label'] : t('_'. $id);
		$value = /*!is_array($details['value']) ? unserialize($details['value']) :*/ $details['value'];
		$test = t('_default_'. $id);
		$default = (isset($details['default_'])) ?
			$details['default_'] :
			((isset($details['default'])) ?
			t('_default'). ': '. $details['default'] :
			(($test != '_default_'. $id) ?
			t('_default'). ': '. $test :
			((is_string($id) && defined($id) && is_string(constant($id))) ?
			t('_default'). ': '. constant($id) :
			null)));
		$invalid = isset($details['invalid']) ? $details['invalid'] : t('_invalid_'. $id);
?>
<!--						<div class="form-group">-->
							<div<?php if (isset($labelWrap) && !empty($labelWrap)) { print ' class="'. $labelWrap. '"'; }?>>
								<label<?php if (isset($labelClass) && !empty($labelClass)) { print ' class="'. $labelClass. '"'; }?> for="<?=$id?>"><?=$label?>:</label>
<?php	if (isset($default) && !empty($default) && is_string($default) && do_mbstr('substr', $default, 0, 1) !== '_') :	?>
								<span class="default<?=$defaultClass?>"><?=$default?></span>
<?php	endif;	?>
							</div>
							<div<?php if (isset($inputWrap) && !empty($inputWrap)) { print ' class="'. $inputWrap. '"'; }?>>
								<select class="form-control<?php if (isset($inputClass) && !empty($inputClass)) { print ' '. $inputClass; }?>" id="<?=$id?>" name="<?=$name?>" value="<?=$value?>">
<?php
	if (!isset($_POST[$id])) {
?>
									<option selected disabled><?=l('_Select')?></option>
<?php
	}
	foreach($details['options'] as $val => $text)
	{
?>
									<option<?php if ($val === $value) { echo ' selected'; }?> value="<?=$val?>"><?=$text?></option>
<?php
	}
?>
								</select>
<?php	if (isset($invalid) && !empty($invalid)) :	?>
								<div class="invalid-feedback"><?=$invalid?></div>
<?php	endif;	?>
							</div>
	<!--					</div>-->
<?php
	}
