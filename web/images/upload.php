<?php
  $filename=uniqid('f_').'.'.$_GET['filetype'];
  $fileData=file_get_contents('php://input');
  if (!file_exists('pawLostFound')) {
    mkdir('pawLostFound', 0777, true);
  }
  $fhandle=fopen("pawLostFound/".$filename, 'wb');
  fwrite($fhandle, $fileData);
  fclose($fhandle);
  echo($filename);
?>