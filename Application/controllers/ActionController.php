<?php

/**
 * Description of ActionController
 *
 * @author Eduardo Valente
 */
class ActionController {

  var $action;

  function __construct() {
    include_once('Application/models/Base.php');
    include_once('Application/models/Action.php');
    include_once('Application/views/ActionView.php');
  }
  
  function painel($modo,$token) {
    $obj = new Action();      
    $view = new ActionView();
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
    }
    return $ret;
  }
  
}
