<?php
/**
  * List of active records
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-02-26
  * @since 2013-02-26
  *
  */
require_once 'config.php';

// TODO: Convert to OOP

$format = $_REQUEST['format'];

$database = new db;
$db = $database->connect();
$sql = 'SELECT r.id, r.resource_name, r.last_load, r.num_records, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE load_records = "Y" ORDER BY vendor_name, resource_name';
$query = $db->prepare($sql);
$query->execute();
$marc_record_loads = $query->fetchAll(PDO::FETCH_ASSOC);
$db = null;

$records = array();

foreach($marc_record_loads as $resource) {
  $last_load     = $resource['last_load'];
  $vendor_name   = $resource['vendor_name'];
  $resource_name = $resource['resource_name'];
  $resource_id   = $resource['id'];
  $num_records   = $resource['num_records'];
  $file_exists   = $resource['file_exists'];
  $name          = $vendor_name . '-' . $resource_name;
  $filename      = config::UPLOAD_DIRECTORY . '/' . $resource_id . '.xml';
  // Only show if file actually exists
  if($file_exists == 'Y') {
    $file = config::URL . '/' . config::UPLOAD_DIRECTORY . '/' . $resource_id . '.xml';
    $records[$file] = array('last_updated' => $last_load, 'name' => $name, 'num_records' => $num_records);
  }
}

// JSON
if($format == 'json') {
  echo json_encode($records);
// HTML
} elseif($format == 'html') {
  $links = NULL;
  foreach($records as $url => $record) {
    $links .= '<li><a href="' . $url . '">' . $record['name'] . '</a></li>';
  }
  $html = <<<HTML
  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Scholarsearch Links</title>
    </head>
    <body>
      <ul>$links</ul>
    </body>
  </html>
HTML;
  echo $html;
// Error for incorrect format request
} else { die('Valid formats include "html" and "json". Please go back and try again.');}

?>
