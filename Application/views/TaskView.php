<?php


/**
 * Description of TaskView
 *
 * @author Eduardo Valente
 */
class TaskView {

  var $url;
  var $html;
  var $utili;
  var $form;
  var $route = 'task';  

  function __construct() {
    $this->html = new html;
    $this->utili = new utili;
    $this->url = new url;
    $this->form = new form;
  }
  
  function ficha($obj) {    
    $idtask = $obj->id_task;
    $idacao = $obj->id_acao;
    $o_task = $obj->ordem;
    $token = $obj->token;
    $tarefa = $obj->tarefa;
    $instrucao = $obj->instrucao;
    $dtinicio = $obj->dtinicio;
    $dtentrega = $obj->dtentrega;
    $progresso = $obj->progresso;
    $indicador = $obj->indicador;
    $prioridade = $obj->prioridade;
    //    
    $a_token = $obj->a_token;
    $idpipeline = $obj->id_pipeline;
    $acao = $obj->acao;
    $o_acao = $obj->a_ordem;
    $info = $obj->a_info;
    //
    $p_token = $obj->p_token;
    $o_pipeline = $obj->p_ordem;
    $pipeline = $obj->pipeline;
    //
    $exibe = '';    
    $exibe .= $this->url->dlg_question('exclui_bt','Confirm to delete the task ?',
                                       $this->url->get_href_int($this->route,'exclusao',$token));
    //
    $link1 = $this->url->get_link_int('My Tasks');
    $link2 = $this->url->get_link_int($pipeline,'pipeline','ficha',$p_token);
    $link3 = $this->url->get_link_int($acao,'action','ficha',$a_token);
    $breadcrumb = $this->url->breadcrumb(4,$tarefa,$link1,$link2,$link3);
    //
    $vetor = array();
    $vetor[] = $this->url->dlg_button('exclui_bt','Delete'); 
    $vetor[] = $this->url->get_link_int('Edit',$this->route,'editar',$token);
    //$exibe .= $this->html->filtro_header($pipeline,'',$vetor,$acao,$breadcrumb);
    $exibe .= $this->html->filtro_header('Pipeline &rarr; Action &rarr; Task',$o_pipeline.'. '.$pipeline,$vetor,
                                         $o_pipeline.'.'.$o_acao.'. '.$acao,$breadcrumb);
    //
    $exibe .= $this->html->ficha_inicio();
    $vetor = Array();
    $vetor[] = 'Pipeline¦'.$pipeline.'¦40';
    $vetor[] = 'Action¦'.$acao.'¦40';
    $vetor[] = 'Priority¦'.$this->utili->get_prioridade($prioridade).'¦20';
    $exibe .= $this->html->ficha_vetor($vetor);
    $vetor = Array();
    $vetor[] = 'Task¦'.$tarefa.'¦80';
    $vetor[] = 'Start Date¦'.$this->utili->get_strdata($dtinicio).'¦10';
    $vetor[] = 'Due Date¦'.$this->utili->get_strdata($dtentrega).'¦10';
    $exibe .= $this->html->ficha_vetor($vetor);
    $vetor = Array();
    $vetor[] = 'Instructions¦'.nl2br($instrucao).'¦100';
    $exibe .= $this->html->ficha_vetor($vetor);
    $exibe .= $this->html->ficha_fim();
    return $exibe;
  }
  
  function exclui($obj) {
    $obj->remove();
    return $this->url->redirect($this->url->get_href_int('action','ficha',$obj->a_token));
  }

  function up($obj) {
    $obj->up($obj);
    return $this->url->redirect($this->url->get_href_int('action','ficha',$obj->a_token));
  }

  function down($obj) {
    $obj->down($obj);
    return $this->url->redirect($this->url->get_href_int('action','ficha',$obj->a_token));
  }
  
  function nova($obj) {
    $exibe_form = empty($_POST);
    $msg_erro = '';
    if( !$exibe_form ) {
      $obj->prioridade = $_POST["prioridade"];
      $obj->tarefa = $obj->get_post("tarefa");
      $obj->instrucao = $obj->get_post("instrucao");
      $obj->dtinicio = $this->form->get_formdata("dtinicio");
      $obj->dtentrega = $this->form->get_formdata("dtentrega");
      $dados_ok = true;
      if ( empty($obj->tarefa) ) {
        $msg_erro .= 'Task name is mandatory';
        $dados_ok = false;
      }
      if ( $dados_ok ) {
        $obj->save();
        return $this->url->redirect($this->url->get_href_int('action','ficha',$obj->a_token));
      } else {
        $exibe_form = true;
      }
    }
    if( $exibe_form ) {
      $titform = 'Task &bull; New';
      // monta vetores
      $vet_prioridade = $this->utili->get_vet_prioridade();
      //
      $link1 = $this->url->get_link_int('My Tasks');
      $link2 = $this->url->get_link_int($obj->pipeline,'pipeline','ficha',$obj->p_token);
      $link3 = $this->url->get_link_int($obj->acao,'action','ficha',$obj->a_token);
      $breadcrumb = $this->url->breadcrumb(4,$titform,$link1,$link2,$link3);
      //
      $exibe = '';
      $exibe .= $this->html->filtro_header($titform,'','',$obj->pipeline,$breadcrumb);
      $exibe .= $this->html->formdiv_inicio();
      $linkform = $this->url->get_href_int($this->route,'novo',$obj->a_token);
      $exibe .= $this->form->inicio($linkform,$msg_erro);
      $exibe .= $this->form->data('dtinicio','Start Date',$obj->dtinicio,10);
      $exibe .= $this->form->data('dtentrega','Due Date',$obj->dtentrega,10);
      $exibe .= $this->form->combo('prioridade','Priority',$obj->prioridade,$vet_prioridade,30);
      $exibe .= $this->form->texto('tarefa','Task',$obj->tarefa,100,100);
      $exibe .= $this->form->memo('instrucao','Instructions',$obj->instrucao,10,100);
      
      $botoes = $this->form->botao_cancelar($this->url->get_href_int('action','ficha',$obj->a_token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
  function editar($obj) {
    $idtask = $obj->id_task;
    $idacao = $obj->id_acao;
    $o_task = $obj->ordem;
    $token = $obj->token;
    $tarefa = $obj->tarefa;
    $instrucao = $obj->instrucao;
    $dtinicio = $obj->dtinicio;
    $dtentrega = $obj->dtentrega;
    $progresso = $obj->progresso;
    $indicador = $obj->indicador;
    $prioridade = $obj->prioridade;
    //    
    $a_token = $obj->a_token;
    $idpipeline = $obj->id_pipeline;
    $acao = $obj->acao;
    $o_acao = $obj->a_ordem;
    $info = $obj->a_info;
    //
    $p_token = $obj->p_token;
    $o_pipeline = $obj->p_ordem;
    $pipeline = $obj->pipeline;
    //
    $exibe_form = empty($_POST);
    $msg_erro = '';
    if( !$exibe_form ) {
      $obj->tarefa = $obj->get_post("tarefa");
      $obj->instrucao = $obj->get_post("instrucao");
      $obj->prioridade = $_POST["prioridade"];
      $obj->dtinicio = $this->form->get_formdata("dtinicio");
      $obj->dtentrega = $this->form->get_formdata("dtentrega");
      $dados_ok = true;
      if ( empty($obj->tarefa) ) {
        $msg_erro .= 'Task name is mandatory';
        $dados_ok = false;
      }
      if ( $dados_ok ) {
        $obj->update();
        return $this->url->redirect($this->url->get_href_int($this->route,'ficha',$obj->token));
      } else {
        $exibe_form = true;
      }
    }
    if( $exibe_form ) {
      $titform = 'Edit';
      // monta vetores
      $vet_prioridade = $this->utili->get_vet_prioridade();
      //
      $link1 = $this->url->get_link_int('My Tasks');
      $link2 = $this->url->get_link_int($obj->pipeline,'pipeline','ficha',$obj->p_token);
      $link3 = $this->url->get_link_int($obj->acao,'action','ficha',$obj->a_token);
      $link4 = $this->url->get_link_int($obj->tarefa,$this->route,'ficha',$obj->token);
      $breadcrumb = $this->url->breadcrumb(5,$titform,$link1,$link2,$link3,$link4);    
      //
      $exibe = '';
      $exibe .= $this->html->filtro_header($titform,'','',$obj->pipeline,$breadcrumb);
      $exibe .= $this->html->formdiv_inicio();
      $linkform = $this->url->get_href_int($this->route,'editar',$obj->token);
      $exibe .= $this->form->inicio($linkform,$msg_erro);   
      $exibe .= $this->form->data('dtinicio','Start Date',$obj->dtinicio,10);
      $exibe .= $this->form->data('dtentrega','Due Date',$obj->dtentrega,10);
      $exibe .= $this->form->combo('prioridade','Priority',$obj->prioridade,$vet_prioridade,30);
      $exibe .= $this->form->texto('tarefa','Task',$obj->tarefa,100,100);
      $exibe .= $this->form->memo('instrucao','Instructions',$obj->instrucao,10,100);
      $botoes = $this->form->botao_cancelar($this->url->get_href_int($this->route,'ficha',$obj->token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
  function progresso($obj) {
    $idtask = $obj->id_task;
    $idacao = $obj->id_acao;
    $o_task = $obj->ordem;
    $token = $obj->token;
    $tarefa = $obj->tarefa;
    $instrucao = $obj->instrucao;
    $dtinicio = $obj->dtinicio;
    $dtentrega = $obj->dtentrega;
    $progresso = $obj->progresso;
    $indicador = $obj->indicador;
    $prioridade = $obj->prioridade;
    //    
    $a_token = $obj->a_token;
    $idpipeline = $obj->id_pipeline;
    $acao = $obj->acao;
    $o_acao = $obj->a_ordem;
    $info = $obj->a_info;
    //
    $p_token = $obj->p_token;
    $o_pipeline = $obj->p_ordem;
    $pipeline = $obj->pipeline;
    //
    $exibe_form = empty($_POST);
    if( !$exibe_form ) {
      $obj->progresso = $_POST["progresso"];
      $obj->indicador = $_POST["indicador"];
      $obj->prioridade = $_POST["prioridade"];
      $obj->dtinicio = $this->form->get_formdata("dtinicio");
      $obj->dtentrega = $this->form->get_formdata("dtentrega");
      $obj->update();
      return $this->url->redirect($this->url->get_href_int($this->route,'ficha',$obj->token));
    }
    if( $exibe_form ) {
      $titform = 'Task Progress';
      // monta vetores
      $vet_prioridade = $this->utili->get_vet_prioridade();
      $vet_progresso = $this->utili->get_vet_progresso();
      $vet_vies = $this->utili->get_vet_progresso_vies();
      //
      $link1 = $this->url->get_link_int('My Tasks');
      $link2 = $this->url->get_link_int($pipeline,'pipeline','ficha',$p_token);
      $link3 = $this->url->get_link_int($acao,'action','ficha',$a_token);
      $link4 = $this->url->get_link_int($tarefa,$this->route,'ficha',$token);
      $breadcrumb = $this->url->breadcrumb(5,$titform,$link1,$link2,$link3,$link4);
      //
      $exibe = '';
      $exibe .= $this->html->filtro_header('Pipeline &rarr; Action &rarr; Task',$o_pipeline.'. '.$pipeline,'',
                                           $o_pipeline.'.'.$o_acao.'. '.$acao,$breadcrumb);
      $exibe .= $this->html->ficha_inicio();
      $vetor = Array();
      $vetor[] = 'Pipeline¦'.$pipeline.'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $vetor = Array();
      $vetor[] = 'Action¦'.$acao.'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $vetor = Array();
      $vetor[] = 'Task¦'.$tarefa.'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $vetor = Array();
      $vetor[] = 'Instructions¦'.nl2br($instrucao).'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $exibe .= $this->html->ficha_fim();
      //
      $linkform = $this->url->get_href_int($this->route,'progresso',$token);
      $exibe .= $this->html->formdiv_inicio();
      $exibe .= $this->form->inicio($linkform);
      $exibe .= $this->form->data('dtinicio','Start Date',$dtinicio,10);
      $exibe .= $this->form->data('dtentrega','Due Date',$dtentrega,10);
      $exibe .= $this->form->combo('prioridade','Priority',$prioridade,$vet_prioridade,30);
      $exibe .= $this->form->combo('progresso','Percent Done',$progresso,$vet_progresso,25);
      $exibe .= $this->form->combo('indicador','Delivery Bias',$indicador,$vet_vies,25);
      //
      $botoes = $this->form->botao_cancelar($this->url->get_href_int($this->route,'ficha',$token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
}
