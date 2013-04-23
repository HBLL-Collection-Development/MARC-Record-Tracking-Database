<?php
/**
  * Class to download all MARC record files
  *
  * @author  Jared Howland <marc.records@jaredhowland.com@gmail.com>
  * @version 2013-04-23
  * @since 2013-04-23
  */
  
require_once 'lib/Archive_Tar/Archive/Tar.php';
  
class download {
    /**
    * Constructor. Compresses all MARC records files and presents them to user for download
    *
    * @access public
    * @param null
    * @return boolean TRUE if files compressed successfully
    */
    public function download_file() {
      set_time_limit(300);
      $marc_files       = $this->get_all();
      $compressed_files = $this->compress($marc_files);
      $this->download_all();
    }
    
    /**
    * Gets list of all files to compress and download
    *
    * @access public
    * @param null
    * @return array Array with id, load_records (Y or N), file_exists (Y or N)
    */
    private function get_all() {
      $database = new db;
      $db = $database->connect();
      $sql = 'SELECT id, load_records, file_exists FROM records WHERE load_records = "Y" AND file_exists = "Y"';
      $query = $db->prepare($sql);
      $query->execute();
      $marc_record_loads = $query->fetchAll(PDO::FETCH_ASSOC);
      $db = null;
      return $marc_record_loads;
    }
    
    /**
    * Compresses all the active MARC files
    *
    * @access public
    * @param array Array with id, load_records (Y or N), file_exists (Y or N) for all active MARC files
    * @return boolean TRUE if compressed file created successfully
    */
    private function compress($marc_files) {
      $tar   = new Archive_Tar(config::UPLOAD_DIRECTORY . '/All.tar.gz', 'gz');
      $files = array();
      foreach($marc_files as $resource) {
        $resource_id = $resource['id'];
        $filename    = config::UPLOAD_DIRECTORY . '/' . $resource_id . '.xml';
        $files[]     = $filename;
      }
      $tar->create($files) or die('Could not create archive. Please go back and try again.');
      return TRUE;
    }
    
    private function download_all() {
      if(rename(config::UPLOAD_DIRECTORY . '/All.tar.gz', 'download_all/All.tar.gz')){
        $this->present_download('download_all/All.tar.gz', 'All.tar.gz'); 
      }
    }

    private function present_download($f_location, $f_name){
      header('Content-Description: File Transfer');
      header('Content-Type: application/x-tar');
      header('Content-Disposition: attachment; filename=' . basename($f_name));
      readfile($f_location);
    }
}
?>