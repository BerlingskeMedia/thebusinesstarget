<?php
  $url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&command=cmd_berlingske_quicksignup&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=7b5a777540d92480c04dc79751632f34&xsl_id=1004&mid=%s&email=%s&lid=%s&nlids=%s&intids=%s";


  $mid = 34;
  $nlids = 2;

  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $pass = $request->password;

  $email = $request->email;
  $lid = $request->lid;
  $intids = $request->intids;

  $url = sprintf($url_format, $mid, $email, $lid, $nlids, $intids);

  $content = file_get_contents($url);
  $json = json_decode($content, true);
  $uid = $json['root']['data']['user_id'];

  $response = new stdClass();
  $response->uid = $uid;
  echo (json_encode($response));
?>
