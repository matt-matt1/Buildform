<?php
namespace Yuma;

//PHP 5.3

function searchNestedArray(array $array, $search, $mode = 'value') {

    foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
        if ($search === ${${"mode"}})
            return true;
    }
    return false;
}
/*
$data = array(
    array('abc', 'ddd'),
    'ccc',
    'bbb',
    array('aaa', array('yyy', 'mp' => 555))
);

var_dump(searchNestedArray($data, 555));
 */
function findKey($tab, $key){
    foreach($tab as $k => $value){
        if($k==$key) return $value;
        if(is_array($value)){
            $find = findKey($value, $key);
            if($find) return $find;
        }
    }
    return null;
}

function findInArray(array $array, $findKey) {
	if (!is_array($array)) {
		return false;
	}
	foreach ($array as $k => $v) {
		if (isset($array[$findKey])) {
			echo 'found at ('. $findKey. ') :'. print_r($array[$findKey], true). "<br>\n";
//			echo 'found at <pre>'. print_r($array, true). "</pre><br>\n";
			return $array[$findKey];//$array;
		}
		if (is_array($v)) {
//			echo 'searching in ' .$k. ":<br>\n";
			findInArray($v, $findKey);
//		} else {
//			echo 'not in ' .$k. "<br>\n";
		}
	}
}

function WASinsertInArray(array &$array, $findKey, $value, $newKey)
{
	if (!is_array($array)) {
		return false;
	}
/*	foreach ($array as $k => $v) {
		if (array_key_exists($findKey, $array)) {
//			echo 'found at ('. $findKey. ') :'. print_r($array[$findKey], true). "<br>\n";
//			echo 'found at <pre>'. print_r($array, true). "</pre><br>\n";
	//		return $array[$findKey];//$array;
//			array_push($array[$findKey], $value);
			$array[$findKey][$newKey] = $value;
//			array_merge($array[$findKey], $value);
			return true;
		}
		if (is_array($v)) {
//			echo 'searching in ' .$k. ":<br>\n";
			findInArray($v, $findKey, $value, $newKey);
//		} else {
//			echo 'not in ' .$k. "<br>\n";
		}
	}*/
	foreach ($array as $k => $v) {
		if (is_array($v)) {
			echo 'looking for array key "'. $findKey. '" in <pre>array: '. print_r($array, true). "</pre><br>\n";
			echo 'going to insert <pre> '. print_r($value, true). "</pre><br>\n";
		if (array_key_exists($findKey, $array)) {
			$array[$findKey][$newKey] = $value;
//			echo '<pre>array: '. print_r($array, true). "</pre><br>\n";
			break;
		} elseif (is_array($v)) {
			echo 'checking level 1 in '. $k. "</pre><br>\n";
			if (array_key_exists($findKey, $v)) {
				echo "found at {$k}-{$findKey}: ". print_r($array[$k][$findKey], true). "</pre><br>\n";
				$array[$k][$findKey][$newKey] = $value;
				break;
			} elseif (is_array($v)) {
				echo 'checking level 2 in '. $k. "</pre><br>\n";
				foreach ($v as $k1 => $v1) {
					if (is_array($v1)) {
					if (array_key_exists($findKey, $v1)) {
						$array[$k][$k1][$findKey][$newKey] = $value;
						echo "found at {$k}-{$k1}-{$findKey}: ". print_r($array[$k][$k1][$findKey], true). "</pre><br>\n";
						break;
					} elseif (is_array($v1)) {
						echo 'checking level 3 in '. $k. $k1. "</pre><br>\n";
						foreach ($v1 as $k2 => $v2) {
							if (is_array($v2)) {
							if (array_key_exists($findKey, $v2)) {
								$array[$k][$k1][$k2][$findKey][$newKey] = $value;
								echo "<pre>found at {$k}-{$k1}-{$k2}-{$findKey}: ". print_r($array[$k][$k1][$k2], true). "</pre><br>\n";
								break;
							} elseif (is_array($v2)) {
								foreach ($v2 as $k3 => $v3) {
									if (is_array($v3) && array_key_exists($findKey, $v3)) {
										$array[$k][$k1][$k2][$k3][$findKey][$newKey] = $value;
										echo "<pre>found at {$k}-{$k1}-{$k2}-{$k3}-{$findKey}: ". print_r($array[$k][$k1][$k2][$k3], true). "</pre><br>\n";
									}
								}
							} else {
								//								echo "not level 3-{$k}-{$k1}-{$k2}</pre><br>\n";
							}
							}
						}
					} else {
						//						echo "not level 2={$k}-{$k1}</pre><br>\n";
					}
					}
				}
			} else {
				//				echo "not level 1-{$k}-{$k1}</pre><br>\n";
			}
		} else {
			//			echo "not level 0-{$k}</pre><br>\n";
		}
		}
	}
}

/**
 * Iterates recusively through an array, searching for a certain key
 * and inserting a value
 */
function insertInArray(array &$array, string $findKey=null, $value=null, string $newKey=null)
{
/*	static $find;
	if ($findKey) {
		$find = $findKey;
	}
	static $val;
	if ($value) {
		$val = $value;
	}
	static $nk;
	if ($newKey) {
		$nk = $newKey;
	}*/
	if (!is_array($array)) {
		return false;
	}
//			echo 'looking for array key "'. $findKey. '" in <pre>array: '. print_r($array, true). "</pre><br>\n";
//			echo 'going to insert <pre> '. print_r($value, true). "</pre><br>\n";
//			echo 'searching for "'. $findKey. '"<br>'. "\n";
	foreach ($array as $k => $v) {
		//echo 'searching for "'. $findKey. '" in <pre> '. print_r($v, true). "</pre><br>\n";
		if (is_string($k) && ((mb_substr($findKey, -1) === '_' && strpos($k, $findKey) === 0) || $k === $findKey)) {
			$array[$k][$newKey] = $value;
		//	echo 'found in "'. $k. '" of <pre>'. print_r($v, true). "</pre><br>\n";
//			echo 'result <pre>'. print_r($array, true). "</pre><br>\n";
		} elseif (is_array($v)) {
			insertInArray($array[$k], $findKey, $value, $newKey);
			//insertInArray($array[$k]);
		}
	}
//	return $array;
}

/**
 * Iterates recusively through an array, searching for a certain key that has a certain value
 * and appending a value to the "inner" section
 */
function appendToArray(array &$array, string $findKey, string $findVal, $value, $newKey=null)
{
	if (!is_array($array)) {
		return false;
	}
//			echo 'looking for array key "'. $findKey. '" in <pre>array: '. print_r($array, true). "</pre><br>\n";
//			echo 'going to insert <pre> '. print_r($value, true). "</pre><br>\n";
	foreach ($array as $k => $v) {
//			echo 'searching <pre> '. print_r($v, true). "</pre><br>\n";
		if ($k === $findKey && is_string($v) && $v === $findVal) {
			if (isset($newKey)) {
				$array[$newKey] = $value;
			} else {
				$array[] = $value;
			}
		} elseif (is_array($v)) {
			appendToArrayInner($array[$k], $findKey, $findVal, $value, $newKey);
		}
	}
//	return $array;
}

/**
 * Iterates recusively through an array, searching for a certain key that has a certain value
 * and appending a value to the "inner" section
 */
function appendToArrayInner(array &$array, string $findKey, string $findVal, $value, $newKey=null)
{
	if (!is_array($array)) {
		return false;
	}
//			echo 'looking for array key "'. $findKey. '" in <pre>array: '. print_r($array, true). "</pre><br>\n";
//			echo 'going to insert <pre> '. print_r($value, true). "</pre><br>\n";
	foreach ($array as $k => $v) {
//			echo 'searching <pre> '. print_r($v, true). "</pre><br>\n";
		if ($k === $findKey && is_string($v) && $v === $findVal) {
			if (isset($newKey)) {
				$array['inner'][$newKey] = $value;
			} else {
				$array['inner'][] = $value;
			}
		} elseif (is_array($v)) {
			appendToArrayInner($array[$k], $findKey, $findVal, $value, $newKey);
		}
	}
//	return $array;
}

/**
 * Returns a sub-array from a source array
 * by recusively cycling through the given array, searching for a certain key that has a certain value
 */
function getInnerOfArray(array $array, string $findKey, string $findVal)
{
/*	if (!is_array($array)) {
		return false;
	}*/
//			echo 'looking for array key "'. $findKey. '" in <pre>array: '. print_r($array, true). "</pre><br>\n";
//			echo 'going to insert <pre> '. print_r($value, true). "</pre><br>\n";
	foreach ($array as $k => $v) {
//			echo 'searching <pre> '. print_r($v, true). "</pre><br>\n";
		if ($k === $findKey && is_string($v) && $v === $findVal) {
			echo 'getInnerOfArray inner: '. $findKey. '='. $findVal. '<pre>'. print_r($array['inner'], true). "</pre><br>\n";
			return $array['inner'];
			//return $v;//['inner'];
/*			if (isset($newKey)) {
				$array['inner'][$newKey] = $value;
			} else {
				$array['inner'][] = $value;
			}*/
		} elseif (is_array($v)) {
			getInnerOfArray($array[$k], $findKey, $findVal);
		}
	}
//	return false;//$array;
}
