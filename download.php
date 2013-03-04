<?php
/**
  * Compress and download all files
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-03-04
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

$tar   = new Archive_Tar(config::UPLOAD_DIRECTORY . '/ALL.tar.gz', 'gz');
$files = array();
foreach($marc_record_loads as $resource) {
  $resource_id = $resource['id'];
  $filename    = $resource_id . '.xml';
  $files[] = $filename;
}

// $files = array($filename);
$compressed_tar = $tar->create($files) or die('Could not create archive. Please go back and try again.');

?>
