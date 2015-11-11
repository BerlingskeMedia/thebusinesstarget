<?php
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  //$url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&command=cmd_berlingske_user_profile_set&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=30f3acff061bdc073a16a7d02d2c7d90&&user_id=%s&nyhedsbreve=%s&lid=1732";

  $url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=981afeccbb2e4d48c5eda7c55c4d0b63&command=cmd_berlingske_quicksignup&email=%s&nlids=844&lid=1732&mid=51&action=3";

  //$id = $request->id;
  //$nlid = $request->nlid;

  $url = sprintf($url_format, $request->email);

  file_get_contents($url);

  //Send oneshot mail
  $oneshot_url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=6ff47cb3441841ac37fd1bca752d6bff&command=cmd_send_oneshot&email=%s&mail_data_id=3107&mail_id=13307";
  $oneshot_url = sprintf($oneshot_url_format, $request->email);

  file_get_contents($oneshot_url);

  echo "";
?>
