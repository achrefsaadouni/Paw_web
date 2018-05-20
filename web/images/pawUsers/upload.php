<?php
  $filename=uniqid('f_').'.'.$_GET['filetype'];
  $fileData=file_get_contents('php://input');
  if (!file_exists('pawUsers')) {
    mkdir('pawUsers', 0777, true);
  }
  $fhandle=fopen("pawUsers/".$filename, 'wb');
  fwrite($fhandle, $fileData);
  fclose($fhandle);
  echo($filename);
?>