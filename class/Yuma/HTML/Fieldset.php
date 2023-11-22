<?php

namespace Yuma\HTML;

class Fieldset {

	public static function open($v)
	{
		ob_start();
		echo '<!--Fieldset-->'. "\n";;
		echo '<fieldset id="'. (isset($v['id']) ? $v['id'] : 'fieldset_'. /*($fieldset +*/ 1/*)*/). '"';
//		if (isset($val['selected']) && $val['selected']) {
//			echo ' selected="selected"';
//		}
		$skipKeys = array('tag', 'legend', 'id', 'innerText', 'inner', 'selfClose');
		foreach ($v as $k => $val) {
			if (!in_array($k, $skipKeys)) {
				echo " {$k}";
				if (is_string($val) || is_integer($val)) {
					echo '="'. $val. '"';
				}
			}
		}
/*		if (isset($val['class'])) {
			echo ' class="'. $val['class']. '"';
		}*/
//		if (isset($val['value'])) {
//			echo ' value="'. $val['value']. '"';
//		}
		echo '>';
		if (isset($v['innerText'])) {
			echo $v['innerText'];
		}
		echo "\n";
		if (isset($v['legend'])) {
			echo "\t". '<legend for="'. (isset($v['id']) ? $v['id'] : 'legend'). '"';
//		if (isset($opt['selected']) && $opt['selected']) {
//			echo ' selected="selected"';
//		}
//		if (isset($opt['class'])) {
//			echo ' class="'. $opt['class']. '"';
//		}
//		if (isset($opt['value'])) {
//			echo ' value="'. $opt['value']. '"';
//		}
			echo '>';
			echo $v['legend'];
			echo "</legend>\n";
		}
		$out = ob_get_contents();
		ob_clean();
		return $out/*. '<!--Fieldset-->'*/;
	}

	public static function close()
	{
		return '</fieldset><!--end Fieldset-->';
	}
}
