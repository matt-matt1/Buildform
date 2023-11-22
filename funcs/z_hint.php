<?php
namespace Yuma;

//use Yuma\HTML;
	function ajax_hint($data)
	{
		if (!isset($data) || !isset($data['q']))
			return;
//		throw new Exception('here I am');
		$words = array(
			'alphabet','beetroot','charlie','delta','elephant','foxtrot','golf','house','indigo','jockey','kilo','lima','mike','november','oscar','papa','quickly','romeo','sierra','tango','uniform','victor','water','xray','yankee','zulu'
		);
		$o = array();
		foreach ($words as $w) {
			if (strtolower($data['q']) == substr($w, 0, 1))
				$o[] = $w;
		}
		//return 'data='. $data['q']. ', ss='. substr('asdf', 0, 1);//$o;
		return is_array($o) ? implode(', ', $o) : 'data='. $data['q']. ', ss='. substr('asdf', 0, 1);//$o;
		//return /*is_array($o) ? implode(', ', $o) :*/ 'data='. $data['q']. ', ss='. substr('asdf', 0, 1);//$o;
//		return 'data='. print_r($data, true). '+trueyddddddd';
	}
add_hook('ajax_hint', 'ajax_hint');
//Hook::add('ajax_hint', 'ajax_hint');
//print show_hooks();
