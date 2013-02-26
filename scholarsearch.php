<?php
require_once 'config.php';

$database = new db;
$db = $database->connect();
$sql = 'SELECT r.id, r.resource_name, r.last_load, r.num_records, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE load_records = "Y" ORDER BY vendor_name, resource_name';
$query = $db->prepare($sql);
$query->execute();
$marc_record_loads = $query->fetchAll(PDO::FETCH_ASSOC);
$db = null;

$records = array();

foreach($marc_record_loads as $resource) {
  $last_load = $resource['last_load'];
  $vendor_name = $resource['vendor_name'];
  $resource_name = $resource['resource_name'];
  $resource_id = $resource['id'];
  $num_records = $resource['num_records'];
  
  $name = $vendor_name . '-' . $resource_name;
  $file = config::URL . '/records/' . $resource_id . '.xml';
  $records[$file] = array('last_updated' => $last_load, 'name' => $name, 'num_records' => $num_records);
}

echo json_encode($records);

?>
