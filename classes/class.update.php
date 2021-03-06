<?php
/**
  * Class to update the database
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-02-24
  * @since 2013-02-24
  *
  */

class update {
  /**
   * Updates the database from a form submission
   *
   * @access public
   * @param array Data submitted from the form
   * @return bool TRUE if successfully updated database; FALSE otherwise
   */
  public function records_table($form_data) {
    return $this->update_records($form_data);
  }
  
  public function next_load($resource_id) {
    $next_load = date('Y-m-d', strtotime('+2 weekdays'));
    $database = new db;
    $db = $database->connect();
    $sql = 'UPDATE records SET next_load = :next_load WHERE id = :resource_id';
    $query = $db->prepare($sql);
    $query->bindParam(':next_load', $next_load);
    $query->bindParam(':resource_id', $resource_id);
    $status = $query->execute();
    $db = null;
    return $status;
  }

  private function update_records($form_data) {
    // Clean data
    $url           = trim($form_data['url']);
    $username      = trim($form_data['username']);
    $password      = trim($form_data['password']);
    $frequency     = trim($form_data['frequency']);
    $item_type     = trim($form_data['item_type']);
    if($item_type == '') { $item_type = null; }
    if($form_data['next_load'] != '') {
      $next_load   = trim(date('Y-m-d', strtotime($form_data['next_load'])));
    } else {
      $next_load   = NULL;
    }
    $num_records   = trim(str_replace(',', '', $form_data['num_records']));
    $load_records  = trim($form_data['load_records']);
    $notes         = trim($form_data['notes']);
    $resource_id   = trim($form_data['resource_id']);
    // Update database
    $database = new db;
    $db = $database->connect();
    $sql = 'UPDATE records SET url = :url, username = :username, password = :password, frequency = :frequency, next_load = :next_load, num_records = :num_records, load_records = :load_records, item_type = :item_type, notes = :notes WHERE id = :resource_id';
    $query = $db->prepare($sql);
    $query->bindParam(':url', $url);
    $query->bindParam(':username', $username);
    $query->bindParam(':password', $password);
    $query->bindParam(':frequency', $frequency);
    $query->bindParam(':item_type', $item_type);
    $query->bindParam(':next_load', $next_load);
    $query->bindParam(':num_records', $num_records);
    $query->bindParam(':load_records', $load_records);
    $query->bindParam(':notes', $notes);
    $query->bindParam(':resource_id', $resource_id);
    $status = $query->execute();
    $db = null;
    // PDO will return TRUE on success and FALSE on failure
    return $status;
  }
}
?>
