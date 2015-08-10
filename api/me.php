<?php
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  #echo $postdata;
  $my_id = $request->id;
  $url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&command=cmd_berlingske_user_profile_get&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=b5f9a4e0d6b55465ba48ff8c0f236c0e&xsl_id=1004&user_id=%s";
  $url = sprintf($url_format, $my_id);

  $content = file_get_contents($url);
  $json = json_decode($content, true);
  $data = $json['root']['data'];

  $response = new stdClass();
  $response->interests = $data['interesser'];
  $response->mail = $data['email'];
  $response->first_name = $data['fornavn'];
  $response->last_name = $data['efternavn'];
  $response->zip = intval($data['postnummer']);
  $response->nyhedsbreve = $data['nyhedsbreve'];

  echo (json_encode($response));
?>
