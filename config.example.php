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
  const NOTIFY_EMAILS    = '';
  const FROM_EMAIL       = '';
  const FREQUENCY        = '["Once","Weekly","Monthly","Quarterly","Semiannually","Annually","When notified"]'; // JSON of allowed values for frequency records are loaded


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
