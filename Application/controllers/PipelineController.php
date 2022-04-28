<?php

/**
 * Description of PipelineController
 *
 * @author Eduardo Valente
 */
class PipelineController {

  function __construct() {
    include_once('Application/models/Base.php');
    include_once('Application/models/Pipeline.php');
    include_once('Application/views/PipelineView.php');
  }
  
  function painel($modo,$token) {
    $obj = new Pipeline();
    $view = new PipelineView();
    $ret = '';
    if ( $modo == 'ficha' ) {
      $obj->loadItem($token);
      $ret .= $view->ficha($obj); 
    } elseif ( $modo == 'novo' ) {
      $obj->loadNew();
      $ret .= $view->novo($obj); 
    } elseif ( $modo == 'editar' ) {
      $obj->loadItem($token);
      $ret .= $view->editar($obj); 
    } elseif ( $modo == 'exclusao' ) {
      $obj->loadItem($token);
      $ret .= $view->exclui($obj); 
    } else {
      $ret .= $view->lista($obj);
    }
    return $ret;
  }
  
}
