<?php

  $config = include_once("config.php");
  require_once 'Requests/library/Requests.php';
  Requests::register_autoloader();
  $profile_page_link_url = $config['BASEURL'] . '/doubleopt';
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $request->location_id = $config['LOCATION_ID'];
  $request->nyhedsbreve = array($config['TBT_NID']);

  $headers = array('Content-Type' => 'application/json');
  $response = Requests::post($profile_page_link_url, $headers, json_encode($request));

  echo $response->body;

?>
