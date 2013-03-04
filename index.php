<?php
require_once 'config.php';

$database = new db;
$db = $database->connect();
$sql = 'SELECT r.id, r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.last_updated, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE load_records = "Y" ORDER BY r.next_load ASC, vendor_name, resource_name';
$query = $db->prepare($sql);
$query->execute();
$marc_record_loads = $query->fetchAll(PDO::FETCH_ASSOC);
$db = null;

$num_resources            = count($marc_record_loads);
$num_up_to_date_resources = 0;
$num_records              = 0;

foreach($marc_record_loads as $resource) {
  $num_records += $resource['num_records'];
  // To count as being up to date, must have a file associated with it, and have the next load date in the future
  if($resource['file_exists'] == 'Y' && (strtotime($resource['next_load']) > strtotime(date('Y-m-d')) || is_null($resource['next_load']))) {
	$num_up_to_date_resources++;
  }
}
$percent_complete = round(($num_up_to_date_resources / $num_resources) * 100) . '%';

$stats = array('num_records' => $num_records, 'num_resources' => $num_resources, 'num_up_to_date_resources' => $num_up_to_date_resources, 'percent_complete' => $percent_complete);

$html = array('title' => 'Home', 'stats' => $stats, 'results' => $marc_record_loads);
template::display('html.tmpl', $html);
?>