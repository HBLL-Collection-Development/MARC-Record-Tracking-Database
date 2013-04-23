<?php
/**
  * Compress and download all files
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-04-23
  * @since 2013-03-04
  *
  */
require_once 'config.php';

$download     = new download;
$download_all = $download->download_file();

?>
