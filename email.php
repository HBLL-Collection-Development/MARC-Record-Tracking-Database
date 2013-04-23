<?php
/**
  * Creates and sends an email outlining upcoming (next 2 weeks) and past-due tasks
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-04-23
  * @since 2013-04-23
  *
  */
  
require_once 'config.php';
$email = new email;
$email->send_email();
?>