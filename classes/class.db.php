<?php
/**
  * Database class to connect to an SQLite database
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2012-12-06
  * @since 2012-12-05
  *
  */

class db {
  /**
   * Connects to the database
   *
   * @access public
   * @param null
   * @return object Object containing database connection information
   */
  public function connect() {
    return $this->db_connect();
  }

  private function db_connect() {
    try {
      $db = new PDO('mysql:host=' . config::DB_HOST . ';port=' . config::DB_PORT . ';dbname=' . config::DB_NAME, config::DB_USERNAME, config::DB_PASSWORD);
      return $db;
    // Throw an error and kill script if cannot connect
    } catch ( PDOException $e ) {
      $e->getMessage();
      die();
    }
  }

}
?>
