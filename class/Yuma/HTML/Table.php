<?php

namespace Yuma\HTML;
use function Yuma\do_mbstr;

class Table
{
	//const FILT = 'Filter';
	const FILT = 'All';
	//const NOFILT = '-';
	private $params;
	private $defaults = array(
		'id' => 'mytable',
		'name' => '',
		'class' => '',
		'checkmarks' => true,
		'head' => true,
		'foot' => true,
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
			'pre' => "&lsh;",/*↰ - UP ARROW WITH TIP LEFT - U+021B0 UNICODE - &#x21b0; HEX CODE - &#8624; HTML CODE - &lsh; HTML ENTITY - \21B0*/
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
//		'filters' => array(
//			array(
//				'element' => 'dropdown|radio',
//				'name' => 'POSTED-name',
//				'list' => array(
//					'command' => 'className::method(parameters)',	- NOT IMPLEMENTED YET
//					'text' => '{%variable_from_the_source_(array_below)%} {%user_last%} - text_to_display',
//					'value' => 'variable_from_the_source_(array_below)',
//					'inner' => array_of_data,
//				),
//			),
//		),
		'canSelectAll' => 'Invert'
	);

	public function __construct(array $params=null) {
		//$this->param = $param;
		$this->params = array_merge($this->defaults, (array)$params);
		unset($this->defaults);
/*		if (isset($this->params['data'])) {
			//$this->data = $this->params['data']);
			$this->params['pagination']['data'] = $this->params['data']);
		}*/
		if (!isset($this->params['data'])) {
			return;
		}
		if (isset($this->params['pagination']) /*&& isset($this->params['data'])*/) {
			$this->params['pagination']['id'] = $this->params['id'];
			$this->pagi = new Pagination($this->params['pagination'], $this->params['data']);
//			$this->data = $this->pagi->getDataForPage(/*$this->pagi->page*/);
		} else {
			$this->data = $this->params['data'];
		}
	}

	/**
	 * Constructs the HTML code for each filter
	 */
	private function makeFilters(array $array=null)
	{
		if (isset($array)) {
			$this->params['filters'] = $array;
		}
		if (!isset($this->params['filters']) /*|| !isset($array)*/) {
			return false;
		}
//		echo 'filters:<pre>'. print_r($this->params['filters'], true). '</pre>'. "\n";
		$out = array();
		foreach ($this->params['filters'] as $c => $filter) {
			if (!isset($filter['element'])) {
				$filter['element'] = 'dropdown';
			}
			if (!isset($filter['name'])) {
				$filter['name'] = 'filter_'. rand();
			}
			switch ($filter['element']) {
			case 'radio':
				$out[$c] = '<div class="filter-'. $filter['element']. ' filter-'. $filter['name']. '">';
				if (isset($filter['list'])) {
					if (isset($filter['list']['command']) && is_callable($filter['list']['command'])) {
						ob_start();
						call_user_func($filter['list']['command']);
						$source = ob_get_contents();
						ob_end_clean();
					} elseif (is_array($filter['list']) && isset($filter['list']['inner'])) {
						$source = $filter['list']['inner'];
					}
					$filt = isset($filter['list']['placeholder']) ? $filter['list']['placeholder'] : static::FILT;
					$out[$c] .= '<div class="radio-all" id="radio-'. $filter['name']. '-all">';
					$out[$c] .= "\t". '<input id="radioButton-'. $filter['name']. '-all" name="filter-'. $c. '" value="" type="radio"';
					if (!isset($_GET['filter-'.$filter['name']]) || empty($_GET['filter-'.$filter['name']])) {
						$out[$c] .= ' checked';
					}
					$out[$c] .= '>';
					$out[$c] .= "\t". '<label for="radioButton-'. $filter['name']. '-all" id="radioLabel-'. $filter['name']. '-all">'. $filt. '</label>';
					$out[$c] .= '</div>';
					foreach ($source as $item) {
						$i_name = isset($item['name']) ? $item['name'] : $c;
						$text = isset($filter['list']['text']) ? $filter['list']['text'] : $i_name;
						//foreach($replace_array as $key=>$value) $s=str_replace($key,$value,$s);
						if (do_mbstr('strpos', $text, '{%') !== false) {
							$text = trim(preg_replace_callback('|{%(\w+)%}|', function ($matches) use($item) {
								//	echo '<pre>item:'. print_r($item, true). '!</pre>'. "\n";
								return isset($item[$matches[1]]) ? $item[$matches[1]] : 'unk-'. $matches[1];
							}, $text), ' -.,:/');
						}
						$value = isset($filter['list']['value']) && isset($item[trim($filter['list']['value'])]) ? trim($item[trim($filter['list']['value'])]) : $i_name. '_'. rand();
						if (empty(trim($text)) || empty(trim($value))) {
							continue;
						}
						$out[$c] .= '<div class="radio-'. $i_name. '" id="radio-'. $filter['name']. '-'. $value. '">';
						$out[$c] .= "\t". '<input id="radioButton-'. $filter['name']. '-'. $value. '" name="filter-'. $c. '" value="'. $value. '" type="radio"';
						if (isset($_GET['filter-'.$filter['name']]) && $_GET['filter-'.$filter['name']] == $value) {
							$out[$c] .= ' checked';
						}
						$out[$c] .= '>';
						$out[$c] .= "\t". '<label for="radioButton-'. $filter['name']. '-'. $value. '" id="radioLabel-'. $filter['name']. '-'. $value. '">'. $text. '</label>';
						$out[$c] .= '</div>';
					}
				}
				$out[$c] .= '</div>';
				break;
//			case 'dropdown':
			default:
				$out[$c] = '<select class="filter-'. $filter['element']. ' filter-'. $filter['name']. '" name="filter-'. $filter['name']. '">';
				if (isset($filter['list'])) {
					if (isset($filter['list']['command']) && is_callable($filter['list']['command'])) {
						ob_start();
						call_user_func($filter['list']['command']);
						$source = ob_get_contents();
						ob_end_clean();
					} elseif (is_array($filter['list']) && isset($filter['list']['inner'])) {
						$source = $filter['list']['inner'];
					}
//					if (isset($filter['list']['placeholder'])) {
//						$out[$c] .= '<option disabled selected>'. $filter['list']['placeholder']. '</option>';
//					}
					$filt = isset($filter['list']['placeholder']) ? $filter['list']['placeholder'] : static::FILT;
//					$nofilt = isset($filter['list']['none']) ? $filter['list']['none'] : static::NOFILT;
					//$out[$c] .= '<option disabled selected>'. $filt. '</option>';
					$out[$c] .= '<option value=""';
					if (!isset($_GET['filter-'.$filter['name']]) || empty($_GET['filter-'.$filter['name']])) {
						$out[$c] .= ' selected';
					}
					$out[$c] .= '>'. $filt. '</option>';
//					$out[$c] .= '<option>'. $nofilt. '</option>';
					if (isset($source) && is_array($source)) {
						foreach ($source as $item) {
//			echo '<pre>item:'. print_r($item, true). '!</pre>'. "\n";
							$i_name = isset($item['name']) ? $item['name'] : $c;
							$text = isset($filter['list']['text']) ? $filter['list']['text'] : $i_name;
							if (do_mbstr('strpos', $text, '{%') !== false) {
								$text = trim(preg_replace_callback('|{%(\w+)%}|', function ($matches) use($item) {
									//	echo '<pre>item:'. print_r($item, true). '!</pre>'. "\n";
									return isset($item[$matches[1]]) ? $item[$matches[1]] : 'unk-'. $matches[1];
								}, $text), ' -.,:/');
							}
							$value = isset($filter['list']['value']) && isset($item[trim($filter['list']['value'])]) ? trim($item[trim($filter['list']['value'])]) : $i_name. '_'. rand();
							if (empty(trim($text)) || empty(trim($value))) {
								continue;
							}
							$out[$c] .= "\t". '<option id="option-'. $filter['name']. '-'. $value. '"';
							$out[$c] .= ' class="filters-option option-'. $i_name. '" value="'. $value. '"';
							$vals = array_column($this->params['data'], $c);
							//if (array_search($value, $vals) === false) {
							if (array_search($text, $vals) === false) {
//							if (!in_array($value, array_values($this->params['data'])) {
								$out[$c] .= ' disabled';
							}
							if (isset($_GET['filter-'.$filter['name']]) && $_GET['filter-'.$filter['name']] === $value) {
								$out[$c] .= ' selected';
							}
							$out[$c] .= '>';
							$out[$c] .= $text. '</option>';
						}
					}
				}
				$out[$c] .= '</select>';
			}
		}
		return $out;
	}

	/**
	 * Removes each column that contain no values from the class data
	 */
	public function hideEmptyColumns()
	{
//		echo '<pre>data:'. print_r($this->data, true). '!</pre>'. "\n";
		if (!isset($this->data) || !is_array($this->data)) {
			return;
		}
		if (is_array($this->data[0])) {
			//$cols = array_keys($this->params['data'][0]);
			foreach ($this->data as $rows) {
				foreach ($rows as $k => $v) {
					if (empty($v)) {
						$empties[$k] = true;
					} else {
						$empties[$k] = false;
					}
				}
			}
			//echo '<pre>'. print_r($empties, true). '</pre>'. "\n";
//			echo '<script>console.log(empties: '. print_r($empties, true). ');</script>'. "\n";
//			error_log ('empties: '. print_r($empties, true). '!!!');//. "\n";
            $cols = array_keys($this->params['data']);
            foreach ($empties as $k => $v) {
				if ($v) {
					foreach ($this->data as $rows) {
						foreach ($rows as $k => $v) {
							if ($k == $col) {
								unset ($this->data[$rows][$col]);
							}
						}
					}
				}
			}
		} else {
//			$cols = array_keys($this->params['data']);
			foreach ($rows as $k => $v) {
				if (empty($v)) {
					$empties[$k] = true;
				} else {
					$empties[$k] = false;
				}
			}
//			echo '<pre>'. print_r($empties, true). '</pre>'. "\n";
			foreach ($empties as $k => $v) {
				if ($v) {
					foreach ($this->data as $col => $val) {
						if ($k == $col) {
							unset ($this->data[$col]);
						}
					}
				}
			}
		}
	}

	/**
	 * Constructs the HTML code for the table head section
	 */
	private function head()
	{
		ob_start();
?>
<thead>
<?php
		if (isset($this->data) && is_array($this->data)) {
			echo '<tr class="thead sticky">'. "\n";
			if (isset($this->params['checkmarks'])) {
				echo '<th><span class="thead checkbox">';
				if (isset($this->params['canSelectAll']) && (true == $this->params['canSelectAll'])) {
					if (is_string($this->params['canSelectAll'])) {
						$title = $this->params['canSelectAll'];
					} else {
						$title = 'All';
					}
					//echo '<input type="checkbox" title="'. (is_string($this->params['canSelectAll'])) ? $this->params['canSelectAll'] : 'All'. '" class="check-all" name="checked" value="all">';
					echo '<input type="checkbox" title="'. $title. '" class="check-all" name="checked" value="all">';
				} else {
					echo '&nbsp;';
				}
				echo '</span></th>';
			}
			$i = 0;
			foreach (array_keys($this->data[0]) as $v) {
				if (!isset($this->params['omit']) || !in_array($v, $this->params['omit'])) {
					echo '<th class="thead" data-col="'. $i. '" data-name="'. $v. '">';
					$printed = false;
					if (isset($this->params['rename']) && is_array($this->params['rename'])) {
						foreach ($this->params['rename'] as $was => $new) {
							if ($v == $was) {
								echo str_replace('_', ' ', $new);
								$printed = true;
								break;
							}
						}
					}
					if (!$printed) {
						echo str_replace('_', ' ', $v);
					}
					echo '</th>';
					$i++;
				}
			}
			echo '</tr>'. "\n";
		}
		$this->drawFilters();
?>
</thead>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	private function isFilter($var)
	{
		$sub = do_mbstr('substr', $var, 0, 7);
		//echo 'testing "filter-" == "'. $sub. '"'. "\n";
		return $sub === 'filter-' && !empty($_GET[$var]);
	}

	/**
	 * Constructs the HTML code for the table body section (main data)
	 */
	private function body()
	{
		ob_start();
?>
<tbody>
<?php
		if (isset($this->data) && is_array($this->data)) {
			$row = 0;
			$test = array_filter(array_keys($_GET), array($this, 'isFilter'));
			foreach ($this->data as $val) {
/*				if (is_array($test) && count($test)) {
					foreach ($test as $f) {
						$name = do_mbstr('substr', $f, 7);	// store filtered column name
						foreach ($val as $k => $v) {
							if (isset($val[$name])) {
								echo '"'. $name. '" found';
							} else {
//								echo 'testing "'. $k. '" is like "'. $name. '_"!'. "\n";
//							}
								$pattern = '/'. $name. '_/';
//								$name = preg_match($pattern, $k) ? $k : '';
							}
							if ((isset($val[$name]) || preg_match($pattern, $k)) && $v != $_GET[$f]) {
//							if (isset($val[$name]) && $v != $_GET[$f]) {
								//echo 'found "'. $name. '" in "'. $k. '", skipping row!'. "\n";
								echo 'found key "'. $k. '" value "'. $v. '", skipping row!'. "\n";
								continue 3;
							}
						}
/ *
//							echo 'val:<pre>'. print_r($val, true). '</pre>!'. "\n";
						if (isset($val[$name])) {
							echo 'testing "'. $val[$name]. '" == "'. $_GET[$f]. '"!'. "\n";
						} elseif (is_numeric($_GET[$f]) && isset($val[$name.'_id'])) {
							echo 'non-existant "'. $name. '"!'. "\n";
							$name .= '_id';
							echo 'trying num "'. $name. '" for "'. $_GET[$f]. '"!'. "\n";
						} else {
							echo 'non-existant "'. $name. '"!'. "\n";
							if (isset($this->params['rename']) && in_array($name, array_values($this->params['rename']))) {
								$name = array_search($name, $this->params['rename']);
							}
							echo 'trying "'. $name. '" for "'. $_GET[$f]. '"!'. "\n";
						}
						if (isset($val[$name]) && $val[$name] != $_GET[$f]) {
							echo 'found "'. $name. '", skipping row!'. "\n";
							continue 2;		// skip this row if filtered-out
						}* /
				//		//echo $filter. ' =><pre>'. print_r($_GET[$filter], true). '</pre>';
				//		echo $name. ' => '. $_GET[$f]. "\n";
					}
				}*/
?><tr class="tbody checkbox" data-row="<?=$row?>">
<?php
				if (isset($this->params['checkmarks']) && $this->params['checkmarks']) {
				//	echo '<label for="row_'. $row. '">';
					echo '<td';
					//if (isset($val['business_id'])) {
					if (isset($this->params['data_id']) && isset($val[$this->params['data_id']])) {
						echo ' title="'. $val[$this->params['data_id']]. '"';
					}
					//echo '><input id="row_'. $row. '" name="checked[row_'. $row. ']" class="tbody checkbox" type="checkbox"/></td>';
					echo '><input id="row_'. $row. '" name="checked[id_'. $val[$this->params['data_id']]. ']" class="tbody checkbox" type="checkbox"/></td>';
				}
				$i = 0;
				foreach ($val as $k => $v) {
					if (!isset($this->params['omit']) || !in_array($k, $this->params['omit'])) {
//						echo '<label for="row_'. $row. '">';
						echo '<td class="tbody" data-col="'. $i. '"';
						if (isset($this->params['title']) && is_array($this->params['title']) /*&& in_array($this->params['title'], $v)*/) {
							foreach ($this->params['title'] as $src => $t) {
								if ($k == $src && isset($val[$t])) {
									echo ' title="'. $val[$t]. '"';
								}
							}
						}
						echo '>';
						echo '<label for="row_'. $row. '">';
						echo print_r($v, true);
						echo '</label>';
						'</td>';
//						echo '</label>';
					}
					$i++;
				}
				$row++;
//				if (isset($this->params['checkmarks']) && $this->params['checkmarks']) {
//					echo '</label>';
//				}
?></tr>
<?php
			}
		}
?>
</tbody>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	/**
	 * Constructs the HTML code for the table footer section
	 */
	private function foot()
	{
		ob_start();
?>
<tfoot>
<?php
		if (isset($this->data) && is_array($this->data)) {
			echo '<tr class="tfoot">'. "\n";
			if (isset($this->params['checkmarks'])) {
				echo '<th><span class="tfoot checkbox">&nbsp;</span></th>';
			}
			$i = 0;
			foreach (array_keys($this->data[0]) as $v) {
				if (!isset($this->params['omit']) || !in_array($v, $this->params['omit'])) {
					echo '<th class="tfoot" data-col="'. $i. '">'. $v. '</th>';
					$i++;
				}
			}
			echo '</tr>'. "\n";
		}
?>
</tfoot>
<?php
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function actions()
	{
		if (isset($data)) {
			$this->params['data'] = $data;
		}
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

	/**
	 * Print the HTML code (from the makeFilters method) for each data-column
	 */
	public function drawFilters()
	{
		if (isset($data)) {
			$this->params['data'] = $data;
		}
		//$filters = $this->makeFilters();
		//if (!$filters) {
		if (!$filters = $this->makeFilters()) {
			return;
		}
		if (!is_array($filters)) {
			$filters = [$filters];
		}
		//echo 'filters:<pre>'. print_r($filters, true). '</pre>'. "\n";
		//echo 'filters:<pre>'. print_r(array_keys($filters), true). '</pre>'. "\n";
		if (!is_array($filters) || empty($filters)) {
			return;
		}
//		print '<thead class="filters-head">';
		print '<tr class="filters-row">';
		if (isset($this->params['checkmarks'])) {
			echo '<th class="filters-checkmarks">&nbsp;</th>';
		}
		foreach (array_keys($this->data[0]) as $col) {
			if (!isset($this->params['omit']) || !in_array($col, $this->params['omit'])) {
				print '<th data-column="'. $col. '">';
				if (in_array($col, array_keys($filters)) && isset($filters[$col])) {
					print $filters[$col];//$v;
				} else {
					print '&nbsp;';
				}
				print '</th>';
			}
		}
		print '</tr>';
//		print '</thead>';
		print "\n";
	}

	/**
	 * Use the above methods to write valid HTML code to construct the table
	 */
	public function render($data=null)
	{
		if (isset($data)) {
			$this->params['data'] = $data;
		}
//			echo 'render<pre>'. print_r($this->params, true). '</pre>';
		if (!isset($this->params['data']) || empty($this->params['data'])) {
/*			$note = new Note(array(
				'notice' => Note::error,
				'message' => 'no data',
			));
			echo $note->display();*/
			return;
		}
		$id = isset($this->params['id']) ? $this->params['id'] : '';
		$name = isset($this->params['name']) ? $this->params['name'] : '';
		$class = isset($this->params['class']) ? $this->params['class'] : $id;
		$cap = isset($this->params['caption']) ? $this->params['caption'] : '';
		ob_start();
		if ((isset($this->params['actions']) && is_array($this->params['actions'])) || (isset($this->params['pagination']) && is_array($this->params['pagination']))) {
?><form id="form_<?php echo ((!empty($id)) ? $id : ''). rand();?>" class="table-form form-<?php echo ((!empty($id)) ? $id : '')?>"><?php
		}
		if (isset($_GET['tab'])) {		// stay on the current tab, if already POSTED
?>	<input type="hidden" name="tab" value="<?=$_GET['tab']?>"><?php
		}
?>	<table<?php
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
/*			$note = new Note(array(
				'notice' => Note::error,
				'message' => 'no data'. (!empty($id) ? ' id="'. $id. '"' : ''),
			));
			echo $note->display();*/
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
?>	</table><?php
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

}
