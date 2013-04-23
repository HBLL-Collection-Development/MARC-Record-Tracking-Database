<?php
/**
  * Displays all active resources in the database
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-04-23
  * @since 2013-04-23
  *
  */
require_once 'config.php';

$display = new display;
$html    = $display->html('active');
template::display('html.tmpl', $html);
?>