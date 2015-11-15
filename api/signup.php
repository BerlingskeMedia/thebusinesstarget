<?php

  include 'baseline.php';

  $profile_page_link_url = $config['BASEURL'] . '/doubleopt';
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $request->location_id = $config['LOCATION_ID'];
  $request->nyhedsbreve = array($config['TBT_NID']);

  $response = Requests::post($profile_page_link_url, $headers, json_encode($request));

  echo $response->body;

?>
