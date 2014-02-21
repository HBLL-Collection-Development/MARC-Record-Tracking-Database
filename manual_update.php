<?php
require_once 'config.php';
require_once 'lib/xmlreader-iterators.php'; // https://gist.github.com/hakre/5147685

ini_set("memory_limit","12000M");
ini_set('max_execution_time', 500);


// If the start_id variable is not set, request that the user set it
if(!$_REQUEST['start_id']) {
  $html = <<<HTML
    <h1>Single Resource</h1>
    <form action="" method="get">
      <label for="start_id">ID of resource to update: </label><input type="text" name="start_id" id="start_id">
      <p><input type="submit" value="Continue &rarr;"></p>
    </form>
    <h1>Range of Resources</h1>
    <form action="" method="get">
      <label for="start_id">ID of first resource to update: </label><input type="text" name="start_id" id="start_id"><br/>
      <label for="end_id">ID of last resource to update: </label><input type="text" name="end_id" id="end_id">
      <p><input type="submit" value="Continue &rarr;"></p>
    </form>
HTML;
  $html = array('title' => 'Manual Update', 'html' => $html);
  template::display('generic.tmpl', $html, 'Manual Update');
  die();
// The start_id is the min value used in the loop that follows
} else {
  $min = (int) $_REQUEST['start_id'];
}

// If there is no end_id variable, set the max to be the min so that only one resource is processed
if(!$_REQUEST['end_id']) {
  $max = $min;
  // Otherwise, begin running through all files beginning with start_id and ending with end_id
} else {
  $max = (int) $_REQUEST['end_id'];
}

$i = $min;

while($i <= $max) {
  echo $i . '<br/>';
  $adjust_xml = new adjust_xml();
  $add_vendor = $adjust_xml->add_vendor($i);
  $update_database = update_database($i);
  $i++;
}

function update_database($resource_id) {
  $today = date('Y-m-d');
  $resource = get_resource($resource_id);
  $frequency = $resource[0]['frequency'];
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
  $sql = 'UPDATE records SET last_load = :today, next_load = :next_load, file_exists = "Y" WHERE id = :resource_id';
  $query = $db->prepare($sql);
  $query->bindParam(':today', $today);
  $query->bindParam(':next_load', $next_load);
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
}

function get_resource($resource_id) {
  $database = new db;
  $db = $database->connect();
  $sql = 'SELECT r.id, r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.last_updated, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.id = :resource_id';
  $query = $db->prepare($sql);
  $query->bindParam(':resource_id', $resource_id);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_ASSOC);
  $db = null;
  return $results;
}
?>
