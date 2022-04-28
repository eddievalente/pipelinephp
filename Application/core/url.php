<?php

define('PREFIXO_SESSAO','pipe_');
//

/***
 *    ##     ## ########  ##       
 *    ##     ## ##     ## ##       
 *    ##     ## ##     ## ##       
 *    ##     ## ########  ##       
 *    ##     ## ##   ##   ##       
 *    ##     ## ##    ##  ##       
 *     #######  ##     ## ######## 
 */

class url {
  var $route;
  var $modo;
  var $id;
  
  function __construct() {
    $this->decode_param();
  }
  
  function is_route($menu) { 
    $vet_menu = array();
    $vet_menu[] = 'pipeline';
    $vet_menu[] = 'action';
    $vet_menu[] = 'task';
    return in_array($menu,$vet_menu);
  }
  
  function decode_param() {
    $url_atual = isset($_GET['url']) ? $_GET['url'] : '';
    $parametros = explode('/', $url_atual);
    $param0 = isset($parametros[0]) ? $parametros[0] : '';
    $param1 = isset($parametros[1]) ? $parametros[1] : '';
    $param2 = isset($parametros[2]) ? $parametros[2] : '';
    if ( $this->is_route($param0) ) {
      $this->route = $param0;
      $this->modo = $param1;
      $this->id = $param2;
    } else {
      $this->route = '';
      $this->modo = $param0;
      $this->id = $param1;
    }
    return;
  }
  
  function redirect($url) {
    $ret = '';
    header('Location: '.$url, true, 301);
    return $ret;
  }
  
  function get_host() {
    $host = $_SERVER['HTTP_HOST']; 
    return 'http://'.$host;
  }
  
  function get_host_path() {
    $scriptpath = '/pipelinephp/';
    return $this->get_host().$scriptpath;
  }

  function get_href_int($route='',$modo='',$id='') {
    $conector = '';
    $link = '';
    $base = $this->get_host_path();
    if ( !empty($route) ) {
      $link .= $conector.$route;
      $conector = '/';
    }
    if ( !empty($modo) ) {
      $link .= $conector.$modo;
      $conector = '/';
    }
    if ( !empty($id) ) {
      $link .= $conector.$id;
      $conector = '/';
    }
    return $base.$link;
  }

  function get_link_int($texto,$route='',$modo='',$id='',$info='') {
    $exibe = '';
    $link = $this->get_href_int($route,$modo,$id);
    if ( !empty($info)) $info = 'title="'.$info.'"';
    $exibe .= '<a href="'.$link.'" '.$info.'>'.$texto.'</a>';
    return $exibe;
  }
  
  function breadcrumb($nivel,$titulo='',$link1='',$link2='',$link3='',$link4='',$link5='') {
    $inicio = '<span class=inicio>Personal Task Manager</span>';
    $divisor = '<span class=divisor>&gt;</span>';
    $titulo = '<span class=texto>'.$titulo.'</span>';
    $exibe = '<div class="breadcrumb">';
    if ( $nivel == 0 ) { // inicio
      $exibe .= $inicio;
    } elseif ( $nivel == 1 ) { // inicio > titulo
      $exibe .= $inicio.$divisor.$titulo;
    } elseif ( $nivel == 2 ) { // inicio > titulo > grupo
      $exibe .= $inicio.$divisor.$link1.$divisor.$titulo;
    } elseif ( $nivel == 3 ) { // inicio > titulo > grupo > item
      $exibe .= $inicio.$divisor.$link1.$divisor.$link2.$divisor.$titulo;
    } elseif ( $nivel == 4 ) { // inicio > titulo > grupo > item > subitem
      $exibe .= $inicio.$divisor.$link1.$divisor.$link2.$divisor.$link3.$divisor.$titulo;
    } elseif ( $nivel == 5 ) { // inicio > titulo > grupo > item > subitem
      $exibe .= $inicio.$divisor.$link1.$divisor.$link2.$divisor.$link3.$divisor.$link4.$divisor.$titulo;
    } elseif ( $nivel == 6 ) { // inicio > titulo > grupo > item > subitem
      $exibe .= $inicio.$divisor.$link1.$divisor.$link2.$divisor.$link3.$divisor.$link4.$divisor.$link5.$divisor.$titulo;
    }
    $exibe .= '</div>
       ';
    return $exibe;
  } 
  
  function dlg_question($name,$msg,$link_if_ok) {
    
    $ret = '<script type="text/javascript">
      $(document).ready(function() { 
        $("#'.$name.'").on("click", function() {
          new $.Zebra_Dialog("'.$msg.'", {
            auto_focus_button: $("body.materialize").length ? false : true,
            type: "question",
            buttons: [
                {caption: "No", callback: function() { }},
                {caption: "Yes", callback: function() { $( location ).attr("href","'.$link_if_ok.'"); }}
                
            ]
          });
       });
    });
    </script>
    ';
    return $ret;
  }  
  
  function dlg_button($name,$label) {
    $ret = '<a href="javascript:void(0)" id="'.$name.'">'.$label.'</a>
        ';
    return $ret;
  }

}

?>