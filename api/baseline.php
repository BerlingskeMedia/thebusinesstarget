<?php
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json');

  include 'Requests/library/Requests.php';
  Requests::register_autoloader();

  include 'config.php';
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $headers = array('Content-Type' => 'application/json');
?>
