<?php
  $config = include_once("config.php");
  require_once 'Requests/library/Requests.php';
  Requests::register_autoloader();
  $profile_page_link_url = $config['BASEURL'] . '/mails/profile-page-link';
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $mail = $request->mail;
  $payload = new stdClass();
  $payload->publisher_id = 51;
  $payload->email = $mail;
  $headers = array('Content-Type' => 'application/json');
  $response = Requests::post($profile_page_link_url, $headers, json_encode($payload));
  echo $response->body;

?>
