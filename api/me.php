<?php
  include 'baseline.php';

  $user_url = $config['BASEURL'] . '/users/' . $request->ekstern_id;

  $response = Requests::get($user_url);
  echo $response->body;

?>
