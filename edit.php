<?php
require_once 'config.php';

$resource_id = $_REQUEST['id'];
$submit = $_REQUEST['submit'];

if(!$submit) {
  $database = new db;
  $db = $database->connect();
  $sql = 'SELECT r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.load_records, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.id = :resource_id';
  $query = $db->prepare($sql);
  $query->bindParam(':resource_id', $resource_id);
  $query->execute();
  $resource = $query->fetchAll(PDO::FETCH_ASSOC);
  $db = null;

  $resource_name     = $resource[0]['resource_name'];
  $url               = $resource[0]['url'];
  $username          = $resource[0]['username'];
  $password          = $resource[0]['password'];
  $frequency         = $resource[0]['frequency'];
  $frequency_options = json_decode(config::FREQUENCY);
  $frequency_form    = NULL;
  foreach($frequency_options as $option) {
    if($option == $frequency) {
      $frequency_form .= '<option selected="selected">' . $option . '</option>';
    } else {
      $frequency_form .= '<option>' . $option . '</option>';
    }
  }
  if($last_load != '') {
    $last_load   = date('F j, Y', strtotime($resource[0]['last_load']));
  } else {
    $last_load   = '[unknown]';
  }
  $next_load     = $resource[0]['next_load'];
  $num_records   = number_format($resource[0]['num_records']);
  $notes         = $resource[0]['notes'];
  $load_records  = $resource[0]['load_records'];
  if($load_records == 'Y') {
    $check_yes = ' checked="yes"';
    $check_no = '';
  } else {
    $check_yes = '';
    $check_no = ' checked="yes"';
  }
  $vendor_name   = $resource[0]['vendor_name'];
  $html = <<<HTML
  <h1>$resource_name</h1>
  <h2>$vendor_name</h2>
  <h2>Last loaded <em>$last_load</em></h2>
  <form action="" method="get" accept-charset="utf-8">
    <input type="hidden" name="resource_id" value="$resource_id" />
    <p>
      <label for="load_records">Load records:</label>
      <input type="radio" name="load_records" id="Y" value="Y"$check_yes><label for="Y">Yes</label><br/><br/>
      <input type="radio" name="load_records" id="N" value="N"$check_no><label for="N">No</label>
    </p>
    <p><label for="url">URL:</label><br/><input type="text" name="url" value="$url" id="url"></p>
    <p><label for="username">Username:</label><br/><input type="text" name="username" value="$username" id="username"></p>
    <p><label for="password">Password:</label><br/><input type="text" name="password" value="$password" id="password"></p>
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
  $update = new update;
  $update_database = $update->records_table($_REQUEST);
  if($update_database === TRUE) {
    $html = '<p>The database was successfully updated. <a href="' . config::URL . '">Review other resources.</a></p>';
  } else {
    $html = '<p>The database was not successfully updated. Please go back and try again.</p>';
  }
}

$html = array('title' => 'Edit', 'html' => $html);
template::display('generic.tmpl', $html, 'Edit');
?>