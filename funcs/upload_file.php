<?php
namespace Yuma;
//https://stackoverflow.com/questions/6974684/how-to-send-formdata-objects-with-ajax-requests-in-jquery
function upload_file() {
	foreach($_FILES as $key) {
		$name = time().$key['name'];
		$path = 'upload/'.$name;
		@move_uploaded_file($key['tmp_name'], $path);
	}
}
