<?php

  include 'baseline.php';

  $url = $config['BASEURL'] . '/doubleopt/' . $request->double_opt_key . '/confirm';

  $headers = array('Content-Type' => 'application/json');
  $response = Requests::get($url);

  echo $response->body;

?>
