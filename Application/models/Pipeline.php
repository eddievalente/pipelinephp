<?php

/**
 * Description of Pipeline
 *
 * @author Eduardo Valente
 */
class Pipeline extends Base {

  var $idpipeline;
  var $ordem;
  var $token;
  var $pipeline;
  var $info;
  
  var $q_acao;
  var $q_task;
  var $max_ordem;
  
  var $action_list = Array();
   
  function __construct() {
    parent::__construct();
  }

  public function listAll() {
    $lista = Array();
    $qrstr = "SELECT * FROM gesh_pipeline ORDER BY ordem,pipeline";
    $rstr = $this->db->consulta($qrstr);
    while ($item = $this->db->fetch($rstr)) {
      $obj = new Pipeline();
      $obj->loadItem_by_Id($item["id_pipeline"]);
      $lista[] = $obj;
    }
    return $lista;
  }
  
  public function loadNew() {
    $this->idpipeline = 0;
    $this->ordem = 0;
    $this->token = '';
    $this->pipeline = 'New Pipeline';
    $this->info = '';
  }
  
  public function loadItem($token) {
    $qrstr = "SELECT * FROM gesh_pipeline WHERE token='$token'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idpipeline = $item["id_pipeline"];
    $this->ordem = $item["ordem"];
    $this->token = $item["token"];
    $this->pipeline = $item["pipeline"];
    $this->info = $item["info"];
    //
    $this->loadActionList();
  }

  public function loadItem_by_Id($id) {
    $qrstr = "SELECT * FROM gesh_pipeline WHERE id_pipeline='$id'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idpipeline = $item["id_pipeline"];
    $this->ordem = $item["ordem"];
    $this->token = $item["token"];
    $this->pipeline = $item["pipeline"];
    $this->info = $item["info"];
    //
    $this->loadActionList();
  }
  
  private function loadActionList() {
    $qrstrq = "SELECT COUNT(*) AS qtd,MAX(ordem) AS max_ordem FROM gesh_pipeline_acao WHERE id_pipeline='$this->idpipeline'";
    $q_item = $this->db->cons_fetch($qrstrq);
    $this->q_acao = $q_item["qtd"];
    $this->max_ordem = $q_item["max_ordem"];
    //
    $qrstrq = "SELECT COUNT(*) AS qtd FROM gesh_pipeline_task WHERE id_pipeline='$this->idpipeline'";
    $q_item = $this->db->cons_fetch($qrstrq);
    $this->q_task = $q_item["qtd"];
    //
    $action = New Action();
    $this->action_list = $action->listAll($this->idpipeline);
  }
        
  public function save() {
    $qrstr = "SELECT MAX(ordem) AS ordem FROM gesh_pipeline ";
    $item = $this->db->cons_fetch($qrstr);
    $this->ordem = $item["ordem"] + 1;
    //
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "INSERT INTO gesh_pipeline(ordem,pipeline,info) 
                  VALUES ('$this->ordem','$this->pipeline','$this->info')";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $this->idpipeline = $this->db->ultimoid;
      $this->token = strtoupper(md5($this->idpipeline));
      $qsubmit = "UPDATE gesh_pipeline SET token='$this->token' WHERE id_pipeline='$this->idpipeline'";
      $this->db->transacao_operacao($conexao,$qsubmit);      
      $this->db->transacao_commit($conexao);
      $this->msg = '';
      $this->coderro = 0;
    } catch (Throwable $e) {
      $id = 0;
      $this->msg = $e->getMessage();
      $this->db->transacao_rollback($conexao);
    }
    $this->db->transacao_conclui($conexao);
  }

  public function update() {
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "UPDATE gesh_pipeline
                    SET pipeline='$this->pipeline',info='$this->info'
                    WHERE id_pipeline='$this->idpipeline'";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $this->db->transacao_commit($conexao);
      $this->msg = '';
      $this->coderro = 0;
    } catch (Throwable $e) {
      $this->msg = $e->getMessage();
      $this->db->transacao_rollback($conexao);
    }
    $this->db->transacao_conclui($conexao);  
  }

  public function remove() {
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "DELETE FROM gesh_pipeline WHERE id_pipeline='$this->idpipeline'";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $qsubmit = "UPDATE gesh_pipeline SET ordem=ordem-1 WHERE ordem>$this->ordem";
      $this->db->transacao_operacao($conexao,$qsubmit);      
      $this->db->transacao_commit($conexao);
      $this->msg = '';
      $this->coderro = 0;
    } catch (Throwable $e) {
      $this->msg = $e->getMessage();
      $this->db->transacao_rollback($conexao);
    }
    $this->db->transacao_conclui($conexao);  
  }
  
}
