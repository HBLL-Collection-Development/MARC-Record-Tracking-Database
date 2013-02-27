<?php
require_once 'config.php';
$date = date('Y-m-d', strtotime('+2 weeks'));
$database = new db;
$db = $database->connect();
$sql = 'SELECT r.resource_name, r.next_load, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.next_load < :date ORDER BY r.next_load ASC, vendor_name, resource_name';
$query = $db->prepare($sql);
$query->bindParam(':date', $date);
$query->execute();
$resources = $query->fetchAll(PDO::FETCH_ASSOC);
$db = null;
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
  echo $resource_list;
  die();
  ob_start();
  $to      = config::NOTIFY_EMAILS;
  $subject = date('D, M d, Y') . ': MARC Records to Load';
  $message = 'The following resources need to be loaded soon:' . "\r\n\r\n" . $resource_list;
  $headers = 'From: ' . config::FROM_EMAIL . "\r\n" .
      'Reply-To: ' . config::FROM_EMAIL . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
  mail( $to, $subject, $message, $headers );
} else {
  echo 'Congratulations! No resources need to be loaded in the next 2 weeks.';
}
?>