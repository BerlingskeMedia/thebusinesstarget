<?php

  include 'baseline.php';


  $user_put = $config['BASEURL'] . '/users/' . $request->ekstern_id;
  $request->location_id = $config['LOCATION_ID'];
  array_push($request->nyhedsbreve, $config['TBT_NID']);
  $response = Requests::put($user_put, $headers, json_encode($request));
  echo $response->body;

?>
