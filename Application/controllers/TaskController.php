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
      $ret .= $view->tarefa_ficha($obj); 
    } elseif ( $modo == 'novo' ) {
      $obj->loadNew($token);
      $ret .= $view->tarefa_nova($obj); 
    } elseif ( $modo == 'editar' ) {
      $obj->loadItem($token);
      $ret .= $view->tarefa_editar($obj); 
    } elseif ( $modo == 'exclusao' ) { 
      $obj->loadItem($token);
      $ret .= $view->tarefa_exclui($obj); 
    } elseif ( $modo == 'up' ) {
      $obj->loadItem($token);
      $ret .= $view->tarefa_up($obj); 
    } elseif ( $modo == 'down' ) {
      $obj->loadItem($token);
      $ret .= $view->tarefa_down($obj); 
    } elseif ( $modo == 'progresso' ) {
      $obj->loadItem($token);
      $ret .= $view->tarefa_progresso($obj); 
    }
    return $ret;
  }
  
}
