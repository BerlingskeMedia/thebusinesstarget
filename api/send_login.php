<?php
  //Get ID by email
  //Send email with ekstern ID
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  $url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&command=cmd_berlingske_user_ekstern_id_by_email&xsl_id=&xml=&stack_key=6ee998984e476705e4bb8973540463e5&customer_key=a40e4703a823d1d11977928620c6ead0&email=%s&xsl_id=1004";

  $mail = $request->mail;
  $url = sprintf($url_format, $mail);

  $content = file_get_contents($url);
  $json = json_decode($content, true);

  $ekstern_id = $json['root']['data']['ekstern_id'];

  $url_format = "http://apps.adlifter.com/plugin/?application_key=70c4dd8af56f5dad9d5483513f049eb6&command=cmd_berlingske_user_profile_get&customer_key=a40e4703a823d1d11977928620c6ead0&stack_key=b5f9a4e0d6b55465ba48ff8c0f236c0e&xsl_id=1004&user_id=%s";
  $url = sprintf($url_format, $ekstern_id);

  $content = file_get_contents($url);
  $json_me = json_decode($content, true);
  $data_me = $json_me['root']['data'];

  $first_name = $data_me['fornavn'];
  $last_name = $data_me['efternavn'];
  $full_name = $first_name . ' ' . $last_name;

  #$message = sprintf($message_format, $ekstern_id);
  #mail($mail, "Login til The Business Target", $ekstern_id);

  require_once 'mandrill-api-php/src/Mandrill.php'; //Not required with Composer
  $mandrill = new Mandrill('YOUR_API_KEY');
  $template_name = "send-login";
  $template_content = array();
  $message = array(
      'to' => array(
        array(
          'email' => $mail,
          'name' => $full_name,
          'type' => 'to'
          )
        ),
      'merge_language' => 'handlebars',
      'global_merge_vars' => array(
            array(
                'name' => 'first_name',
                'content' => $first_name,
            ),
            array(
                'name' => 'last_name',
                'content' => $last_name,
            ),
            array(
                'name' => 'id',
                'content' => $ekstern_id
            )
        ),
      );
  // $message = array(
  //       'to' => array(
  //           array(
  //               'email' => 'mgmano@gmail.com',
  //               'name' => 'Marcus Bjerg Gregersen',
  //               'type' => 'to',
  //           ),
  //       ),
  //     ),
  // );

  $async = false;
  $ip_pool = 'Main Pool';
  $send_at = null;

  $retval = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool, $send_at);






?>
