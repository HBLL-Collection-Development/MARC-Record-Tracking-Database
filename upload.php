<?php
require_once 'config.php';

$resource_id = $_REQUEST['resource_id'];
$frequency   = $_REQUEST['frequency'];
$num_records = $_REQUEST['num_records'];

// Upload new MARC records
if($frequency) {
  $import_file = new import();
  $success = $import_file->upload( $resource_id, $frequency, $num_records, $_FILES );
  if( $success ) {
      $html = '<p>File ' . $type . ' successfully uploaded!</p>';
  } else {
      $html = '<p>File ' . $type . ' was not successfully uploaded. Please try again.</p>';
  }
// Update next load date to be 2 workdays from now
} else {
  $update = new update();
  $success = $update->next_load( $resource_id );
  if( $success ) {
      $html = '<p>Date successfully updated. <a href="' . config::URL . '">Return home</a>.</p>';
  } else {
      $html = '<p>Date was not successfully updated. Please go back and try again.</p>';
  }
}

$html = array('title' => 'Message', 'html' => $html);
template::display('generic.tmpl', $html, 'Message');
?>