<?php

class banco {
    
  var $ultimoid;
  var $conexao = '';
  var $conectado = false;
  var $url;
  
  // Save class instances in this property
  private static $_instance;

  public static function getInstance() {
    if(! (self::$_instance instanceof self) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    $this->url = new url;
  }
  
  function conectar() {
    if ( $this->conectado ) {
      $mysqli = $this->conexao;
    } else {
      $hostname_conexao = "localhost";
      $database_conexao = "pipeline";
      $username_conexao = "root";
      $password_conexao = "";
      $mysqli = new mysqli($hostname_conexao, $username_conexao, $password_conexao, $database_conexao);
      $mysqli->set_charset("utf8");
      $this->conexao = $mysqli;
    }
    return $mysqli;

  }

  function consulta($sql) {
    $mysqli = $this->conectar();
    $consulta = $mysqli->query($sql);
    $this->ultimoid = $mysqli->insert_id;
    $mysqli->close();
    return $consulta;
  }

  function cons_fetch($sql) {
    $rsconsulta = $this->consulta($sql);
    $consulta = mysqli_fetch_array($rsconsulta);
    return $consulta;
  }

  function fetch($cursor) {
    $ret = mysqli_fetch_array($cursor);
    return $ret;
  }

  function qtd_linhas($sql) {
    $mysqli = $this->conectar();
    $consulta = $mysqli->query($sql);
    $qtd = mysqli_num_rows($consulta);
    $mysqli->close();
    return $qtd;
  }
  
  function ultimo_id() {
    return $this->ultimoid;
  }

  function transacao_inicia() {
    $conexao = $this->conectar();
    mysqli_autocommit($conexao, FALSE);
    return $conexao;
  }
  
  function transacao_consulta($conexao,$sql) {
    $consulta = $conexao->query($sql);
    return $consulta;
  }

  function transacao_operacao($conexao,$sql) {
    $consulta = $conexao->query($sql);
    $this->ultimoid = $conexao->insert_id;
    return $consulta;
  }

  function transacao_cons_fetch($conexao,$sql) {
    $cursor = $this->transacao_consulta($conexao,$sql);
    $ret = mysqli_fetch_array($cursor);
    return $ret;
  }
  
  function transacao_fetch($cursor) {
    $ret = mysqli_fetch_array($cursor);
    return $ret;
  }
  
  function transacao_conclui($conexao) {
    $conexao->close();      
    return;
  }
  
  function transacao_commit($conexao) {
    mysqli_commit($conexao);
    return;
  }

  function transacao_rollback($conexao) {
    mysqli_rollback($conexao);
    return;
  }
  
  function get_post($campo) {
    $this->conectar();
    $valor = $_POST[$campo];
    $ret = mysqli_real_escape_string($this->conexao,$valor);
    //$ret = addslashes($_POST[$campo]);
    return $ret;
  }
  
  function get_string_safe($valor) {
    $ret = mysqli_real_escape_string($this->conexao,$valor);
    return $ret;
  }
  
}

?>
