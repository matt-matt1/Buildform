<?php

namespace Yuma\HTML;
use function \Yuma\t;
use function \Yuma\do_mbstr;

class Field {
	/**
	 * Outputs the HTML form section for a text input field
	 * @param $details array containing:
	 *		'id' => string	the ID of the filed
	 *		'type' => string	either: "button", "checkbox", "color", "date", "datetime-local", "email", "file", "hidden", "image", "month", "number", "password", "radio", "range", "reset", "search", 'select', "submit", "tel", "text", 'textarea', "time", "url" or "week"
	 *		'name' => optional string	name of the field use as key in form submission (if not supplied the id is used)
	 *		'label' => optional string	label displayed in association with the input (if not supplied a translation of the id is used)
	 *		'labelClass' => optional string	used as the classname for the label - multiple classes are space seperated
	 *		'labelWrapClass' => optional string	used as the classname for the label wrap - multiple classes are space seperated
	 *		'value' => optional		the cuurent value for the input
	 *		'default_' => optional string	overrides "default" - no translation
	 *		'default' => optional string	displays under the label to indicate the original value (if not supplied a translation the id is used)
	 *		'defaultClass' => optional string	used as the classname for the default, if set - multiple classes are space seperated
	 *		'extra' => optional string		extra text placed inside the textarea/input/select/button
//	 *		'isPassword' => optional boolean	indicating whether the input should be masked, no default
	 *		'invalid' => optional string	displayed only if a invalid value was entered - uses the 'invalid-feedback' class
	 *		'valid' => optional string		displayed only if a valid value was entered - uses the 'valid-feedback' class
	 *		'optional' => optional string      displays a string indicating that input is optional
	 *		'optional' => optional string      whether the optional indicator is shown or not
	 *		'form-text' => optional string      displays a div with class 'form-text' 
	 *		'inputClass' => optional string	used as the classname for the input - multiple classes are space seperated
	 *		'inputWrapClass' => optional	string	used as the classname for the input wrap - multiple classes are space seperated
	 *		'inputWrapExtra' => optional	string	displays extra text within the input wrapper tag
	 *		'inputTitle' => optional string   displays a string onhover of the input box
	 *		'placeholder' => optional string	display this value when the input is empty
	 *		'canshow' => optional boolean	places an icon in the input box to show/hide secure information
	 *		'after' => optional string		placed after the input box - basic instructions
	 *		'roptions' => optional string	raw HTML string of options (include <option> tag)  *only when type is select
	 *		'options' => optional array		array of select options  *only when type is select
	 */
	private static array $details;
	private static string $id;
	private static $type;
	private static $eye;
	private static $wipe;
	private static $name;
	private static $label;
	private static $labelWrap;
	private static $labelClass;
	private static $labelTitle;
	private static $title;
	private static $placeholder;
	private static $value;
	private static $default;
	private static $after;
	private static $invalid;
	private static $valid;
	private static $formText;
	private static $inputWrap;
	private static $inputClass;
	private static $defaultClass;
	private static $defaultWrapClass;
	private static $extra;
	private static $class;


	/**
	 * Ensures valid parameters are passed, otherwise an error is thrown
	 */
	private static function valid()//array $details)
	{
		//echo '<!-- textField::details:'. print_r($details, true). ' -->';
//		if (!isset($details['id']) /*|| !isset($details['value']) || !is_string($details['value'])*/)
/*		{
			throw new Exception("Tried to output a form field with an invalid id ". static::$details['id']);
			return;
		}*/
		if (!isset(static::$details['id'])) {
			throw new Exception( sprintf( ('_Tried to output a form field without an id : %s'), print_r(static::$details, true) ) );
			return;
		}
		static::$id = isset(static::$details['id']) ? static::$details['id'] : '_';
/*		{
			$id = $details['id'];
		}*/
		$types = array("button", "checkbox", "color", "date", "datetime-local", "email", "file", "hidden", "image", "month", "number", "password", "radio", "range", "reset", "search", 'select', "submit", "tel", "text", 'textarea', "time", "url", "week");
		if (!isset(static::$details['type'])) {
			static::$details['type'] = 'text';
		}
		if (/*!isset($details['type']) ||*/ !in_array(static::$details['type'], $types)) {
			//throw new Exception("Tried to output a form field with an invalid type ". static::$details['type']. "- must be ". implode(', ', $types));
			throw new Exception( sprintf(t("_Tried to output a form field with an invalid type ('%s') - must be %s"), static::$details['type'], implode(', ', $types)));
			return;
		}
	}

	/**
	 * Puts values in variables
	 */
	private static function loadVars()//array $details)
	{
		static::valid(static::$details);
		static::$type = isset(static::$details['type']) ? static::$details['type'] : 'text';
		static::$eye = isset(static::$details['canShow']) && static::$details['canShow'] ? true : false;
		static::$wipe = isset(static::$details['canWipe']) && static::$details['canWipe'] ? true : false;
		static::$name = isset(static::$details['name']) ? static::$details['name'] : static::$details['id'];
//		$formFloating = isset($details['form-floating']) ? $details['form-floating'] : 'id';
		static::$label = isset(static::$details['label']) ? static::$details['label'] : t('_'. static::$id);
		static::$labelWrap = isset(static::$details['labelWrapClass']) ? static::$details['labelWrapClass'] : 'label col-md-3';//"label col-lg-3 col-9";
		static::$labelClass = isset(static::$details['labelClass']) ? static::$details['labelClass'] : '';
		static::$labelTitle = isset(static::$details['labelTitle']) ? static::$details['labelTitle'] : t('_title_'. static::$id);
		if (do_mbstr('substr', static::$labelTitle, 0, 1) == '_') {
			static::$labelTitle = '';
		}
		static::$title = isset(static::$details['inputTitle']) ? static::$details['inputTitle'] : '';
		static::$placeholder = isset(static::$details['placeholder']) ? static::$details['placeholder'] : '';
		if (static::$type == 'checkbox')
		{
			static::$labelClass .= (isset(static::$labelClass) && strlen(static::$labelClass) > 1 ? ' ' : ''). 'form-check-label';
		}
		//$value = /*isset($details['value']) ?*/ $details['value'] /*: $details[$id]['value']*/;
		static::$value = isset(static::$details['value']) ? static::$details['value'] : false/*static::$details[$id]['value']*/;
		//if (!isset($details['isPassword']) || !$details['isPassword'])
/*		if ($type != 'password')
		{*/
			$test = t('_default_'. static::$id);
			static::$default = (isset(static::$details['default_']) && !empty(static::$details['default_'])) ?
				static::$details['default_'] :
			//	((isset($details['default']) && mb_substr($details['default'], 0, 1) !== '_') ?
				((isset(static::$details['default'])) ?
				t('_default'). ': '. static::$details['default'] :
				(($test != '_default_'. static::$id) ?
				t('_default'). ': '. $test :
			//	((defined($id) && is_string(constant($id))) ?
				((is_string(static::$id) && defined(static::$id) && is_string(constant(static::$id))) ?
				t('_default'). ': '. constant(static::$id) :
				null)));
/*		}*/
		static::$after = isset(static::$details['after']) ? static::$details['after'] : t('_after_'. static::$id);
		if (do_mbstr('substr', static::$after, 0, 1) == '_') {
			static::$after = '';
		}
		static::$invalid = isset(static::$details['invalid']) ? static::$details['invalid'] : t('_invalid_'. static::$id);
		static::$valid = isset(static::$details['valid']) ? static::$details['valid'] : t('_valid_'. static::$id);
		static::$formText = isset(static::$details['form-text']) ? static::$details['form-text'] : t('_form-text_'. static::$id);
		static::$inputWrap = isset(static::$details['inputWrapClass']) ? static::$details['inputWrapClass'] : ((static::$type == 'checkbox') ? 'input col-auto' : 'input col-md-3');//'input col-lg-3 col-9');
		static::$inputClass = isset(static::$details['inputClass']) ? static::$details['inputClass'] : '';
		if (static::$type == 'checkbox') {
			static::$inputClass .= (isset($inputClass) && strlen($inputClass) > 1 ? ' ' : ''). 'form-check-input';
		}
		static::$defaultClass = isset(static::$details['defaultClass']) ? /*' '.*/ static::$details['defaultClass'] : 'default';
		static::$defaultWrapClass = isset(static::$details['defaultWrapClass']) ? /*' '.*/ static::$details['defaultWrapClass'] : 'default-hint';
		static::$extra = isset(static::$details['extra']) ? ' '. static::$details['extra'] : null;
		static::$class = (static::$type != 'hidden' && static::$type != 'checkbox' && static::$type != 'submit' && static::$type != 'reset') ? 'form-control' : '';
		if (!empty(static::$class) && static::$type != 'checkbox' && static::$type != 'submit' && isset(static::$inputClass) && !empty(static::$inputClass)) {
			static::$class .= ' ';
		}
		if (isset(static::$inputClass) && !empty(static::$inputClass)) {
			static::$class .= static::$inputClass;
		}
		if (!empty(static::$class)) {
			static::$class = ' class="'. static::$class. '"';
		}
	}

	private static function sample()//array $details)
	{
	}

	/**
	 * Renders opening a row
	 */
	private static function beginRow()//array $details)
	{
//			$c = explode(' ', $details['row_class']);
//			if (in_array( 'row', $c ))
//			{
/*						<div class="form-group<?php if (isset($details['row_class']) and !empty($details['row_class'])) { print ' '. $details['row_class']; }?>">*/
?>
						<div data-ob-Level="<?=ob_get_level()?>" class="<?=static::$details['rowClass']?>"<?php
		if (isset(static::$details['rowExtra']) && !empty(static::$details['rowExtra']))
			print ' '. static::$details['rowExtra'];
?>>
<?php	//	}
	}

	/**
	 * Render a field label
	 */
	private static function drawLabel()//array $details)
	{
?>
							<!--div<?php if (isset(static::$labelWrap) && !empty(static::$labelWrap)) { print ' class="'. static::$labelWrap. '"'; }?>-->
							<div class="<?=static::$labelWrap?>">
<?php	if (strlen(static::$label) > 1) {
?>								<label<?php
			if (isset(static::$labelClass) && !empty(static::$labelClass)) {
				print ' class="'. static::$labelClass. '"';
			}
			if (isset(static::$labelTitle) && !empty(static::$labelTitle)) {
				print ' title="'. static::$labelTitle. '"';
			}
?> for="<?=static::$id?>"<?php
			if (static::$type == 'hidden') {
				print ' style="display:none"';
			}
?>><?=static::$label?>:<?php
			if (isset(static::$details['optional']) && static::$details['optional'] && !isset(static::$details['showOptional']))
				echo '<small>'. t('_optional'). '</small>'; ?></label>
<?php	}
		if (static::$type == 'textarea' && isset(static::$default) && !empty(static::$default) && is_string(static::$default) && do_mbstr('substr', static::$default, 0, 1) !== '_') {
?>								<span class="default<?=static::$defaultClass?>"><?=static::$default?></span>
<?php	}
?>							</div>
<?php
	}

/**/
	private static function drawOpenFieldset()//array $details)
	{
?>		<fieldset id="<?=static::$id?>">
<?php
				if (isset(static::$details['legend']) && !empty(static::$details['legend'])) {
?>			<legend for="<?=static::$id?>"><?=static::$details['legend']?></legend>
<?php
				}
	}

	private static function drawCloseFieldset()//array $details)
	{
?>		</fieldset><!--Fields-->
<?php
	}
/**/

	/**
	 * Renders the opening wrapper for this field
	 */
	private static function drawOpenWrap()//array $details)
	{
?>
		<div<?php if (isset(static::$inputWrap) && !empty(static::$inputWrap)) {
			print ' class="'. static::$inputWrap. '"';
		}
		if (isset(static::$details['inputWrapExtra'])) {
			print ' '. static::$details['inputWrapExtra'];
		}
		if (static::$type == 'hidden') {
			print ' style="display:none"';
		}
?>>
<?php
		if (isset(static::$details['icon']) && !empty(static::$details['icon'])) {
?><span class="icon"><?=static::$details['icon']?></span>
<?php
		}
	}

	/**
	 * Renders a textarea block
	 */
	private static function drawTextarea()//array $details)
	{
?>								<textarea class="<?php
		if (static::$type != 'hidden') {
			print 'form-control';
		}
		if (static::$type != 'hidden' && isset(static::$inputClass) && !empty(static::$inputClass)) {
			print ' ';
		}
		if (isset(static::$inputClass) && !empty(static::$inputClass)) {
			print static::$inputClass;
		}
?>"<?php
		if (isset(static::$title) && !empty(static::$title)) {
			print ' title="'. static::$title. '"';
		}
?> id="<?=static::$id?>" name="<?=static::$name?>" rows="5"<?php
		if (static::$type != 'hidden' && (!isset(static::$details['optional']) || !static::$details['optional'])) {
			print ' required';
		}
		if (isset(static::$extra) && !empty(static::$extra)) {
			print ' '. static::$extra;
		}
?>><?php
		if (is_array(static::$value) && !empty(static::$value)) {
			foreach (static::$value as $opt) {
				echo "{$opt}\n";
			}
		} else
			echo static::$value;
?>								</textarea>
<?php
	}

	/**
	 * Renders a select box of options
	 */
	private static function drawSelect()//array $details)
	{
?>
								<select class="<?php
		if (static::$type != 'hidden') {
			print 'form-control';
		}
		if (static::$type != 'hidden' && isset(static::$inputClass) && !empty(static::$inputClass)) {
			print ' ';
		}
		if (isset(static::$inputClass) && !empty(static::$inputClass)) {
			print static::$inputClass;
		}
?>"<?php
		if (isset(static::$title) && !empty(static::$title)) {
			print ' title="'. static::$title. '"';
		}
?> id="<?=static::$id?>" name="<?=static::$name?>"<?php
		if (!empty(static::$value))
			echo ' value="'. static::$value. '"';
		if (static::$type != 'hidden' && (!isset(static::$details['optional']) || !static::$details['optional'])) {
			print ' required';
		}
		if (isset(static::$extra) && !empty(static::$extra)) {
			print ' '. static::$extra;
		}
?>>
<?php	/*	if (!isset($_POST[$id])) {
?>									<option selected disabled><?=l('_Select')?></option>
<?php		}*/
		if (isset(static::$placeholder) && !empty(static::$placeholder)) {
?>							<option selected disabled><?=static::$placeholder?></option>
<?php
		}
		if (isset(static::$details['roptions'])) {
			echo '<!-- roptions: -->'. static::$details['roptions']. '<!--/-->';
		} elseif (isset(static::$details['options']) && is_array(static::$details['options'])) {
			foreach (static::$details['options'] as $val => $text) {
				if (is_array($text)) {
					foreach ($text as $o => $v) {
						if (!isset($v['name']) || !isset($v['value'])) {
							continue;
						}
?>							<option<?php if ($v['value'] === static::$value) { echo ' selected'; }?> value="<?=$v['value']?>"><?=$v['name']?></option>
<?php
					}
				} else {	?>
							<option<?php if ($val === static::$value) { echo ' selected'; }?> value="<?=$val?>"><?=$text?></option>
<?php
				}
			}
		}
?>								</select>
<?php
	}

	/**
	 * Renders an input button
	 */
	private static function drawButton()//array $details)
	{
?>								<button type="<?=static::$type?>" data-lpignore="true" class="
<?php
		if (static::$type != 'hidden' && static::$type != 'checkbox' && static::$type != 'submit') {
			print 'form-control';
			if (isset(static::$inputClass) && !empty(static::$inputClass)) {
				print ' ';
			}
		}
		if (isset(static::$inputClass) && !empty(static::$inputClass)) {
			print static::$inputClass;
		}
?>"<?php
		if (isset(static::$title) && !empty(static::$title)) {
			print ' title="'. static::$title. '"';
		}
?> id="<?=static::$id?>" name="<?=static::$name?>"<?php
		if (isset(static::$placeholder) && !empty(static::$placeholder)) {
			print ' placeholder="'. static::$placeholder. '"';
		}
		if (isset(static::$extra) && !empty(static::$extra)) {
			print ' '. static::$extra;
		}
		echo '>';
		if (isset(static::$details['inner'])) {
			print static::$details['inner'];
		}
		if (static::$value) {
			print static::$value;
		}
/*		if (isset($extra) && !empty($extra)) {
			print ' '. $extra;
		}*/
?>								</button>
<?php
	}

	/**
	 * Renders an input field
	 */
	private static function drawInput()//array $details)
	{
?>
								<input type="<?=static::$type?>" data-lpignore="true"<?=static::$class?><?php
		if (isset(static::$title) && !empty(static::$title)) {
			print ' title="'. static::$title. '"';
		}
?> id="<?=static::$id?>" name="<?=static::$name?>"<?php
		if (isset(static::$placeholder) && !empty(static::$placeholder)) {
			print ' placeholder="'. static::$placeholder. '"';
		}
		if (static::$type == 'checkbox' && static::$value) {
			print ' checked';
		} elseif (!empty(static::$value)) {
			print ' value="'. static::$value. '"';
		}
		if (static::$type == 'password') {
			print ' autocomplete="off"';
		}
		if (static::$type != 'submit' && static::$type != 'reset' && static::$type != 'hidden' && (!isset(static::$details['optional']) || !static::$details['optional'])) {
			print ' required';
		}
		if (isset(static::$extra) && !empty(static::$extra)) {
			print ' '. static::$extra;
		}
		if (static::$type == 'number') {
			print ' inputmode="decimal"';
		}
?>>
<?php
		if (static::$eye) {
?>								<i class="eye fa-solid fa-eye-slash form-control-feedback hide-if-no-js" id="eye"></i>
<?php
		}
		if (static::$wipe && !static::$eye) {
?>								<i class="wipe fa-solid fa-circle-xmark form-control-feedback hide-if-no-js" id="wipe"></i>
<?php
		}
	}

	/**
	 * Renders a label for a checkbox
	 */
	private static function drawCheckboxLabel()//array $details)
	{
?>
							<div class="post-input col">
								<label<?php
		if (isset(static::$labelClass) && !empty(static::$labelClass)) {
			print ' class="'. static::$labelClass. '"';
		}
?> for="<?=static::$id?>" title="<?=static::$after?>"><?=static::$after?></label>
							</div>
<?php
	}

	/**
	 * Renders an HTML close
	 */
	private static function endRow()//array $details)
	{
?>						</div>
<?php
	}

	/**
	 * Outputs the HTML for a form field
	 */
	public static function out(array $details)
	{
		ob_start();
		static::$details = $details;
		static::loadVars();//$details);
		if (isset($details['rowClass']) && !empty($details['rowClass'])) :
			static::beginRow();//$details);
		endif;
		if (static::$type !== 'hidden' && static::$type !== 'submit' && (!isset($details['noLabel']) || !$details['noLabel']) && static::$id !== '_') {
			static::drawLabel();
		}

		if (isset($details['fieldset']) && !empty($details['fieldset'])) {
			if ($details['fieldset'] == 'open') {
				//static::drawOpenFieldset();
				echo Fieldset::open($details);
			} elseif ($details['fieldset'] == 'close') {
				//static::drawCloseFieldset();
				echo Fieldset::close();
			}
		} else {
			while (isset(static::$type)) {	//	keep doing this until type doesn't exist
				static::drawOpenWrap();
				if (static::$type == 'textarea') {
					static::drawTextarea();
				} elseif (static::$type == 'select') {
					static::drawSelect();
				} elseif (isset($details['makeButton']) && $details['makeButton']) {
					static::drawButton();
				} else {
					static::drawInput();
				}
				if (isset(static::$valid) && !empty(static::$valid) && is_string(static::$valid) && do_mbstr('substr', static::$valid, 0, 1) !== '_') {
?>								<div class="valid-feedback"><?=static::$valid?></div>
<?php			}
				if (isset(static::$invalid) && !empty(static::$invalid) && is_string(static::$invalid) && do_mbstr('substr', static::$invalid, 0, 1) !== '_') {
?>								<div class="invalid-feedback"><?=static::$invalid?></div>
<?php			}
				if (isset(static::$formText) && !empty(static::$formText) && is_string(static::$formText) && do_mbstr('substr', static::$formText, 0, 1) !== '_') {
?>								<div class="form-text"><?=static::$formText?></div>
<?php			}
?>							</div>
<?php
				// ^ closes the drawOpenWrap
				if (isset(static::$type) && static::$type == 'checkbox' && isset(static::$after) && !empty(static::$after) && static::$after != '_after_') {
					static::drawCheckboxLabel();
				}
				static::$type = null;
				//unset(static::$type);	// unset type variables (so while stops)
				if (isset($details['input2']) && !empty($details['input2'])) {	// however if there's a input2 set it's type 
					static::$id = isset($details['input2']['id']) ? $details['input2']['id'] : $details['id']. '2';
					static::$name = isset($details['input2']['name']) ? $details['input2']['name'] : $details['id']. '2';
					static::$type = isset($details['input2']['type']) ? $details['input2']['type'] : 'text';
					static::$extra = isset($details['input2']['extra']) ? $details['input2']['extra'] : null;
					static::$placeholder = isset($details['input2']['placeholder']) ? $details['input2']['placeholder'] : null;
					static::$inputClass = isset($details['input2']['inputClass']) ? $details['input2']['inputClass'] : '';
					static::$inputWrap = isset($details['input2']['inputWrapClass']) ? $details['input2']['inputWrapClass'] : '';
					static::$title = isset($details['input2']['inputTitle']) ? $details['input2']['inputTitle'] : '';
					static::$extra = isset($details['input2']['extra']) ? $details['input2']['extra'] : '';
					$details['input2'] = null;
					unset($details['input2']);
				}
			}
			if (isset(static::$default) && !empty(static::$default) && is_string(static::$default) && do_mbstr('substr', static::$default, 0, 1) !== '_') {
?>
						<div class="<?=static::$defaultWrapClass?>"<?php if (isset($details['defaultWrapExtra']) && !empty($details['defaultWrapExtra'])) echo ' '.$details['defaultWrapExtra'];?>>
							<span<?php if (isset($details['defaultExtra']) && !empty($details['defaultExtra'])) echo ' '.$details['defaultExtra'];?> class="<?=static::$defaultClass?>"><?=static::$default?></span>
						</div>
<?php
			}
		}	?>

<?php
	/*if (isset($type) && $type == 'checkbox' && $after != '_after_') :	?>
							<div class="post-input col">
								<label<?php if (isset($alabelClass) && !empty($alabelClass)) { print ' class="'. $alabelClass. '"'; }?> for="<?=$id?>"><?=$after?></label>
							</div>
<?php	endif;*/	?>

<?php
	if (isset($details['rowClass']) and !empty($details['rowClass'])) :
/*			$c = explode(' ', $details['row_class']);
//			echo '<!-- '. print_r($c, true). ' -->';
			if (in_array( 'row', $c ))
			{*/
		static::endRow();
	endif;	?>
<?php
	/*				</div>*/?>
<?php
				$out = ob_get_contents();
				ob_clean();
				return $out;
	}

}
