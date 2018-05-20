<?php
	$filename = $_GET["filename"];
	$path = "pawLostFound/".$filename;
	if (file_exists($path)){
		unlink($path);
		http_response_code(200);
	}
?>