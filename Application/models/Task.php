<?php

/**
 * Description of Task
 *
 * @author Eduardo Valente
 */
class Task extends Base {

  var $idtask;
  var $idpipeline;
  var $idacao;
  var $ordem;
  var $token;
  var $tarefa;
  var $prioridade;
  var $progresso;
  var $indicador;
  var $dtinicio;
  var $dtentrega;
  var $instrucao;

  var $a_token;
  var $acao;
  var $a_ordem;
  var $a_info;

  var $p_token;
  var $p_ordem;
  var $pipeline;
    
  function __construct() {
    parent::__construct();
  }

  public function listAll($idacao) {
    $lista = Array();
    $qrstr = "SELECT * FROM gesh_pipeline_task WHERE id_acao='$idacao' ORDER BY ordem,dtinicio,dtentrega";
    $rstr = $this->db->consulta($qrstr);
    while ($item = $this->db->fetch($rstr)) {
      $obj = new Task();
      $obj->loadItem_by_Id($item["id_task"]);
      $lista[] = $obj;
    }
    return $lista;
  }

  public function loadNew($token) {
    $qrstr = "SELECT * FROM gesh_pipeline_acao WHERE token='$token'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idacao = $item["id_acao"];
    $this->idpipeline = $item["id_pipeline"];
    $this->a_token = $item["token"];
    $this->acao = $item["acao"];
    $this->a_ordem = $item["ordem"];
    $this->a_info = $item["info"];
    $this->loadItemPipeline();
    //
    $this->tarefa = 'New Task';
    //
  }
  
  public function loadItem($token) {
    $qrstr = "SELECT * FROM gesh_pipeline_task WHERE token='$token'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idtask = $item["id_task"];
    $this->idacao = $item["id_acao"];
    $this->idpipeline = $item["id_pipeline"];
    $this->ordem = $item["ordem"];
    $this->token = $item["token"];
    $this->tarefa = $item["tarefa"];
    $this->instrucao = $item["instrucao"];
    $this->dtinicio = $item["dtinicio"];
    $this->dtentrega = $item["dtentrega"];
    $this->progresso = $item["progresso"];
    $this->indicador = $item["indicador"];
    $this->prioridade = $item["prioridade"];
    //    
    $this->loadItemAction();
    $this->loadItemPipeline();
  }  

  public function loadItem_by_Id($id) {
    $qrstr = "SELECT * FROM gesh_pipeline_task WHERE id_task='$id'";
    $item = $this->db->cons_fetch($qrstr);
    $this->idtask = $item["id_task"];
    $this->idacao = $item["id_acao"];
    $this->idpipeline = $item["id_pipeline"];
    $this->ordem = $item["ordem"];
    $this->token = $item["token"];
    $this->tarefa = $item["tarefa"];
    $this->instrucao = $item["instrucao"];
    $this->dtinicio = $item["dtinicio"];
    $this->dtentrega = $item["dtentrega"];
    $this->progresso = $item["progresso"];
    $this->indicador = $item["indicador"];
    $this->prioridade = $item["prioridade"];
    //    
    $this->loadItemAction();
    $this->loadItemPipeline();
  }  

  public function loadItemAction() {
    $qrstr = "SELECT * FROM gesh_pipeline_acao WHERE id_acao='$this->idacao'";
    $item = $this->db->cons_fetch($qrstr);
    $this->a_token = $item["token"];
    $this->acao = $item["acao"];
    $this->a_ordem = $item["ordem"];
    $this->a_info = $item["info"];
  }  

  public function loadItemPipeline() {
    $qrstr = "SELECT * FROM gesh_pipeline WHERE id_pipeline='$this->idpipeline'";
    $item = $this->db->cons_fetch($qrstr);
    $this->p_token = $item["token"];
    $this->p_ordem = $item["ordem"];
    $this->pipeline = $item["pipeline"];
  }  
  
  public function save() {
    $qrstr = "SELECT MAX(ordem) AS ordem FROM gesh_pipeline_task WHERE id_acao='$this->idacao' ";
    $item = $this->db->cons_fetch($qrstr);
    $this->ordem = $item["ordem"] + 1;
    //
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "INSERT INTO gesh_pipeline_task(id_pipeline,id_acao,ordem,tarefa,dtinicio,dtentrega,prioridade,instrucao ) 
                  VALUES ('$this->idpipeline','$this->idacao','$this->ordem','$this->tarefa','$this->dtinicio','$this->dtentrega','$this->prioridade','$this->instrucao')";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $this->idtask = $this->db->ultimoid;
      $this->token = strtoupper(md5($this->idtask));
      $qsubmit = "UPDATE gesh_pipeline_task SET token='$this->token' WHERE id_task='$this->idtask'";
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
    return $id;
  }

  public function update() {
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "UPDATE gesh_pipeline_task
                    SET tarefa='$this->tarefa',dtinicio='$this->dtinicio',dtentrega='$this->dtentrega',
                        prioridade='$this->prioridade',instrucao='$this->instrucao'
                    WHERE id_task='$this->idtask'";
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
      $qsubmit = "DELETE FROM gesh_pipeline_task WHERE id_task='$this->idtask'";
      $this->db->transacao_operacao($conexao,$qsubmit);
      $qsubmit = "UPDATE gesh_pipeline_task SET ordem=ordem-1 WHERE id_acao=$this->idacao AND ordem>$this->ordem";
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

  public function progresso() {
    if ( $this->progresso == 100 ) $this->indicador = 7;
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "UPDATE gesh_pipeline_task
                    SET dtinicio='$this->dtinicio',dtentrega='$this->dtentrega',prioridade='$this->prioridade',
                        progresso='$this->progresso',indicador='$this->indicador'
                    WHERE id_task='$this->idtask'";
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
      $qsubmit = "UPDATE gesh_pipeline_task SET ordem='$ordem' WHERE id_acao='$this->idacao' AND ordem='$nova_ordem'";
      $this->db->transacao_operacao($conexao,$qsubmit);      
      $qsubmit = "UPDATE gesh_pipeline_task SET ordem='$nova_ordem' WHERE id_task='$this->idtask'";
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

  public function down() {
    $ordem = $this->ordem;
    $nova_ordem = $ordem + 1;
    //
    $conexao = $this->db->transacao_inicia();
    try {
      $qsubmit = "UPDATE gesh_pipeline_task SET ordem='$ordem' WHERE id_acao='$this->idacao' AND ordem='$nova_ordem'";
      $this->db->transacao_operacao($conexao,$qsubmit);      
      $qsubmit = "UPDATE gesh_pipeline_task SET ordem='$nova_ordem' WHERE id_task='$this->idtask'";
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
