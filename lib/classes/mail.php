<?
class Mail {

  public static function prepare_and_send($rcpt, $mail_template, $vars) {
    include(CONFIG."mail_templates.php");
    
    $headers = "From: ".EMAIL_SENDER;
    if ($vars["html"]) {
      $headers .= "\nContent-Type: text/html; charset=UTF-8";
    }  
    $body = Mail::populate_variables(
              $mail_templates[$mail_template]["body"], 
              $vars
            );
    mail($rcpt,
         $mail_templates[$mail_template]["subject"], 
         $body,
         $headers);
    return $body;     
  } // end of prepare_and_send()

  public static function populate_variables($body, $vars) {
    // start over from the beginning every time
    while ($start = strpos($body, "##", 0)) {
      $end = strpos($body, "\##", $start);
      $varname = substr($body, $start + 2, $end - ($start + 2));
      if (array_key_exists($varname, $vars)) {
        $body = substr_replace($body, $vars[$varname], 
                               $start, ($end + 3) - $start);
      }
      else { // exit in order to avoid an eternal loop
        break;
      }  
    }  
    return $body;
  } // end of populate_variables()

} // end of Mail
