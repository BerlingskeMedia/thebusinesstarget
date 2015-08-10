<?php
  $url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&command=cmd_berlingske_quicksignup&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=7b5a777540d92480c04dc79751632f34&xsl_id=1004&mid=%s&email=%s&lid=%s&nlids=%s&intids=%s&postnummer=%d&fornavn=%s&efternavn=%s";


  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $pass = $request->password;

  $email = $request->email;

  $intids = $request->intids;
  $postnummer_dk = $request->zip;
  $fornavn = urlencode($request->first_name);
  $efternavn = urlencode($request->last_name);

  $mid = "34";
  $nlids = $request->nlids;

  if ($request->bem_permission) {
    // Berlingske Media Permission = 108
    $nlids = $nlids . ',108';
  }
  $lid = $request->lid;


  $url = sprintf($url_format, $mid, $email, $lid, $nlids, $intids, $postnummer_dk, $fornavn, $efternavn);

  $content = file_get_contents($url);
  $json = json_decode($content, true);
  $uid = $json['root']['data']['user_id'];

  $response = new stdClass();
  $response->uid = $uid;

  //Send oneshot mail
  $oneshot_url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=6ff47cb3441841ac37fd1bca752d6bff&command=cmd_send_oneshot&email=%s&mail_data_id=3107&mail_id=13205";
  $oneshot_url = sprintf($oneshot_url_format, $email);
  file_get_contents($oneshot_url);

  echo (json_encode($response));


?>
