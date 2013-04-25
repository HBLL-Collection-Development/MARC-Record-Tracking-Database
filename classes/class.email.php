<?php
/**
  * Class to create and send an email outlining upcoming (next 2 weeks) and past-due loads
  *
  * @author  Jared Howland <marc.records@jaredhowland.com@gmail.com>
  * @version 2013-04-25
  * @since 2013-04-23
  */
  
class email {
  /**
  * Constructor. Emails past due and upcoming records to load.
  *
  * @access public
  * @param null
  * @return boolean TRUE/FALSE status of email (sent/not sent)
  */
  public function send() {
    $message = $this->get_message();
    if($this->send_message($message)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
  
  /**
  * Emails list of resources that need to be loaded in the next 2 weeks to appropriate parties (as defined in config.php)
  *
  * @access public
  * @param string String containing the message to be emailed.
  * @return boolean TRUE/FALSE status of email (sent/not sent)
  */
  private function send_message($message) {
    $to      = config::NOTIFY_EMAILS;
    $subject = 'MARC Records to Load for ' . date('D, M d, Y');
    $headers = 'From: ' . config::FROM_EMAIL . "\r\n" . 'Reply-To: ' . config::FROM_EMAIL . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    ob_start();
    return mail( $to, $subject, $message, $headers );
  }
  
  /**
  * Create email message.
  *
  * @access public
  * @param null
  * @return string Message to be sent via email
  */
  private function get_message() {
    $past_resources   = $this->get_past_resources();
    $future_resources = $this->get_future_resources();
    $message = NULL;
    if($past_resources) {
      $message .= 'The following resources are past due:' . "\r\n\r\n" . $this->get_resource_list($past_resources) . "\r\n\r\n";
    }
    if($future_resources) {
      $message .= 'The following resources need to be loaded in the next two weeks:' . "\r\n\r\n" . $this->get_resource_list($future_resources);
    }
    if(!$past_resources && !$future_resources) {
      $message .= 'We are all up to date. There are no resources to load at this time.';
    }
    return $message;
  }
  
  /**
  * Format array as a list (string) for email message
  *
  * @access public
  * @param array Array containing about resources to email
  * @return string List of resources from array
  */
  private function get_resource_list($resources) {
    $resource_list = NULL;
    foreach($resources as $resource) {
      $resource_name = $resource['resource_name'];
      $next_load     = $resource['next_load'];
      $vendor_name   = $resource['vendor_name'];
      $resource_list .= $next_load . ': ' . $vendor_name . '-' . $resource_name . "\r\n";
    }
    return $resource_list;
  }

  /**
  * Returns array of all resources that are past due
  *
  * @access public
  * @param null
  * @return array, boolean Array with data about resources. Returns FALSE if query returns no results.
  */
  private function get_past_resources() {
    $date = date('Y-m-d');
    $database = new db;
    $db = $database->connect();
    $sql = 'SELECT r.resource_name, r.next_load, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.next_load < :date ORDER BY r.next_load ASC, vendor_name, resource_name';
    $query = $db->prepare($sql);
    $query->bindParam(':date', $date);
    $query->execute();
    $past_resources = $query->fetchAll(PDO::FETCH_ASSOC);
    $db = null;
    if(count($past_resources) > 0) {
      return $past_resources;
    } else {
      return FALSE;
    }
  }
  
  /**
  * Returns array of all resources that need to be loaded in the next 2 weeks
  *
  * @access public
  * @param null
  * @return array, boolean Array with data about resources. Returns FALSE if query returns no results.
  */
  private function get_future_resources() {
    $date = date('Y-m-d');
    $future_date = date('Y-m-d', strtotime('+2 weeks'));
    $database = new db;
    $db = $database->connect();
    $sql = 'SELECT r.resource_name, r.next_load, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.next_load between :date AND :future_date ORDER BY r.next_load ASC, vendor_name, resource_name';
    $query = $db->prepare($sql);
    $query->bindParam(':date', $date);
    $query->bindParam(':future_date', $future_date);
    $query->execute();
    $future_resources = $query->fetchAll(PDO::FETCH_ASSOC);
    $db = null;
    if(count($future_resources) > 0) {
      return $future_resources;
    } else {
      return FALSE;
    }
  }
}
?>
