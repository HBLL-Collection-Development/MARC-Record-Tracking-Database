<?php
/**
  * Class to adjust the XML file
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-10-08
  * @since 2013-10-08
  *
  */

class adjust_xml {
  public function add_vendor($resource_id) {
    $file    = config::UPLOAD_DIRECTORY . '/' . $resource_id . '.xml';
    $xml_str = file_get_contents($file);
    $xml     = new SimpleXMLElement($xml_str);
    // MARC namespace
    $marc    = $xml->children('http://www.loc.gov/MARC21/slim');
    foreach($marc->record AS $record) {
      $vendor_name = $record->vendor_name;
      if($vendor_name) {
        break;
      } else {
        $field_583       = $this->get_583($resource_id);
        $vendor_name     = $field_583[0]['vendor_name'];
        $collection_name = $field_583[0]['resource_name'];
        $vendor->addChild('vendor_name', $vendor_name);
        $vendor->addChild('collection_name', $collection_name);
      }
    }
    $xml->asXML($file);
  }
  
  private function get_583($resource_id) {
    $database = new db;
    $db = $database->connect();
    $sql = 'SELECT r.resource_name, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.id = :resource_id';
    $query = $db->prepare($sql);
    $query->bindParam(':resource_id', $resource_id);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $db = null;
    return $results;
  }

}
?>
