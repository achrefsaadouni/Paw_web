<?php
	$filename = $_GET["filename"];
	$path = "images/".$filename;
	if (file_exists($path)){
		unlink($path);
		http_response_code(200);
	}
?>