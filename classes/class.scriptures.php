<?php
/**
  * Class to access scriptures in the SQLite database
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2012-12-11
  * @since 2012-12-06
  *
  */

class scriptures {
  private $start_verse_id;
  private $end_verse_id;
  private $highlight_shortest_verse; // FALSE is default
          
  private $verses;
  private $chapters;

  /**
   * Returns an array with the day’s reading schedule
   *
   * @access public
   * @param int Starting verse ID
   * @param int Ending verse ID
   * @param bool Whether or not to highlight the shortest verse in the reading. Optional. FALSE is default.
   * @return array Array containing the reading schedule
   *
   */
  public function reading($start_verse_id, $end_verse_id, $highlight_shortest_verse = FALSE) {
    // Throw exception if an invalid verse id is used
    if($end_verse_id < $start_verse_id) {
      throw new Exception('Ending verse must be after starting verse. Start verse id: ' . $start_verse_id . '; End verse id: ' . $end_verse_id);
    } elseif($start_verse_id < 1 || $end_verse_id > 41995) {
      throw new Exception('Verse IDs must be between 1 and 41995 (last verse in database) inclusive. Start verse id: ' . $start_verse_id . '; End verse id: ' . $end_verse_id);
    }
    // Set variables
    $this->start_verse_id           = $start_verse_id;
    $this->end_verse_id             = $end_verse_id;
    $this->highlight_shortest_verse = $highlight_shortest_verse;
    return $this->get_reading();
  }

  private function get_reading() {
    $this->verses   = $this->get_verses();
    $this->chapters = $this->get_chapters();
    // Calculate the shortest verse only if $highlight_shortest_verse is TRUE
    if($this->highlight_shortest_verse) {
      $shortest_verse_id = $this->get_shortest_verse_id();
    }
    return array('reading' => $this->get_chapter_titles(), 'verse_count' => $this->get_verse_count(), 'word_count' => $this->get_word_count(), 'est_time' => $this->get_est_time(), 'highlight_shortest' => $this->highlight_shortest_verse, 'shortest_verse_id' => @$shortest_verse_id, 'chapters' => $this->chapters);
  }
  
  /**
   * Returns a string containing the titles for day’s reading
   * *Example:* '1 Nephi 2–1 Nephi 4' or 'Leviticus 11'
   *
   * @access private
   * @param int ID of the first verse
   * @param int ID of the last verse
   * @return string Titles for day’s reading
   */
  private function get_chapter_titles() {
    $keys = array_keys($this->chapters);
    $end_title = $this->get_chapter_title(end($keys));
    $start_title = $this->get_chapter_title(reset($keys));
    if($start_title == $end_title) {
      return $start_title;
    } else {
      return $start_title . '–' . $end_title;
    }
  }
  
  /**
   * Returns a string containing the chapter title
   * *Example:* '1 Nephi 2' or 'Leviticus 11'
   *
   * @access private
   * @param int Chapter ID
   * @return string Chapter title
   */
  private function get_chapter_title($chapter_id) {
    return $this->chapters[$chapter_id]['title'];
  }
  
  /**
   * Returns verse ID of shortest verse
   *
   * @access private
   * @param null
   * @return int Verse ID of shortest verse
   */
  private function get_shortest_verse_id() {
    // Grab full text of the shortest verse
    $shortest_verse = $this->find_shortest_verse();
    foreach($this->verses as $verse) {
      if($verse['scripture'] == $shortest_verse) {
        return $verse['id'];
      }
    }
  }
  
  /**
   * Returns a string of the full text of shortest verse in $array
   *
   * @access private
   * @param array Array containing database query results of verses
   * @return string Full text of the shortest verse
   */
  private function find_shortest_verse() {
    $verses = $this->extract_verses();
    $mapping = array_combine($verses, array_map('strlen', $verses)); // http://php.net/manual/en/function.array-flip.php#89724
    // If there is a tie, only return the first result for shortest verse
    $shortest_verse = array_keys($mapping, min($mapping));
    return $shortest_verse[0];
  }
  
  /**
   * Get verse count
   *
   * @access private
   * @param null
   * @return int Number of verses in day’s reading
   */
  private function get_verse_count() {
    return count($this->extract_verses());
  }
  
  /**
   * Get word count
   *
   * @access private
   * @param null
   * @return int Number of words in day’s reading
   */
  private function get_word_count() {
    return str_word_count(implode(' ', $this->extract_verses()));
  }
  
  /**
   * Get estimated reading time
   *
   * @access private
   * @param null
   * @return int Estimated reading time in minutes
   */
  private function get_est_time() {
    return number_format(ceil($this->get_word_count() / config::WORDS_PER_MINUTE ));
  }
  
  /**
   * Returns an array with just the verse text (each verse is an array value)
   *
   * @access private
   * @param null
   * @return array Array containing verses
   */
  private function extract_verses() {
    return array_map(function ($ar) {return $ar['scripture'];}, $this->verses); // http://stackoverflow.com/a/7994555
  }
  
  /**
   * Returns an array with the day’s verses
   *
   * @access private
   * @param null
   * @return array Array containing database query results of verses
   */
  private function get_verses() {
    // Connect to the database
    $database = new db;
    $db = $database->connect();
    // Run database query
    $sql = 'SELECT v.id, b.title AS book, c.id AS chapter_id, c.chapter, lc.chapter_summary, v.verse, v.scripture FROM verses v INNER JOIN chapters c ON v.chapter_id = c.id INNER JOIN lds_chapters lc ON c.id = lc.id INNER JOIN books b ON c.book_id = b.id WHERE v.id BETWEEN :start_verse_id AND :end_verse_id ORDER BY v.id';
    $query = $db->prepare($sql);
    $query->bindParam(':start_verse_id', $this->start_verse_id, PDO::PARAM_INT);
    $query->bindParam(':end_verse_id', $this->end_verse_id, PDO::PARAM_INT);
    $query->execute();
    // Store results in $verses() array
    $verses = $query->fetchAll(PDO::FETCH_ASSOC);
    // Close database connection
    $db = null;
    return $verses;
  }
  
  /**
   * Returns the verse id of the first verse of the chapter
   *
   * @access private
   * @param int Chapter id
   * @return int Verse id of first verse in the chapter
   */
  public function get_first_verse_id($chapter_id) {
    // Connect to the database
    $database = new db;
    $db = $database->connect();
    // Run database query
    $sql = 'SELECT id FROM verses WHERE chapter_id = :chapter_id ORDER BY id LIMIT 1';
    $query = $db->prepare($sql);
    $query->bindParam(':chapter_id', $chapter_id, PDO::PARAM_INT);
    $query->execute();
    // Store result in $verse_id() array
    $verse_id = $query->fetch(PDO::FETCH_ASSOC);
    // Close database connection
    $db = null;
    return $verse_id['id'];
  }
  
  /**
   * Returns the verse id of the last verse of the chapter
   *
   * @access private
   * @param int Chapter id
   * @return int Verse id of last verse in the chapter
   */
  public function get_last_verse_id($chapter_id) {
    // Connect to the database
    $database = new db;
    $db = $database->connect();
    // Run database query
    $sql = 'SELECT id FROM verses WHERE chapter_id = :chapter_id ORDER BY id DESC LIMIT 1';
    $query = $db->prepare($sql);
    $query->bindParam(':chapter_id', $chapter_id, PDO::PARAM_INT);
    $query->execute();
    // Store result in $verse_id() array
    $verse_id = $query->fetch(PDO::FETCH_ASSOC);
    // Close database connection
    $db = null;
    return $verse_id['id'];
  }
  
  /**
   * Returns the count of all verses in the reading schedule
   * This differs from get_verse_count in that it is the full schedule
   * and not just the schedule for one day
   *
   * @access private
   * @param int ID of first chapter
   * @param int ID of last chapter
   * @return array Array of the verse count for every chapter in the reading schedule
   */
  public function get_schedule_verse_counts($start_chapter_id, $end_chapter_id) {
    // Connect to the database
    $database = new db;
    $db = $database->connect();
    // Run database query
    $sql = 'SELECT chapter_id, COUNT(id) as num_verses FROM verses WHERE chapter_id BETWEEN :start_chapter_id AND :end_chapter_id GROUP BY chapter_id';
    $query = $db->prepare($sql);
    $query->bindParam(':start_chapter_id', $start_chapter_id, PDO::PARAM_INT);
    $query->bindParam(':end_chapter_id', $end_chapter_id, PDO::PARAM_INT);
    $query->execute();
    // Store results in $verse_count() array
    $verse_counts = $query->fetchAll(PDO::FETCH_ASSOC);
    // Close database connection
    $db = null;
    return $verse_counts;
  }
  
  /**
   * Returns an array grouped by chapter
   *
   * @access private
   * @param array
   * @return array Array containing verses grouped by chapter id with chapter metadata
   */
  private function get_chapters(){
    // Group verses by chapter id
    $all_verses = $this->group_array($this->verses, 'chapter_id');
    foreach($all_verses as $chapter_id=>$verses) {
      // Shorten Doctrine & Covenants
      $book = $verses[0]['book'];
      if($book == 'Doctrine & Covenants') { $book = 'D&C'; }
      $summary = $this->fix_small_caps($verses[0]['chapter_summary']);
      $chapters[] = array('id' => $chapter_id, 'title' => $book . ' ' . $verses[0]['chapter'], 'summary' => $summary, 'verses' => $verses);
    }
    return $chapters;
  }
  
  /**
   * Encapsulates B.C. and A.D. in <span> with class 'caps' for small caps display
   *
   * @access private
   * @param array
   * @return array Array containing verses grouped by chapter id with chapter metadata
   */
  private function fix_small_caps($summary) {
    // Fix B.C and A.D. to be small caps
    $search = array( 'B.C.', 'A.D.' );
    $replace = array( '<span class="caps">B.C.</span>', '<span class="caps">A.D.</span>' );
    return str_replace( $search, $replace, $summary );
  }
  
  /**
   * Returns an array grouped by provided array index
   *
   * @access private
   * @param array
   * @param string|int Array index key to group the array by
   * @return array Array containing verses grouped by chapter id
   */
  private function group_array($array, $array_index){
    foreach ($array as $key => $value) {
      $output[$value[$array_index]][] = $value;
    }
    return $output;
  }
}
?>
