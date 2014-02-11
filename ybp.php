<?php
/**
  * Displays all active resources in the database
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2014-02-10
  * @since 2014-02-10
  *
  */
require_once 'config.php';

$display = new display;
$html    = $display->html('ybp');

header("Content-Type: text/plain");
header('Content-Disposition: attachment; filename=YBP_ISBNs.txt');
template::display('ybp.tmpl', $html);
?>