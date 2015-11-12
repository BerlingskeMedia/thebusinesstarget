<?php

  $config = include_once("config.php");
  require_once 'Requests/library/Requests.php';
  Requests::register_autoloader();

  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $user_put = $config['BASEURL'] . '/users/' . $request->ekstern_id;
  $request->location_id = $config['LOCATION_ID'];
  array_push($request->nyhedsbreve, $config['TBT_NID']);
  $headers = array('Content-Type' => 'application/json');
  $response = Requests::put($user_put, $headers, json_encode($request));
  echo $response->body;

?>
