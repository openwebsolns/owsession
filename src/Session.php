<?php
namespace OWSession;

/**
 * Manages the session data from a static namespaced class.
 *
 * In addition to providing an easy-to-use interface for dealing with
 * Session data (including objects), it also provides functionality
 * for "flash" messages through the PA class.
 *
 * @author Dayan Paez
 * @version 2010-10-13
 */
class Session {

  // User-saved variables
  public static $DATA = array();

  // ------------------------------------------------------------
  // Announcement capabilities
  // ------------------------------------------------------------

  private static $flashes = array();

  /**
   * Queue the given announcement
   *
   * The $context argument is a user-defined String that allows
   * conditional fetching of the announcements.
   *
   * @param String $mes the message
   * @param Const $type one of PA::* constants (default = PA::S = success)
   * @param String $context an optional grouping variable
   * @return PA the newly created object
   * @see popFlashes
   */
  public static function flash($mes, $type = PA::S, $context = null) {
    $obj = new PA($mes, $type, $context);
    self::$flashes[] = $obj;
    return $obj;
  }

  /**
   * Returns (and removes) announcements of optional type
   *
   * By default, return all messages
   *
   * @param const $type optional type of messages to remove
   * @param String $context optional context for message
   * @return Array the messages
   */
  public static function popFlashes($type = null, $context = null) {
    $list = array();
    $i = 0;
    while ($i < count(self::$flashes)) {
      $pa = self::$flashes[$i];
      if (($type === null || $type == $pa->getType()) && ($context === null || $context == $pa->getContext())) {
        $list[] = $pa;
        array_splice(self::$flashes, $i, 1);
      }
      else {
        $i++;
      }
    }
    return $list;
  }

  /**
   * Like popFlashes, but does not remove them internally
   *
   * This method allows access to flash messages without removing them
   * from the internal list
   *
   * @param const $type optional type of messages to remove
   * @param String $context optional context for message
   * @return Array the messages
   * @see popFlashes
   */
  public static function peekFlashes($type = null, $context = null) {
    $list = array();
    $i = 0;
    foreach (self::$flashes as $pa) {
      if (($type === null || $type == $pa->getType()) && ($context === null || $context == $pa->getContext())) {
        $list[] = $pa;
      }
    }
    return $list;
  }

  /**
   * Initializes the session class from the session object, opening a
   * session if one not already opened.
   *
   * @throws RuntimeException if unable to create session
   */
  public static function init() {
    if (session_id() == "") {
      if (!session_start())
        throw new RuntimeException("Unable to start session from Session class.");
    }

    // register commit()
    register_shutdown_function(array(get_class(), 'commit'));

    if (isset($_SESSION['_announce_'])) {
      foreach ($_SESSION['_announce_'] as $a)
	self::$flashes[] = unserialize($a);
    }

    // other parameters
    if (isset($_SESSION['_data_']))
      self::$DATA = unserialize($_SESSION['_data_']);
  }
  
  /**
   * Call this method to actually send the information back to the
   * session. Note that this does NOT call session_write_close
   *
   */
  public static function commit() {
    // commit announcements
    $_SESSION['_announce_'] = array();
    foreach (self::$flashes as $a)
      $_SESSION['_announce_'][] = serialize($a);

    // commit data
    $_SESSION['_data_'] = serialize(self::$DATA);
  }

  /**
   * Sets the following variable to the session (will be committed at
   * the end of the script)
   *
   * @param String $key the key to set
   * @param mixed $value the value
   */
  public static function s($key, $value = null) {
    self::$DATA[$key] = $value;
  }

  /**
   * Returns the value for the given key, if one exists
   *
   * @param String $key the key
   * @return mixed the value, or null
   */
  public static function g($key) {
    if (!self::has($key))
      return null;
    return self::$DATA[$key];
  }

  public static function has($key) {
    return isset(self::$DATA[$key]);
  }
}
?>