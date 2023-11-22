<?php
namespace Yuma;
function array_pairs( string $source )
{
	$a = Array();
	$b = Array();
	$pos = strpos($source, '?');
	$pos1 = strpos($source, '&');
/*	$start = ($pos !== false || $pos1 !== false) ?
		(($pos !== false && $pos1 !== false) ? (($pos < $pos1) ? $pos : $pos1) :
		($pos !== false) ? $pos :
		($pos1 !== false) ? $pos1 : null) :
		null;*/
	if ($pos !== false || $pos1 !== false)
	{
		if ($pos !== false && $pos1 !== false)
		{
			$start = ($pos < $pos1) ? $pos : $pos1;
		} elseif ($pos !== false)
		{
			$start = $pos;
		} elseif ($pos1 !== false)
		{
			$start = $pos1;
		}
		$query = do_mbstr('substr', $source, $start+1);
		parse_str($query, $b);
		$source = do_mbstr('substr', $source, 0, $start);
	}
	$source = explode('/', $source);
	$a['page'] = (count($source) % 2) ? $source[count($source)-1] : $source[count($source)-2];
	if (count($source) > 1)
	{
		$t = array_chunk( $source, 2 );
		foreach ($t as $p)
		{
			if (isset($p[1]))
			{
				$a[$p[0]] = $p[1];
			} else {
				$a[$p[0]] = true;
			}
		}
	}
	return array_merge($a, $b);
}
