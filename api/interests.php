<?php
  $config = include_once("config.php");
  $interest_url = $config['BASEURL'] . '/interesser/branches?displayTypeId=6';
  echo(file_get_contents($interest_url));
?>
