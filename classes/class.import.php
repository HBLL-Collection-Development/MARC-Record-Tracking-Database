<?php
/**
  * Class to import files into app
  *
  * @author  Jared Howland <marc.records@jaredhowland.com@gmail.com>
  * @version 2013-02-23
  */
  
require_once 'lib/Archive_Tar/Archive/Tar.php';

class import {
    /**
    * Constructor. Takes a file and puts it into the database
    *
    * @access public
    * @param string $resource_id
    * @param array $file Array with all uploaded file information
    * @return boolean TRUE if upload successful; else logs errors
    */
    public function upload( $resource_id, $frequency, $num_records, $file ) {
      $filename = $resource_id . '.xml';
      $tmp_name = $file['marc_records']['tmp_name'];
      if(move_uploaded_file($tmp_name, config::UPLOAD_DIRECTORY . '/' . $filename)) {
        $tar = new Archive_Tar(config::UPLOAD_DIRECTORY . '/' . $filename . '.tar.gz', 'gz');
        $files = array($filename);
        $compressed_tar = $tar->create($files) or die('Could not create archive. Please go back and try again.');
        $today = date('Y-m-d');
        switch ($frequency) {
          case 'Once':
            $next_load = null;
            break;
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
        $sql = 'UPDATE records SET last_load = :today, next_load = :next_load, num_records = :num_records, file_exists = "Y" WHERE id = :resource_id';
        $query = $db->prepare($sql);
        $query->bindParam(':today', $today);
        $query->bindParam(':next_load', $next_load);
        $query->bindParam(':num_records', $num_records);
        $query->bindParam(':resource_id', $resource_id);
        $result = $query->execute();
        $db = null;
        // If database load is successful return true
        if($result === TRUE) {
          return TRUE;
        // Else return false
        } else {
          return FALSE;
        }
      // If file cannot be created, return false
      } else {
        return FALSE;
      }
    }
}
?>