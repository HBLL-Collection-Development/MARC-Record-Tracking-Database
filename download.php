<?php
/**
  * Compress and download all files
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2014-03-27
  * @since 2013-03-04
  *
  */
require_once 'config.php';

$download = $_REQUEST['download'];

if(!$download) {
  $download     = new download;
  $download_all = $download->download_file();
} else {
  $download     = new download;
  $download_all = $download->download_file(false);
  echo 'New archive has been successfully created.';
}

?>
