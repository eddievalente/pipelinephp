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

  function api($modo,$token) {
    
    if ( $modo == 'load' ) {
      $obj = new Pipeline();
      $obj->loadItem($token);
    } elseif ( $modo == 'save' ) {
      $obj = new Pipeline();
      $obj->loadNew();
      $obj->pipeline = $objson->pipeline;
      $obj->info = $objson->info;
      $obj->save();
    } elseif ( $modo == 'update' ) {
      $obj = new Pipeline();
      $obj->loadItem($objson->token);
      $obj->pipeline = $objson->pipeline;
      $obj->info = $objson->info;
      $obj->update();
    } elseif ( $modo == 'delete' ) {
      $obj = new Pipeline();
      $obj->loadItem($objson->token);
      $obj->remove();
    } else {
      $aux = new Pipeline();
      $obj = $aux->listAll();
    }
    return $obj;
  }
  
}
