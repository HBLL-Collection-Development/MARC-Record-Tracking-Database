<?php
/**
  * Creates and sends an email outlining upcoming (next 2 weeks) and past-due tasks
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-04-25
  * @since 2013-04-23
  *
  */
  
require_once 'config.php';

// Log that this page was accessed.
$date = date('r') . "\n";
$fp = fopen('email_log', 'a');
fwrite($fp, $date);
fclose($fp);

// Send email
$email = new email;
$sent  = $email->send();
if($sent) {
  echo '<p>Email successfully sent.</p>';
} else {
  echo '<p>There was a problem sending the email. Please try again.</p>';
}
?>