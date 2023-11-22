<?php
namespace Yuma\HTML;
class Select
{
	public static function drawSelectOptions(array $arr) {
?>
<select class="column_type" name="field_type[0]" id="field_0_2">
<?php
		static::drawOptionsColType($arr);
?>
</select>
<?php
	}

	public static function drawOptions(array $arr, $selected=null, $selValue=null) {
		ob_start();
		foreach($arr as $k => $v) {
//					echo '<!--<pre>'. print_r($v, true). '</pre>-->';
 			if (isset($v['group'])) {
				foreach($v['group'] as $grp => $value) {
					//echo '<pre>'. print_r($value, true). '</pre>';
?>
	<optgroup
<?php
					if (isset($value['label'])) {
						print ' label="'. $value['label']. '"';
					}
					if (isset($value['title'])) {
						print ' title="'. $value['title']. '"';
					}
					print '>';
					if (isset($value['contents'])) {
						foreach($value['contents'] as $val) {
	//				echo '<pre>'. print_r($val, true). '</pre>';
							print static::drawOption($val, $selected, $selValue);
						}
					}
	?></optgroup>
<?php /**/
				}/**/
			} else {
//				echo '<!-- drawOption: '. print_r($v, true). ' -->'. "\n";
				print static::drawOption($v, $selected, $selValue);/**/
			}
		}
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public static function drawOption(array $v, $selected, $selValue) {
		ob_start();
?>
<option
<?php
		if (isset($v['apply'])) {
			print ' data-apply-element="'. $v['apply']. '"';
		}
		if (isset($v['placeholder'])) {
			print ' data-apply-placeholder="'. $v['placeholder']. '"';
		}
		if (isset($v['min'])) {
			print ' data-apply-min="'. $v['min']. '"';
		}
		if (isset($v['max'])) {
			print ' data-apply-max="'. $v['max']. '"';
		}
		if (isset($v['minlength'])) {
			print ' data-apply-minlength="'. $v['minlength']. '"';
		}
		if (isset($v['maxlength'])) {
			print ' data-apply-maxlength="'. $v['maxlength']. '"';
		}
		if (isset($v['default'])) {
			print ' data-apply-default="'. $v['default']. '"';
		}
		if (isset($v['type'])) {
			print ' data-apply-type="'. $v['type']. '"';
		}
//					echo '<pre>'. print_r($v, true). '</pre>';
		if (isset($v['attr'])) {
			print ' '. $v['attr'];
		}
		if (isset($v['onselect'])) {
			print ' onselect="'. $v['onselect']. '"';
		}
		if (isset($v['title'])) {
			print ' title="'. $v['title']. '"';
		}
		if (isset($v['size'])) {
			print ' size="'. $v['size']. '"';
		}
		if (isset($v['autofocus'])) {
			print ' autofocus';
			if (!empty($v['mautofocu'])) {
				print '="'. $v['autofocus']. '"';
			}
		}
		if (isset($v['dis'])) {
			print ' disabled';
			if (!empty($v['dis'])) {
				print '="'. $v['dis']. '"';
			}
		}
		if (isset($v['autocomplete'])) {
			print ' autocomplete="'. $v['autocomplete'];
		}
		if (isset($v['form'])) {
			print ' form="'. $v['form']. '"';
		}
		if (isset($v['mul'])) {
			print ' multiple';
			if (!empty($v['mul'])) {
				print '="'. $v['mul']. '"';
			}
		}
		if (isset($v['req'])) {
			print ' required';
			if (!empty($v['req'])) {
				print '="'. $v['req']. '"';
			}
		}
		if (isset($v['name'])) {
			print ' name="'. $v['name']. '"';
			if (is_array($selected))
			{
				foreach ($selected as $s)
				{
					if ($s === $v['name'])
					{
						print ' selected';
					}
				}
			} else {
				if (isset($selected) && $selected === $v['name'])
				{
					print ' selected';
				}
			}
		}
		if (isset($v['size'])) {
			print ' size="'. $v['size']. '"';
		}
		if (isset($v['value'])) {
			print ' value="'. $v['value']. '"';
			if (is_array($selValue))
			{
				foreach ($selValue as $s)
				{
					if ($s === $v['value'])
					{
						print ' selected';
					}
				}
			} else {
				if (isset($selValue) && $selValue === $v['value'])
				{
					print ' selected';
				}
			}
		}
		if (isset($v['text']) && isset($selected) && $selected === $v['text'])
		{
			print ' selected';
		}
		print '>';
		if (isset($v['text'])) {
			print $v['text'];
/*		} elseif (isset($v['title'])) {
			echo $v['title'];*/
		} elseif (isset($v['value'])) {
			print $v['value'];
		}
?>
</option>
<?php
		$out2 = ob_get_contents();
		ob_end_clean();
		return $out2;
	}

}
