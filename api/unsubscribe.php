<?php
  include 'baseline.php';

  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $newsletter_delete = $config['BASEURL'] . '/users/' . $request->ekstern_id . '/nyhedsbreve/' . $config['TBT_NID'] . '?location_id=' . $config['LOCATION_ID'];
  $response = Requests::delete($newsletter_delete, $headers, null);
  echo $response->body;
?>
