<?php

/**
 * Description of Base
 *
 * @author Eduardo Valente
 */
class Base {

  var $db;
  var $utili;

  function __construct() {
    include_once('Application/models/Pipeline.php');
    include_once('Application/models/Action.php');
    include_once('Application/models/Task.php');
    $this->db = banco::getInstance();
    $this->utili = new utili;
  }
  
  function get_post($campo) {
    $ret = $this->db->get_post($campo);
    return $ret;
  }
  
}
