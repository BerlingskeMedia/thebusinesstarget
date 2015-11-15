<?php
  include 'baseline.php';

  $profile_page_link_url = $config['BASEURL'] . '/mails/profile-page-link';
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $mail = $request->mail;
  $payload = new stdClass();
  $payload->publisher_id = $config['TBT_PUBLISHER_ID'];
  $payload->email = $mail;
  $response = Requests::post($profile_page_link_url, $headers, json_encode($payload));
  echo $response->body;

?>
