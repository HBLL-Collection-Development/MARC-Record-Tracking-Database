<?php
/**
  * Compress and download all files
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-03-05
  * @since 2013-03-04
  *
  */
require_once 'config.php';
require_once 'lib/Archive_Tar/Archive/Tar.php';

$database = new db;
$db = $database->connect();
$sql = 'SELECT id, load_records, file_exists FROM records WHERE load_records = "Y" AND file_exists = "Y"';
$query = $db->prepare($sql);
$query->execute();
$marc_record_loads = $query->fetchAll(PDO::FETCH_ASSOC);
$db = null;

$tar   = new Archive_Tar(config::UPLOAD_DIRECTORY . '/All.tar.gz', 'gz');
$files = array();
foreach($marc_record_loads as $resource) {
  $resource_id = $resource['id'];
  $filename    = config::UPLOAD_DIRECTORY . '/' . $resource_id . '.xml';
  $files[] = $filename;
}

$compressed_tar = $tar->create($files) or die('Could not create archive. Please go back and try again.');

if(rename(config::UPLOAD_DIRECTORY . '/All.tar.gz', 'download_all/All.tar.gz')){
  download('download_all/All.tar.gz', 'All.tar.gz'); 
}

function download($f_location, $f_name){
  header('Content-Description: File Transfer');
  header('Content-Type: application/x-tar');
  header('Content-Disposition: attachment; filename=' . basename($f_name));
  readfile($f_location);
}

?>
