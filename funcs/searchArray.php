<?php
namespace Yuma;

function searchArray($array, $key, $value=null)
{
/**/
	$results = array();

	if (is_array($array)) {
		if (isset($array[$key])) {
			if (isset($value) && $array[$key] == $value) {
				$results[] = $array;
			} elseif (!isset($value)) {
				$results[] = $array;
			}
		}

		foreach ($array as $subarray) {
			$results = array_merge($results, searchArray($subarray, $key, $value));
		}
	}

	return $results;
/**/
/*
	$arrIt = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));

	foreach ($arrIt as $sub) {
		$subArray = $arrIt->getSubIterator();
		if (isset($subArray[$key])) {
			if (isset($value) && $subArray[$key] == $value) {
//		if ($subArray['name'] === 'cat 1') {
				$outputArray[] = iterator_to_array($subArray);
			} elseif (!isset($value)) {
				$outputArray[] = iterator_to_array($subArray);
			}
		}
	}
*/
}
