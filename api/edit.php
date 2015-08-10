<?php

  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&command=cmd_berlingske_user_profile_set&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=30f3acff061bdc073a16a7d02d2c7d90&&user_id=%s&fornavn=%s&efternavn=%s&postnummer=%s&interesser=%s";

  $user_id = $request->user_id;
  $first_name = urlencode($request->first_name);
  $last_name = urlencode($request->last_name);
  $zip = $request->zip;
  $ints = $request->ints;



  $url = sprintf($url_format, $user_id, $first_name, $last_name, $zip, $ints);
  file_get_contents($url);
?>
