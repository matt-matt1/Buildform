<?php
namespace Yuma;
use Yuma\HTML\Head;
	/**
	 * Returns the HTML code for the head section
	 *  and includes the scripts, if any, from the Scripts class
	 *  and includes the styles, if any, from the Styles class
	 *  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	 */
function makeHead() {
		header( 'Content-type: text/html; charset=utf-8' );
	if (func_num_args() > 1 || !is_array(func_get_arg(0)))
	{
		$param = array();
		$param['page_title'] = func_get_arg(0);
		if (func_num_args() > 1) {
			$param['body_id'] = func_get_arg(1);
		}
		if (func_num_args() > 2) {
			$param['body_class'] = func_get_arg(2);
		}
		if (func_num_args() > 3) {
			$param['page_description'] = func_get_arg(3);
		}
		if (func_num_args() > 4) {
			$param['page_pretitle'] = func_get_arg(4);
		}
		if (func_num_args() > 5) {
			$param['page_posttitle'] = func_get_arg(5);
		}
	} else {
		$param = func_get_arg(0);
	}
//	echo 'makehead():'. print_r($param, true);
	$head = new Head();
//	$head->load();
	if (isset( $param['page_pretitle'] )) {		$head->pagePreTitle = $param['page_pretitle'];			}
	//if (isset( $param['page_title'] )) {		$head->set_pageTitle( $param['page_title'] );			}
	if (isset( $param['page_title'] )) {		$head->pageTitle = $param['page_title'];				}
	if (isset( $param['page_posttitle'] )) {	$head->pagePostTitle = $param['page_posttitle'];		}
	if (isset( $param['page_description'] )) {	$head->pageDescription = $param['page_description'];	}
	if (isset( $param['body_id'] )) {			$head->bodyId = $param['body_id'];						}
	if (isset( $param['body_class'] )) {		$head->bodyClass = $param['body_class'];				}
//	if (isset( $param['article_tag'] )) {		$head->articleTag = $param['article_tag'];				}
	return $head->out();
}
