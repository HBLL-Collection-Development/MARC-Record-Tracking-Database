<?php
require_once 'config.php';

// Instantiate class
$import_file = new import();

$resource_id = $_REQUEST['resource_id'];
$frequency   = $_REQUEST['frequency'];

$success = $import_file->upload( $resource_id, $frequency, $_FILES );

if( $success ) {
    echo 'File ' . $type . ' successfully uploaded!';
} else {
    echo 'File ' . $type . ' was not successfully uploaded. Please try again.';
}
?>