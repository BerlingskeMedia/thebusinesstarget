<?php
  include 'baseline.php';

  $add_interest_url = $config['BASEURL'] . '/doubleopt/' . $request->doubleoptkey . '/interesser';

  $payload = new stdClass();
  $payload->location_id = $config['LOCATION_ID'];
  $payload->interesser = $request->interesser;

  $response = Requests::post($add_interest_url, $headers, json_encode($payload));

  echo $response->body;
?>
