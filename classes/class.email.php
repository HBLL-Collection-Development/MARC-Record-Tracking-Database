<?php
/**
  * Class to create and send an email outlining upcoming (next 2 weeks) and past-due loads
  *
  * @author  Jared Howland <marc.records@jaredhowland.com@gmail.com>
  * @version 2013-04-23
  * @since 2013-04-23
  */

class email {
    /**
    * Constructor. Displays information from the database
    *
    * @access public
    * @param null
    * @return boolean, string TRUE/FALSE status of emailing, string with message if no resources need to be loaded in the next 2 weeks
    */
    public function send_email() {
      $resources = $this->get_resources();
      return $this->get_email($resources);
    }
    
    /**
    * Gets list of all resources that need to be loaded in the next 2 weeks
    *
    * @access public
    * @param null
    * @return array Array with data about resources
    */
    private function get_resources() {
      $date = date('Y-m-d', strtotime('+2 weeks'));
      $database = new db;
      $db = $database->connect();
      $sql = 'SELECT r.resource_name, r.next_load, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.next_load < :date ORDER BY r.next_load ASC, vendor_name, resource_name';
      $query = $db->prepare($sql);
      $query->bindParam(':date', $date);
      $query->execute();
      $resources = $query->fetchAll(PDO::FETCH_ASSOC);
      $db = null;
      return $resources;
    }
    
    /**
    * Emails list of resources that need to be loaded in the next 2 weeks to appropriate parties (as defined in config.php)
    *
    * @access public
    * @param array Array with resources that need to be loaded in the next 2 weeks
    * @return boolean, string TRUE/FALSE status of emailing, string with message if no resources need to be loaded in the next 2 weeks
    */
    private function get_email($resources) {
      $count = count($resources);
      // Only send email if MARC records need to be loaded in the next 2 weeks
      if($count > 0) {
        $resource_list = NULL;
        foreach($resources as $resource) {
          $resource_name = $resource['resource_name'];
          $next_load     = $resource['next_load'];
          $vendor_name   = $resource['vendor_name'];
          $resource_list .= $next_load . ': ' . $vendor_name . '-' . $resource_name . "\r\n";
        }
        $to      = config::NOTIFY_EMAILS;
        $subject = 'MARC Records to Load for ' . date('D, M d, Y');
        $message = 'The following resources need to be loaded soon:' . "\r\n\r\n" . $resource_list;
        $headers = 'From: ' . config::FROM_EMAIL . "\r\n" .
            'Reply-To: ' . config::FROM_EMAIL . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        ob_start();
        $mail = mail( $to, $subject, $message, $headers );
        return $mail;
      } else {
        return 'Congratulations! No resources need to be loaded in the next 2 weeks.';
      }
    }
}
?>
