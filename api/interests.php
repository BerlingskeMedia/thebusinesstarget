<?php
  include 'baseline.php';
  $interest_url = $config['BASEURL'] . '/interesser/branches?displayTypeId=6';

  $response = Requests::get($interest_url);
  echo $response->body;
?>
