<?php

namespace Yuma\HTML;
/**
 * Displays a HTML form
 * Notes:
 * After opening the form errors are displayed, if any: a <div class="errors"> is displayed,
 * followed by each error: <p class="error">.
 */
class HTMLForm
{
	private $forms = array();
//	private $params;
	private $defaults = array(
		'id' => 'optional:string',
		'method' => 'optional:string',
		'class' => '',
		'action' => 'optional:string',
		'inner' => array(),
/*		'foot' => true,
		'caption' => '',
		'pagination' => array(
			'first' => '[<<]',
			'previous' => '<',
			'rpp' => 25,
			'recordsPerPage' => array(
				10, 25, 50,
			),
			'next' => '>',
			'last' => '[>>]',
			'maxDisplayPages' => 6,
			'hideFor1Page' => false,
		),
		'actions' => array(
			'pre' => "&lsh;",
			'extra' => 'style="transform:rotate(180deg)"',
			'inner' => array(
				array(
					'text' => 'Delete',
					'title' => 'Delete',
					'link' => '?delete',
				),
				array(
					'text' => 'Edit',
					'title' => 'Edit',
					'link' => '?edit',
				),
			),
		),
		'canSelectAll' => 'Invert'*/
	);
	private $errors = array();

	public function __construct(array $forms=null)
	{
		$this->forms = $forms;
	}
/*	public function __construct(array $params=null) {
		//$this->param = $param;
		$this->params = array_merge($this->defaults, (array)$params);
		unset($this->defaults);
		if (!isset($this->params['inner'])) {
			return;
		}
	}*/

	private function renderErrors()
	{
		ob_start();
//		if (isset($_POST['business_name']) && !empty($this->errors)) {
			echo '<div class="errors">'. "\n";
			foreach ($this->errors as $err) {
				echo "\t<p class=\"error\">{$err}</p>\n";
			} 
			echo "</div>\n";
//		}
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function appendError($err)
	{
		$this->errors[] = $err;
	}

	/**
	 */
	public function render()
	{
		if (empty($this->forms)) {
			return false;
		}
		ob_start();
		foreach ($this->forms as $num => $form) {
	//			echo '<pre>form : '. print_r($form/*arr['fieldsets']*/, true). '</pre>';
			$id = isset($form['id']) ? $form['id'] : 'form_'. (is_numeric($num) ? intval($num)+1 : $num);			
?>	<form id="<?=$id?>"<?php
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
			if (!empty($this->errors)) {
				echo $this->renderErrors();
			}
			if (isset($form['inner'])) {
				$this->recurseRender($form['inner']);
			}
?>	</form>
	<?php
		}//end foreach form
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
	
	private function renderInner(array $node)
	{
//		ob_start();
//		echo 'renderInner :';
//		echo '  : <pre>'. print_r($node, true). '</pre>';
		if (isset($node['id']) && !isset($node['inner'])) {
			echo Field::out($node);
		} elseif (isset($node['tag'])) {
			$this->renderTag($node);
		} else {
//		echo ' -not a field : <pre>'. key($node). ':'. print_r($node, true). '</pre>';
			foreach ($node as $k => $v) {
//		echo ' -not a field : <pre>'. print_r($v, true). '</pre>';
				if (is_array($v)) {
//					echo ' v is an array';
//					renderInner($v);
					if (isset($v['tag'])) {
//						echo ' next level is a tag';
						$this->renderTag($v);
					} elseif (isset($v['id'])) {
//						echo ' next level is a field';
						echo Field::out($v);
					}
				} else {
//					echo ' -not an array : <pre>'. print_r($v, true). '</pre>';
				}
			}
		}
//		$out = ob_get_contents();
//		ob_end_clean();
//		return $out;
	}

	private function renderTag(array $node)
	{
/*		if (!isset($node['tag'])) {
			echo 'called renderTag without "tag" in <pre>'. print_r($node, true). '</pre>';
			return;
		}*/
//		ob_start();
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
							$this->renderTag($v);
						} else {
							$this->renderInner($v);
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
					$this->renderInner($v);
				}
			}
			if (isset($node['selfClose']) && $node['selfClose']) {
				echo '</'. $node['tag']. '>';
			}
		}
//		$out = ob_get_contents();
//		ob_end_clean();
//		return $out;
	}

	private function recurseRender($node)
	{
//		ob_start();
	    if (is_array($node)) {
			if (isset($node['tag'])) {
				$this->renderTag($node);
			} elseif (isset($node['id'])) {
				echo Field::out($node);
			} else
			foreach ($node as &$childNode) {
	            $this->recurseRender($childNode);
	        }
	    }
//		$out = ob_get_contents();
//		ob_end_clean();
//		return $out;
	}
/*
	private function renderFields($arr)
	{
		$toClose = array();
		$i = 0;
		recurseAll($arr);
	}*/
/*
	public function WASactions()
	{
		if (isset($this->params['actions']) && is_array($this->params['actions'])) {
?><div class="table-actions sticky">
<?php
			if (isset($this->params['actions']['pre'])) {
?>	<div class="pre"<?php
				if (isset($this->params['actions']['pre']['extra'])) {
					echo ' '. $this->params['actions']['pre']['extra'];
				}
?>><?php
				echo $this->params['actions']['pre'];
?>	</div>
<?php
			}
			if (isset($this->params['actions']['inner']) && is_array($this->params['actions']['inner'])) {
				foreach ($this->params['actions']['inner'] as $v) {
?>	<button type="submit" name="table-action"<?php
					if (isset($v['value'])) {
						echo ' value="'. $v['value']. '"';
					}
?>class="table-action<?php
					if (isset($v['class'])) {
						echo ' '. $v['class'];
					}
?>"<?php
					if (isset($v['link'])) {
						echo ' data-href="'. $v['link']. '"';
					}
					$not = array('text', 'link', 'class', 'value');
					foreach ($v as $t => $a) {
						if (!in_array($a, $not)) {
							echo ' '. $t. '="'. $a. '"';
						}
					}
?>><span><?php		echo $v['text'];
?></span></button>
<?php
				}
			}
?></div>
<?php
		}
	}
 *//*
	public function WASrender($data=null)
	{
//			echo 'render<pre>'. print_r($this->params, true). '</pre>';
		if (!isset($this->params['data']) || empty($this->params['data'])) {
/ *			$note = new Note(array(
				'notice' => Note::error,
				'message' => 'no data',
			));
			echo $note->display();* /
			return;
		}
		ob_start();
		$id = isset($this->params['id']) ? $this->params['id'] : '';
		$name = isset($this->params['name']) ? $this->params['name'] : '';
		$class = isset($this->params['class']) ? $this->params['class'] : $id;
		$cap = isset($this->params['caption']) ? $this->params['caption'] : '';
		if ((isset($this->params['actions']) && is_array($this->params['actions'])) || (isset($this->params['pagination']) && is_array($this->params['pagination']))) {
?><form id="form_<?php echo ((!empty($id)) ? $id : ''). rand();?>" class="form-<?php echo ((!empty($id)) ? $id : '')?>"><?php
		}
?><table<?php
		if (!empty($id)) { echo ' id="'. $id. '"'; }
		if (!empty($name)) { echo ' name="'. $name .'"'; }
		if (!empty($class)) { echo ' class="'. $class. '"'; }
?>><?php
		if (!empty($cap)) { echo '<caption>'. $cap. '</caption>'; }
		if (isset($this->params['pagination']) && isset($this->pagi) && is_object($this->pagi)) {
			$this->data = $this->pagi->getDataForPage();
		} else {
			$this->data = $this->params['data'];
		}
		if (empty($this->data)) {
/ *			$note = new Note(array(
				'notice' => Note::error,
				'message' => 'no data'. (!empty($id) ? ' id="'. $id. '"' : ''),
			));
			echo $note->display();* /
			return;
		}
		//if (!isset($this->param['noHead']) || !$this->param['noHead']) {
		if ($this->params['head']) {
			echo $this->head();
		}
		echo $this->body();
		if ($this->params['foot']) {
			echo $this->foot();
		}
?></table><?php
		if (isset($this->params['actions']) && is_array($this->params['actions'])) {
			$this->actions();
		}
		if (isset($this->params['pagination']) && is_array($this->params['pagination'])) {
//		echo 'pagination:'. print_r($this->params['pagination'], true). "<br>\n";
			echo $this->pagi->render();
		}
		if ((isset($this->params['actions']) && is_array($this->params['actions'])) || (isset($this->params['pagination']) && is_array($this->params['pagination']))) {
?></form><?php
		}
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
  */
}
