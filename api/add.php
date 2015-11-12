<?php
  $config = include_once("config.php");
  require_once 'Requests/library/Requests.php';
  Requests::register_autoloader();
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $add_interest_url = $config['BASEURL'] . '/doubleopt/' . $request->doubleoptkey . '/interesser';

  $payload = new stdClass();
  $payload->location_id = $config['LOCATION_ID'];
  $payload->interesser = $request->interesser;


  $headers = array('Content-Type' => 'application/json');
  $response = Requests::post($add_interest_url, $headers, json_encode($payload));

  echo $request;
?>
