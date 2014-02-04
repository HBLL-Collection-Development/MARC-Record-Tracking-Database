<?php
require_once 'config.php';
require_once 'lib/xmlreader-iterators.php'; // https://gist.github.com/hakre/5147685

ini_set("memory_limit","12000M");
ini_set('max_execution_time', 300);

$min = 36;
$max = 36;

$i = $min;

while($i <= $max) {
  echo $i . '<br/>';
  $adjust_xml = new adjust_xml();
  $add_vendor = $adjust_xml->add_vendor($i);
  $i++;
}

?>
