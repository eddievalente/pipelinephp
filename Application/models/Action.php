<?php

/**
 * Description of Action
 *
 * @author Eduardo Valente
 */
class Action extends Base {

  var $idacao;
  var $idpipeline;
  var $ordem;
  var $token;
  var $acao;
  var $info;
   
  var $p_token;
  var $p_ordem;
  var $pipeline;

  var $q_tarefa;
  var $max_ordem;
  
  var $task_list;
  
  function __construct() {
    parent::__construct();
  }
  
  public function listAll($idpipeline) {
    $lista = Array();
    $qrstr = "SELECT id_acao FROM gesh_pipeline_acao WHERE id_pipeline='$idpipeline' ORDER BY ordem,acao";
    $rstr = $this->db->consulta($qrstr);
    while ($item = $this->db->fetch($rstr)) {
      $action = new Action();
      $action->loadItem_by_Id($item["id_acao"]);
      $lista[] = $action;
    }
    return $lista;
  }

  public function loadNew($token) {
    $qrstr = "SELECT * FROM gesh_pipeline WHERE token='$token'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idpipeline = $item["id_pipeline"];
    $this->p_ordem = $item["ordem"];
    $this->p_token = $item["token"];
    $this->pipeline = $item["pipeline"];
    //
    $this->acao = 'New Action';
    //
  }
  
  public function loadItem($token) {
    $qrstr = "SELECT * FROM gesh_pipeline_acao WHERE token='$token'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idacao = $item["id_acao"];
    $this->idpipeline = $item["id_pipeline"];
    $this->ordem = $item["ordem"];
    $this->token = $item["token"];
    $this->acao = $item["acao"];
    $this->info = $item["info"];
    //
    $this->loadItemPipeline();
    $this->loadItemTaskList();
    //
  }
  
  public function loadItem_by_Id($id) {
    $qrstr = "SELECT * FROM gesh_pipeline_acao WHERE id_acao='$id'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idacao = $item["id_acao"];
    $this->idpipeline = $item["id_pipeline"];
    $this->ordem = $item["ordem"];
    $this->token = $item["token"];
    $this->acao = $item["acao"];
    $this->info = $item["info"];
    //
    $this->loadItemPipeline();
    $this->loadItemTaskList();
    //
  }

  public function loadItemPipeline() {
    $qrstr = "SELECT * FROM gesh_pipeline WHERE id_pipeline='$this->idpipeline'";
    $item = $this->db->cons_fetch($qrstr);
    $this->p_ordem = $item["ordem"];
    $this->p_token = $item["token"];
    $this->pipeline = $item["pipeline"];
  }

  public function loadItemTaskList() {
    $qrstrq = "SELECT COUNT(*) AS qtd,MAX(ordem) AS max_ordem FROM gesh_pipeline_task WHERE id_acao='$this->idacao'";
    $q_item = $this->db->cons_fetch($qrstrq);
    $this->q_tarefa = $q_item["qtd"];
    $this->max_ordem = $q_item["max_ordem"];
    //
    $task = New Task();
    $this->task_list = $task->listAll($this->idacao);
  }
  
  public function save() {
    $qrstr = "SELECT MAX(ordem) AS ordem FROM gesh_pipeline_acao WHERE id_pipeline='$this->idpipeline' ";
    $item = $this->db->cons_fetch($qrstr);
    $this->ordem = $item["ordem"] + 1;
    //
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "INSERT INTO gesh_pipeline_acao(ordem,id_pipeline,acao,info) 
                  VALUES ('$this->ordem','$this->idpipeline','$this->acao','$this->info')";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $this->idacao = $this->db->ultimoid;
      $this->token = strtoupper(md5($this->idacao));
      $qsubmit = "UPDATE gesh_pipeline_acao SET token='$this->token' WHERE id_acao='$this->idacao'";
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
      $qsubmit = "UPDATE gesh_pipeline_acao
                    SET acao='$this->acao',info='$this->info'
                    WHERE id_acao='$this->idacao'";
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
      $qsubmit = "DELETE FROM gesh_pipeline_acao WHERE id_acao='$this->idacao'";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $qsubmit = "UPDATE gesh_pipeline_acao SET ordem=ordem-1 WHERE id_pipeline='$this->idpipeline' AND ordem>'$this->ordem'";
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
  
  public function up() {
    $ordem = $this->ordem;
    $nova_ordem = $ordem - 1;
    //
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "UPDATE gesh_pipeline_acao SET ordem='$ordem' WHERE id_pipeline='$this->idpipeline' AND ordem='$nova_ordem'";
      $this->db->transacao_operacao($conexao,$qsubmit);      
      $qsubmit = "UPDATE gesh_pipeline_acao SET ordem='$nova_ordem' WHERE id_acao='$this->idacao' ";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $this->db->transacao_commit($conexao);
      $this->ordem = $nova_ordem;
      $this->msg = '';
      $this->coderro = 0;
    } catch (Throwable $e) {
      $this->msg = $e->getMessage();
      $this->db->transacao_rollback($conexao);
    }
    $this->db->transacao_conclui($conexao);  
  }

  public function down() {
    $ordem = $this->ordem;
    $nova_ordem = $ordem + 1;
    //
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "UPDATE gesh_pipeline_acao SET ordem='$ordem' WHERE id_pipeline='$this->idpipeline' AND ordem='$nova_ordem'";
      $this->db->transacao_operacao($conexao,$qsubmit);      
      $qsubmit = "UPDATE gesh_pipeline_acao SET ordem='$nova_ordem' WHERE id_acao='$this->idacao' ";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $this->db->transacao_commit($conexao);
      $this->ordem = $nova_ordem;
      $this->msg = '';
      $this->coderro = 0;
    } catch (Throwable $e) {
      $this->msg = $e->getMessage();
      $this->db->transacao_rollback($conexao);
    }
    $this->db->transacao_conclui($conexao);  
  }
  
}
