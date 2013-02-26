<?php
/**
  * Class to import files into app
  *
  * @author  Jared Howland <marc.records@jaredhowland.com@gmail.com>
  * @version 2013-02-23
  */

class import {
    /**
    * Constructor. Takes a file and puts it into the database
    *
    * @access public
    * @param string $resource_id
    * @param array $file Array with all uploaded file information
    * @return boolean TRUE if upload successful; else logs errors
    */
    public function upload( $resource_id, $frequency, $file ) {
      $filename = $resource_id . '.xml';
      $tmp_name = $file['marc_records']['tmp_name'];
      if (move_uploaded_file($tmp_name, config::UPLOAD_DIRECTORY . '/' . $filename)) {
        $today = date('Y-m-d');
        switch ($frequency) {
          case 'Weekly':
            $next_load = date('Y-m-d', strtotime('+7 days', strtotime($today)));
            break;
          case 'Monthly':
            $next_load = date('Y-m-d', strtotime('+1 month', strtotime($today)));
            break;
          case 'Quarterly':
            $next_load = date('Y-m-d', strtotime('+3 months', strtotime($today)));
            break;
          case 'Semiannually':
            $next_load = date('Y-m-d', strtotime('+6 months', strtotime($today)));
            break;
          case 'Annually':
            $next_load = date('Y-m-d', strtotime('+1 year', strtotime($today)));
            break;
          case 'When notified':
            $next_load = null;
            break;
          default:
            $next_load = null;
            break;
        }
        $database = new db;
        $db = $database->connect();
        $sql = 'UPDATE records SET last_load = :today, next_load = :next_load WHERE id = :resource_id';
        $query = $db->prepare($sql);
        $query->bindParam(':today', $today);
        $query->bindParam(':next_load', $next_load);
        $query->bindParam(':resource_id', $resource_id);
        $query->execute();
        $db = null;
        return TRUE;
      } else {
        return FALSE;
      }
    }
}
?>