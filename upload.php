<?php
require_once 'config.php';

$resource_id = $_REQUEST['resource_id'];
$frequency   = $_REQUEST['frequency'];

// Upload new MARC records
if($frequency) {
  $import_file = new import();
  $success = $import_file->upload( $resource_id, $frequency, $_FILES );
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
      $html = '<p>Date successfully updated.</p>';
  } else {
      $html = '<p>Date was not successfully updated. Please try again.</p>';
  }
}

$html = array('title' => 'Message', 'html' => $html);
template::display('generic.tmpl', $html, 'Message');
?>