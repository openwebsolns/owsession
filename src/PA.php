<?php
namespace OWSession;

/**
 * Simple announcement object.
 *
 * PA = "Public Announcer". Short name helps serialization to string
 * and associated memory consumption.
 *
 * @author Dayan Paez
 * @version 2010-10-28
 */
class PA {
  const S = "success";
  const E = "error";
  const I = "warn";
  const Q = "rabbit";

  private $m;
  private $t;
  private $c;

  /**
   * Creates a new flash announcement
   *
   * @param mixed $message the message
   * @param const $type one of the class constants
   * @param String $context optional context for message
   */
  public function __construct($message, $type = PA::S, $context = null) {
    $this->m = $message;
    $this->t = $type;
    $this->c = $context;
  }

  public function getMessage() {
    return $this->m;
  }

  public function getType() {
    return $this->t;
  }

  public function getContext() {
    return $this->c;
  }
}
?>