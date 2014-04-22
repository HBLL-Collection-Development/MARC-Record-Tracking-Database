<?php
/**
  * Class to adjust the XML file
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2014-03-25
  * @since 2013-10-08
  *
  */

class adjust_xml {
  public function add_vendor($resource_id) {
    $file = config::UPLOAD_DIRECTORY . '/' . $resource_id . '.xml';
    if(file_exists($file)) {
      $xml_str         = file_get_contents($file);
      $xml_str         = $this->remove_583($xml_str);
      $xml_str         = $this->remove_resource_collection_name($xml_str);
      $field_583       = $this->get_583($resource_id);
      $vendor_name     = $field_583[0]['vendor_name'];
      $collection_name = $field_583[0]['resource_name'];
      $full_583        = $vendor_name . '-' . $collection_name;
      $full_583        = $this->create_583($full_583);
      $item_type       = $field_583[0]['item_type'];
      if(is_null($item_type)) {
        $item_type = null;
      } else {
        $item_type = '<marc:item_type>' . $item_type . '</marc:item_type>';
      }
      $search      = '</marc:record>';
      $replace     = $full_583 . '<marc:vendor_name>' . $vendor_name . '</marc:vendor_name><marc:collection_name>' . $collection_name . '</marc:collection_name>' . $item_type . '</marc:record>';
      $xml_str     = str_replace($search, $replace, $xml_str, $count);
      $num_records = $this->count_records($resource_id, $count);
      file_put_contents($file, $xml_str);
      echo $file . ' now includes vendor and collection name. (' . $count . ' records)<br/>';
    } else {
      echo $file . ' does not exist.<br/>';
    }
  }

  private function get_583($resource_id) {
    $database = new db;
    $db = $database->connect();
    $sql = 'SELECT r.resource_name, v.name AS vendor_name, r.item_type FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE r.id = :resource_id';
    $query = $db->prepare($sql);
    $query->bindParam(':resource_id', $resource_id);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $db = null;
    return $results;
  }

  private function remove_583($xml_str) {
    return preg_replace('/<marc:datafield tag="583".*?>.*?<\/marc:datafield>/s', '', $xml_str);
  }

  private function remove_resource_collection_name($xml_str) {
    return preg_replace('/<marc:vendor_name>.*?<\/marc:collection_name>/s', '', $xml_str);
  }

  private function create_583($full_583) {
    $subfield_a = $full_583;
    $subfield_c = date('Ymd');
    return '<marc:datafield tag="583" ind1=" " ind2=" "><marc:subfield code="a">' . $subfield_a . '</marc:subfield><marc:subfield code="c">' . $subfield_c . '</marc:subfield><marc:subfield code="5">UPB</marc:subfield></marc:datafield>';
  }

  private function count_records($resource_id, $num_records) {
    // Update database
    $database = new db;
    $db = $database->connect();
    $sql = 'UPDATE records SET num_records = :num_records WHERE id = :resource_id';
    $query = $db->prepare($sql);
    $query->bindParam(':num_records', $num_records);
    $query->bindParam(':resource_id', $resource_id);
    $status = $query->execute();
    $db = null;
    // PDO will return TRUE on success and FALSE on failure
    return $status;
  }

}
?>
