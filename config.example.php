<?php
/**
  * Configuration class.
  *
  * @author Jared Howland <marc.records@jaredhowland.com>
  * @version 2013-02-27
  * @since 2013-02-22
  *
  */

class config {
  // Database settings
  const DB_HOST          = '';
  const DB_PORT          = '';
  const DB_NAME          = '';
  const DB_USERNAME      = '';
  const DB_PASSWORD      = '';
  // App settings
  const DEVELOPMENT      = TRUE; // Changes the app behavior (error reporting, template caching, which database to use, etc.)
  const URL              = '';
  const UPLOAD_DIRECTORY = 'records';
  const TIME_ZONE        = ''; // Needed for date calculations in PHP
  const NOTIFY_EMAILS    = ''; // Who should receive the emails; comma-delimited if more than one recipient (http://php.net/manual/en/function.mail.php)
  const FROM_EMAIL       = '';
  const FREQUENCY        = '["Once","Weekly","Monthly","Quarterly","Semiannually","Annually","When notified"]'; // JSON of allowed values for frequency records are loaded
  const ITEM_TYPES       = '["archival_material","article","audio","audiobook","bluray","book","book_chapter","calculator","cassette","cd","cd-rom","conference_proceeding","cuneiform","curriculum","database","dissertation","dvd","ebook","ebookreader","ejournal","equipment","faculty_use","filmstrip","finding_aid","government_document","image","index","ipad","journal","laptop","laserdisc","legal_document","magazine","map","microfiche","microformat","newspaper_article","ostrakon","other","palmleaf","pamphlet","periodical","photograph","pitcture","poster","rare_book","record","reeltape","reference_entry","research_datasets","review","score","statistical_data_set","scroll","serial","slide","software","statistical_data_set","streamingaudio","streamingvideo","streamingmedia","text_resource","vhs","video","walkman","website"]'; // Acceptable item types to be parsed by Primo


/****************************************************************************/
/*                       DO NOT EDIT BELOW THIS LINE                        */
/****************************************************************************/

  /**
    * Determines type of error reporting
    * Based on state of DEVELOPMENT constant
    *
    * @param null
    * @return string Type of error reporting
    */
  public static function set_error_reporting() {
    if(self::DEVELOPMENT) {
      // error_reporting(E_ALL);
      ini_set('error_reporting', E_ALL^E_NOTICE);
      ini_set('display_errors', 1);
    } else {
      error_reporting(0);
    }
  }
  
} // End class


/****************************************/
/* Miscellaneous configuration settings */
/****************************************/

// Autoload classes
// Must be in the 'classes' directory and prefixed with 'class.'
function __autoload( $class ) {
  require_once( __DIR__ . '/classes/class.' . $class . '.php' );
}

// Set default time zone
date_default_timezone_set( config::TIME_ZONE );

// Increase memory limit for large reading schedules
ini_set('memory_limit', '128M');

// Set error reporting
config::set_error_reporting();

?>
