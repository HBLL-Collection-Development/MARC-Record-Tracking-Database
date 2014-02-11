<?php
/**
  * Class to display information from database
  *
  * @author  Jared Howland <marc.records@jaredhowland.com@gmail.com>
  * @version 2014-02-10
  * @since 2013-04-23
  */
  
class display {
    /**
    * Constructor. Displays information from the database
    *
    * @access public
    * @param string Type of query to display (Valid values: “all”, “active”, “inactive”)
    * @return string HTML to put into template
    */
    public function html($type) {
      $results = $this->query_database($type);
      return $this->get_html($type, $results);
    }
    
    /**
    * Gets list of all requested data from the database
    *
    * @access public
    * @param string Type of query to display (Valid values: “all”, “active”, “inactive”)
    * @return array Array with data about resources
    */
    private function query_database($type) {
      $database = new db;
      $db = $database->connect();
      switch ($type) {
        case 'all':
          $sql = 'SELECT r.id, r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.last_updated, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id ORDER BY r.next_load ASC, vendor_name, resource_name';
          break;
        case 'active':
          $sql = 'SELECT r.id, r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.last_updated, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE load_records = "Y" ORDER BY r.next_load ASC, vendor_name, resource_name';
          break;
        case 'inactive':
          $sql = 'SELECT r.id, r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.last_updated, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE load_records = "N" ORDER BY r.next_load ASC, vendor_name, resource_name';
          break;
        case 'ybp':
          $sql = 'SELECT r.id FROM records r WHERE load_records = "Y" AND file_exists = "Y"';
          break;
        default:
          $sql = 'SELECT r.id, r.resource_name, r.url, r.username, r.password, r.frequency, r.last_load, r.next_load, r.num_records, r.notes, r.last_updated, r.file_exists, v.name AS vendor_name FROM records r INNER JOIN vendors v ON r.vendor_id = v.id WHERE load_records = "Y" ORDER BY r.next_load ASC, vendor_name, resource_name';
          break;
      }
      $query = $db->prepare($sql);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_ASSOC);
      $db = null;
      return $results;
    }
    
    /**
    * Creates array of data to be put into HTML template
    *
    * @access public
    * @param string Type of query to display (Valid values: “all”, “active”, “inactive”)
    * @param array Array with database query results
    * @return array Array with data about resources
    */
    private function get_html($type, $results) {
      switch ($type) {
        case 'all':
          $html = array('title' => 'All', 'results' => $results);
          break;
        case 'active':
          $stats = $this->get_stats($results);
          $html = array('title' => 'Home', 'stats' => $stats, 'results' => $results);
          break;
        case 'inactive':
          $html = array('title' => 'Inactive', 'results' => $results);
          break;
        case 'ybp':
          $html = array('title' => 'YBP', 'isbns' => $this->get_isbns($results));
          break;
        default:
          $stats = $this->get_stats($results);
          $html = array('title' => 'Home', 'stats' => $stats, 'results' => $results);
          break;
      }
      return $html;
    }
    
    /**
    * Calculates and returns vital statistics to be presented on the home page
    *
    * @access public
    * @param array Array with data about resources
    * @return array Array with stats about the resources
    */
    private function get_stats($results) {
      $num_resources            = count($results);
      $num_up_to_date_resources = 0;
      $num_records              = 0;

      foreach($results as $resource) {
        $num_records += $resource['num_records'];
        // To count as being up to date, must have a file associated with it, and have the next load date in the future
        if($resource['file_exists'] == 'Y' && (strtotime($resource['next_load']) > strtotime(date('Y-m-d')) || is_null($resource['next_load']))) {
      	$num_up_to_date_resources++;
        }
      }
      $percent_complete = round(($num_up_to_date_resources / $num_resources) * 100) . '%';

      return array('num_records' => $num_records, 'num_resources' => $num_resources, 'num_up_to_date_resources' => $num_up_to_date_resources, 'percent_complete' => $percent_complete);
    }
    
    private function get_isbns($results) {
      // Clear contents of ybp.txt
      file_put_contents('ybp.txt', '');
      foreach($results as $result) {
        $resource_id = $result['id'];
        $file = config::UPLOAD_DIRECTORY . '/' . $resource_id . '.xml';
        if(file_exists($file)) {
          $xml_str = file_get_contents($file);
          $isbns = $this->extract_isbns($xml_str, $file);
          file_put_contents('ybp.txt', $isbns, FILE_APPEND | LOCK_EX);
        } else {
          die();
        }
      }
      $isbns = file('ybp.txt');
      foreach ($isbns as $line_num => $isbn) {
        $isbn = $this->clean_isbn($isbn);
        if($this->is_isbn_valid($isbn)===TRUE) {
          $clean_isbns[] = $isbn;
        }
      }
      // 102,079 isbns
      // 88,598 after validation
      return $clean_isbns;
    }
    
    private function extract_isbns($xml_str, $file) {
      ini_set('memory_limit', '-1');
      $pattern = '/<marc:datafield tag=\"020\".*?><marc:subfield code=\"a\">(.*?)<\/marc:subfield>/';
      preg_match_all($pattern, $xml_str, $matches);
      return implode("\n", $matches[1]);
    }
    
    /**
      * Validates ISBN-10s and ISBN-13s
      *
      * @access private
      * @param string ISBN
      * @return mixed ISBN string if valid; bool FALSE otherwise
      *
      */
    private function is_isbn_valid($isbn) {
      if (!is_string($isbn) && !is_int($isbn)) {
        return FALSE;
      }
      $isbn = (string) $isbn;
      // ISBN-10
      if(strlen($isbn) == 10) {
        $a = 0;
        for($i = 0; $i < 10; $i++){
          if(strtoupper($isbn[$i]) == "X"){
            $a += 10*intval(10-$i);
          } else { //running the loop
            $a += intval($isbn[$i]) * intval(10-$i);
          }
        }
        // return ($a % 11 == 0);
        return TRUE;
      // ISBN-13
      } elseif(strlen($isbn) == 13) {
        $check = 0;
        for($i = 0; $i < 13; $i+=2) $check += substr($isbn, $i, 1);
        for($i = 1; $i < 12; $i+=2) $check += 3 * substr($isbn, $i, 1);
        // return $check % 10 == 0;
        return TRUE;
      } else {
        return FALSE;
      }
    }
    
    private function clean_isbn($isbn) {
      $pattern = '/[^0-9Xx]/';
      $replace = '';
      return preg_replace($pattern, $replace, $isbn);
    }
}
?>