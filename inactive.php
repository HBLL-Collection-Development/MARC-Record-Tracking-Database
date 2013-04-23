<?php
require_once 'config.php';

$database = new db;
$db = $database->connect();
$sql = 'SELECT r.id, r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.last_updated, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE load_records = "N" ORDER BY r.next_load ASC, vendor_name, resource_name';
$query = $db->prepare($sql);
$query->execute();
$marc_record_loads = $query->fetchAll(PDO::FETCH_ASSOC);
$db = null;

$html = array('title' => 'Inactive', 'results' => $marc_record_loads);
template::display('html.tmpl', $html);
?>