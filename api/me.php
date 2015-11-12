<?php
  $config = include_once("config.php");



  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $user_url = $config['BASEURL'] . '/users/' . $request->ekstern_id;
  echo file_get_contents($user_url);
  
?>
