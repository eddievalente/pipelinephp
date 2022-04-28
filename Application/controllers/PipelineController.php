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
      $ret .= $view->pipeline_ficha($obj); 
    } elseif ( $modo == 'novo' ) {
      $obj->loadNew();
      $ret .= $view->pipeline_novo($obj); 
    } elseif ( $modo == 'editar' ) {
      $obj->loadItem($token);
      $ret .= $view->pipeline_editar($obj); 
    } elseif ( $modo == 'exclusao' ) {
      $obj->loadItem($token);
      $ret .= $view->pipeline_exclui($obj); 
    } else {
      $ret .= $view->lista_pipeline($obj);
    }
    return $ret;
  }
  
}
