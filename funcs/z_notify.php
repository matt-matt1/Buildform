<?php
namespace Yuma;

//use Yuma\HTML;
//{tab_id: "user-tab", tab_list: "admin"}
function ajax_notify($data = null)
{
	if (!isset($data)) {
		throw new Exception(t('_Missing data'));
		return;
	}
	$tab_id = $data['tab_id'];
	$tab_list = 'tabs_'. $data['tab_list'];
//	if (isset($tab_list) /*&& $tab_list instanceof TabBar*/) {
//		echo json_encode(array('var' => $tab_list, 'isset' => true));
//	}
	if (isset($tab_list) && $tab_list instanceof TabBar) {
		$tab_list->makeActive($tab_id);
		echo json_encode(array('var' => $tab_list, 'isset' => true));
	}
}
add_hook('ajax_notify', 'ajax_notify');
