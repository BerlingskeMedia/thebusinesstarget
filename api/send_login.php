<?php
  //Get ID by email
  //Send email with ekstern ID
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $mail = $request->mail;
  $oneshot_url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=6ff47cb3441841ac37fd1bca752d6bff&command=cmd_send_oneshot&email=%s&mail_data_id=3107&mail_id=13367";
  $oneshot_url = sprintf($oneshot_url_format, $mail);
  file_get_contents($oneshot_url);
  echo (json_encode(""));
?>
