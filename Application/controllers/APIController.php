<?php

/**
 * Description of PipelineController
 *
 * @author Eduardo Valente
 */
class APIController {

  function __construct() {

  }
  
  protected function sendOutput($data, $httpHeaders=array()) {
    header_remove('Set-Cookie');
    header('Access-Control-Allow-Origin: *');
    if (is_array($httpHeaders) && count($httpHeaders)) {
      foreach ($httpHeaders as $httpHeader) {
        header($httpHeader);
      }
    }
    echo $data;
    exit;
  }
    
  function painel($tipo,$modo,$token) {
    //$aux = json_encode( [ "token" => $json ] );
    //$objson = json_decode($json);
    if ( $tipo == 'action' ) {
      include_once('Application/controllers/ActionController.php');
      $controller = new ActionController();
    } elseif ( $tipo == 'task' ) {
      include_once('Application/controllers/TaskController.php');
      $controller = new TaskController();
    } else {
      include_once('Application/controllers/PipelineController.php');
      $controller = new PipelineController();
    }
    $obj = $controller->api($modo,$token);
    $resp = json_encode($obj);
    $this->sendOutput( $resp, array('Content-Type: application/json', 'HTTP/1.1 200 OK') );
  }
  
}
