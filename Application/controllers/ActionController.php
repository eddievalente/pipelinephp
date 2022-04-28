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
      $ret .= $view->acao_ficha($obj); 
    } elseif ( $modo == 'novo' ) {
      $obj->loadNew($token);
      $ret .= $view->acao_nova($obj); 
    } elseif ( $modo == 'editar' ) {
      $obj->loadItem($token);
      $ret .= $view->acao_editar($obj); 
    } elseif ( $modo == 'exclusao' ) {
      $obj->loadItem($token);
      $ret .= $view->acao_exclui($obj); 
    } elseif ( $modo == 'up' ) {
      $obj->loadItem($token);
      $ret .= $view->acao_up($obj); 
    } elseif ( $modo == 'down' ) {
      $obj->loadItem($token);
      $ret .= $view->acao_down($obj); 
    }
    return $ret;
  }
  
}
