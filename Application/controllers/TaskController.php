<?php

/**
 * Description of TaskController
 *
 * @author Eduardo Valente
 */
class TaskController {

  var $task;

  function __construct() {
    include_once('Application/models/Base.php');
    include_once('Application/models/Task.php');
    include_once('Application/views/TaskView.php');
  }

  function painel($modo,$token) {
    $obj = new Task();
    $view = new TaskView();
    //    
    $ret = '';
    if ( $modo == 'ficha' ) {
      $obj->loadItem($token);
      $ret .= $view->ficha($obj); 
    } elseif ( $modo == 'novo' ) {
      $obj->loadNew($token);
      $ret .= $view->nova($obj); 
    } elseif ( $modo == 'editar' ) {
      $obj->loadItem($token);
      $ret .= $view->editar($obj); 
    } elseif ( $modo == 'exclusao' ) { 
      $obj->loadItem($token);
      $ret .= $view->exclui($obj); 
    } elseif ( $modo == 'up' ) {
      $obj->loadItem($token);
      $ret .= $view->up($obj); 
    } elseif ( $modo == 'down' ) {
      $obj->loadItem($token);
      $ret .= $view->down($obj); 
    } elseif ( $modo == 'progresso' ) {
      $obj->loadItem($token);
      $ret .= $view->progresso($obj); 
    }
    return $ret;
  }
  
}
