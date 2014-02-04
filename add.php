<?php
require_once 'config.php';

// TODO: Convert to OOP
$vendor_id = $_REQUEST['vendor_id'];

if(is_null($vendor_id)) {
  $database = new db;
  $db = $database->connect();
  $sql = 'SELECT id AS vendor_id, name AS vendor_name FROM vendors ORDER BY vendor_name';
  $query = $db->prepare($sql);
  $query->execute();
  $vendors = $query->fetchAll(PDO::FETCH_ASSOC);
  $db = null;

  $vendor_list = NULL;
  foreach($vendors as $vendor){
    $vendor_id   = $vendor['vendor_id'];
    $vendor_name = $vendor['vendor_name'];
    $vendor_list .= '<option value="' . $vendor_id . '">' . $vendor_name . '</option>';
  }
  
  $frequency_options = json_decode(config::FREQUENCY);
  $frequency_form = NULL;
  foreach($frequency_options as $option) {
      $frequency_form .= '<option>' . $option . '</option>';
  }

  $html = <<<HTML
  <form action="#" method="get" accept-charset="utf-8">
    <p>
      <label for="vendor">Vendor:</label><br/>
      <select name="vendor_id" id="vendor_id">
        <option value="" selected="selected">Select a vendor&hellip;</option>
        $vendor_list
      </select><br/>
      <label for="vendor_name">Or add a new vendor:</label><br/><input type="text" name="vendor_name" id="vendor_name">
    </p>
    <p><label for="resource_name">Resource name:</label><br/><input type="text" name="resource_name" id="resource_name"></p>
    <p style="line-height: 1.5em;">
      <label for="primo_central">In Primo Central:</label><br/>
      <input type="radio" name="primo_central" id="Y-pc" value="Y"><label for="Y-pc">Yes</label><br/><br/>
      <input type="radio" name="primo_central" id="N-pc" value="N"><label for="N-pc">No</label>
    </p>
    <p style="line-height: 1.5em;">
      <label for="load_records">Load records:</label><br/>
      <input type="radio" name="load_records" id="Y" value="Y"><label for="Y">Yes</label><br/><br/>
      <input type="radio" name="load_records" id="N" value="N"><label for="N">No</label>
    </p>
    <p><label for="url">URL:</label><br/><input type="text" name="url" value="$url" id="url"></p>
    <p><label for="username">Username:</label><br/><input type="text" name="username" id="username"></p>
    <p><label for="password">Password:</label><br/><input type="text" name="password" id="password"></p>
    <p>
      <label for="frequency">Frequency:</label><br/>
      <select name="frequency" id="frequency">
        $frequency_form
      </select>
    </p>
    <p><label for="num_records"># Records (numbers only):</label><br/><input type="text" name="num_records" value="$num_records" id="num_records"></p>
    <p><label for="next_load">Next Load (yyyy-mm-dd):</label><br/><input type="text" name="next_load" value="$next_load" id="next_load"></p>
    <p><label for="notes">Notes:</label><br/><textarea name="notes" id="notes">$notes</textarea></p>
    <p><input type="submit" name="submit" id="submit" value="submit"></p>
  </form>
HTML;
} else {
  // Create a new vendor if this is the first resource we have purchased from a vendor
  $vendor_name = trim($_REQUEST['vendor_name']);
  if($vendor_id == '') {
    $database = new db;
    $db = $database->connect();
    $sql = 'INSERT INTO vendors (name) VALUES (:vendor_name)';
    $query = $db->prepare($sql);
    $query->bindParam(':vendor_name', $vendor_name);
    $query->execute();
    // ID of new vendor
    $vendor_id = $db->lastInsertId();
    $db = null;
  } else {
    $vendor_id = trim($_REQUEST['vendor_id']);
  }
  // Clean data
  $resource_name = trim($_REQUEST['resource_name']);
  $url           = trim($_REQUEST['url']);
  $username      = trim($_REQUEST['username']);
  $password      = trim($_REQUEST['password']);
  $frequency     = trim($_REQUEST['frequency']);
  if($_REQUEST['next_load'] != '') {
    $next_load   = date('Y-m-d', strtotime($_REQUEST['next_load']));
  } else {
    $next_load   = NULL;
  }
  $num_records   = trim(str_replace(',', '', $_REQUEST['num_records']));
  $notes         = trim($_REQUEST['notes']);
  $primo_central = trim($_REQUEST['primo_central']);
  $load_records  = trim($_REQUEST['load_records']);
  $database = new db;
  $db = $database->connect();
  $sql = 'INSERT INTO records (vendor_id, resource_name, url, username, password, frequency, next_load, num_records, notes, primo_central, load_records) VALUES (:vendor_id, :resource_name, :url, :username, :password, :frequency, :next_load, :num_records, :notes, :primo_central, :load_records)';
  $query = $db->prepare($sql);
  $query->bindParam(':vendor_id', $vendor_id);
  $query->bindParam(':resource_name', $resource_name);
  $query->bindParam(':url', $url);
  $query->bindParam(':username', $username);
  $query->bindParam(':password', $password);
  $query->bindParam(':frequency', $frequency);
  $query->bindParam(':next_load', $next_load);
  $query->bindParam(':num_records', $num_records);
  $query->bindParam(':notes', $notes);
  $query->bindParam(':primo_central', $primo_central);
  $query->bindParam(':load_records', $load_records);
  $status = $query->execute();
  $db = null;
  if($status === TRUE) {
    $html = 'Database successfully updated. <a href="' . config::URL . '">Return home</a>.';
  } else {
    $html = 'Database was not successfully updated. Please go back and try again.';
  }
}

$html = array('title' => 'Add a Resource', 'html' => $html);
template::display('generic.tmpl', $html);
?>