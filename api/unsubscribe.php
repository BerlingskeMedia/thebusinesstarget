<?php
  $config = include_once("config.php");
  require_once 'Requests/library/Requests.php';
  Requests::register_autoloader();

  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $newsletter_delete = $config['BASEURL'] . '/users/' . $request->ekstern_id . '/nyhedsbreve/' . $config['TBT_NID'] . '?location_id=' . $config['LOCATION_ID'];
  $headers = array('Content-Type' => 'application/json');
  $response = Requests::delete($newsletter_delete, $headers, null);
  echo $response->body;
?>
