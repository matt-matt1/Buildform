<?php
header("Content-Type: application/json; charset=UTF-8");
//$data = filter_input( INPUT_POST, 'data', FILTER_SANITIZE_SPECIAL_CHARS );
//$data = filter_input_array( INPUT_POST );
//$data = array_merge( filter_input_array( INPUT_GET ), filter_input_array( INPUT_POST );
$data = array_merge( $_GET, $_POST );
$response = array( 'ok', $data );
echo json_encode($response);
exit();
