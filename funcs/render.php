<?php
namespace Yuma;

/**
 * Draw form
 */
/*	if (isset($_POST['submit'])) {
		echo '<div class="errors">'. "\n";
		foreach ($errors as $err) {
			echo "\t<p class=\"error\">{$err}</p>\n";
		} 
		echo "</div>\n";
	}
	foreach ($forms as $num => $form) {
//			echo '<pre>form : '. print_r($form, true). '</pre>';
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
 */
	function renderInner(array $node)
	{
//		echo 'renderInner :';
//		echo '  : <pre>'. print_r($node, true). '</pre>';
		if (isset($node['id']) && !isset($node['inner'])) {
			echo Field::out($node);
		} elseif (isset($node['tag'])) {
			renderTag($node);
		} else {
//		echo ' -not a field : <pre>'. key($node). ':'. print_r($node, true). '</pre>';
			foreach ($node as $k => $v) {
//		echo ' -not a field : <pre>'. print_r($v, true). '</pre>';
				if (is_array($v)) {
//					echo ' v is an array';
//					renderInner($v);
					if (isset($v['tag'])) {
//						echo ' next level is a tag';
						renderTag($v);
					} elseif (isset($v['id'])) {
//						echo ' next level is a field';
						echo Field::out($v);
					}
				} else {
//					echo ' -not an array : <pre>'. print_r($v, true). '</pre>';
				}
			}
		}
	}

	function renderTag(array $node)
	{
		if (!isset($node['tag'])) {
			echo 'called renderTag without "tag" in <pre>'. print_r($node, true). '</pre>';
			return;
		}
//		echo 'renderTag :';
		if ($node['tag'] == 'fieldset') {
//			echo ' fieldset';
			if (isset($node['inner'])) {
//				echo ' with inner';
				echo Fieldset::open($node);
				//foreach ($node['inner'] as $k => $v) {
				foreach ($node as $k => $v) {
					if (is_array($v)) {
						//if ((!isset($v[0]) || !is_array($v[0]))) {
						if (isset($v['tag']) && (!isset($v[0]) || !is_array($v[0]))) {
//							echo 'calling renderTag:<pre>'. $k. ':'. print_r($v, true). '</pre>'. "<br>\n";
							renderTag($v);
						} else {
							renderInner($v);
						}
					}
				}
				if (!isset($node['selfClose'])) {
					echo Fieldset::close();
				}
			}
		} else {
//		echo ' -not a fieldset : <pre>'. $node['tag']. ' : '. print_r($node, true). '</pre>';
			echo '<'. $node['tag'];
			$skipKeys = array('tag', 'selfClose', 'innerText', 'inner');
			foreach ($node as $k => $v) {
				if (!in_array($k, $skipKeys)) {
					echo " {$k}";
					if (is_string($v) || is_integer($v)) {
						echo '="'. $v. '"';
					}
				}
			}
/*			if (isset($node['id'])) {
				echo ' id="'. $node['id']. '"';
			}
			if (isset($node['class'])) {
				echo ' class="'. $node['class']. '"';
			}
			if (isset($node['value'])) {
				echo ' value="'. $node['value']. '"';// ['inner']
			}*/
			echo '>';
			if (isset($node['innerText'])) {
				echo $node['innerText'];
			}
			if (isset($node['inner']) /*&& !isset($node['selfClose'])*/) {
//				echo ' tag has inner';
				foreach ($node['inner'] as $k => $v) {
					renderInner($v);
				}
			}
			if (isset($node['selfClose']) && $node['selfClose']) {
				echo '</'. $node['tag']. '>';
			}
		}
	}

	function recurseAll($node) {
	    if (is_array($node)) {
			if (isset($node['tag'])) {
				renderTag($node);
			} elseif (isset($node['id'])) {
				echo Field::out($node);
			} else
			foreach ($node as &$childNode) {
	            recurseAll($childNode);
	        }
	    }
	}

	function renderFields($arr)
	{
		$toClose = array();
		$i = 0;
		recurseAll($arr);
	}
