<?php

/**
 * Description of ActionView
 *
 * @author Eduardo Valente
 */
class ActionView {

  var $url;
  var $html;
  var $utili;
  var $form;
  var $route = 'action';  

  function __construct() {
    $this->html = new html;
    $this->utili = new utili;
    $this->url = new url;
    $this->form = new form;
  }
  
  function ficha($obj) {
    $idacao = $obj->id_acao;
    $idpipeline = $obj->id_pipeline;
    $o_acao = $obj->ordem;
    $token = $obj->token;
    $acao = $obj->acao;
    $info = $obj->info;
    $o_pipeline = $obj->p_ordem;
    $p_token = $obj->p_token;
    $pipeline = $obj->pipeline;
    $q_tarefa = $obj->q_tarefa;
    $max_ordem = $obj->max_ordem;
    //
    $exibe = '';
    if ( $q_tarefa == 0 ) {
      $exibe .= $this->url->dlg_question('exclui_bt','Confirm to delete the action ?',
                                         $this->url->get_href_int($this->route,'exclusao',$token));
    }
    //
    $link1 = $this->url->get_link_int('My Tasks');
    $link2 = $this->url->get_link_int($pipeline,'pipeline','ficha',$p_token);
    $breadcrumb = $this->url->breadcrumb(3,$acao,$link1,$link2);
    //
    $vetor = array();
    $vetor[] = $this->url->get_link_int('Edit',$this->route,'editar',$token);
    if ( $q_tarefa == 0 ) $vetor[] = $this->url->dlg_button('exclui_bt','delete'); 
    $exibe .= $this->html->filtro_header('Pipeline &rarr; Action',$o_pipeline.'. '.$pipeline,$vetor,$o_pipeline.'.'.$o_acao.'. '.$acao,$breadcrumb);
    //
    if ( $q_tarefa == 0 ) $q_tarefa = 'None';
    $exibe .= $this->html->ficha_inicio();
    $vetor = Array();
    $vetor[] = 'Pipeline¦'.$pipeline.'¦45';
    $vetor[] = 'Action¦'.$acao.'¦45';
    $vetor[] = 'Tasks¦'.$q_tarefa.'¦10';
    $exibe .= $this->html->ficha_vetor($vetor);
    $vetor = Array();
    $vetor[] = 'Info¦'.nl2br($info).'¦100';
    $exibe .= $this->html->ficha_vetor($vetor);
    $exibe .= $this->html->ficha_fim();
    
    $vetor = array();
    $vetor[] = $this->url->get_link_int('new task','task','novo',$token);
    $exibe .= $this->html->filtro_divisor('Tasks',$vetor);

    $colunas = array();
    $colunas[] = 'Task¦55¦false';
    $colunas[] = 'Due Date¦10¦false';
    $colunas[] = 'Priority¦20¦false';
    $colunas[] = 'Progress¦10¦false';
    $colunas[] = 'Options¦5¦false';
    $exibe .= $this->html->scroll_inicio($colunas,'50vh');
    foreach( $obj->task_list as $task ) {
      $idtask = $task->id_task;
      $o_task = $task->ordem;
      $t_token = $task->token;
      $tarefa = $task->tarefa;
      $instrucao = $task->instrucao;
      $dtinicio = $task->dtinicio;
      $dtentrega = $this->utili->get_strdata($task->dtentrega);
      $progresso = $task->progresso;
      $indicador = $task->indicador;
      $prioridade = $task->prioridade;
      $tarefa = $this->url->get_link_int($o_pipeline.'.'.$o_acao.'.'.$o_task.' &bull; '.$tarefa,'task','ficha',$t_token);
      if ( empty($dtentrega) ) $dtentrega = 'N/I';
      $icones = '';
      if ( $o_task == $max_ordem ) {
        $ico_down = $this->html->icone('DWOFF'); 
      } else {
        $ico_down = $this->url->get_link_int($this->html->icone('DW'),'task','down',$t_token);
      }
      if ( $o_task == 1 ) {
        $ico_up = $this->html->icone('UPOFF'); 
      } else {
        $ico_up = $this->url->get_link_int($this->html->icone('UP'),'task','up',$t_token);
      }
      $icones = $ico_up.$ico_down;
      //
      $vetor = Array();
      $vetor[] = $tarefa.'¦left';
      $vetor[] = $dtentrega.'¦left';
      $vetor[] = $this->utili->get_prioridade($prioridade).'¦left';
      $vetor[] = $progresso.'¦left';
      $vetor[] = $icones.'¦center';
      $exibe .= $this->html->scroll_linha($vetor);      
    }
    $exibe .= $this->html->scroll_fim();
    return $exibe;
  }
  
  function exclui($obj) {
    $obj->remove();
    return $this->url->redirect($this->url->get_href_int('pipeline','ficha',$obj->p_token));
  }

  function up($obj) {
    $obj->up();
    return $this->url->redirect($this->url->get_href_int('pipeline','ficha',$obj->p_token));
  }

  function down($obj) {
    $obj->down();
    return $this->url->redirect($this->url->get_href_int('pipeline','ficha',$obj->p_token));
  }
  
  function nova($obj) {
    $exibe_form = empty($_POST);
    $msg_erro = '';
    if( !$exibe_form ) {
      $obj->acao = $obj->get_post("acao");
      $obj->info = $obj->get_post("info");
      $dados_ok = true;
      if ( empty($obj->acao) ) {
        $msg_erro .= 'Action title is required';
        $dados_ok = false;
      }
      if ( $dados_ok ) {
        $obj->save();
        return $this->url->redirect($this->url->get_href_int('pipeline','ficha',$obj->p_token));
      } else {
        $exibe_form = true;
      }
    }
    if( $exibe_form ) {
      $exibe = '';
      $titform = 'Action &bull; New';
      //
      $link1 = $this->url->get_link_int('My Tasks');
      $link2 = $this->url->get_link_int($obj->pipeline,'pipeline','ficha',$obj->p_token);
      $breadcrumb = $this->url->breadcrumb(3,$titform,$link1,$link2);
      // monta vetores
      $linkform = $this->url->get_href_int($this->route,'novo',$obj->p_token);
      $exibe .= $this->html->filtro_header($obj->pipeline,'','',$titform,$breadcrumb);
      $exibe .= $this->html->formdiv_inicio();
      $exibe .= $this->form->inicio($linkform,$msg_erro);
      $exibe .= $this->form->texto('acao','Action',$obj->acao,100,100);
      $exibe .= $this->form->memo('info','Info',$obj->info,10,100);
      $botoes = $this->form->botao_cancelar($this->url->get_href_int('pipeline','ficha',$obj->p_token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
  function editar($obj) { 
    $exibe_form = empty($_POST);
    $msg_erro = '';
    if( !$exibe_form ) {
      $obj->acao = $obj->get_post("acao");
      $obj->info = $obj->get_post("info");
      $dados_ok = true;
      if ( empty($obj->acao) ) {
        $msg_erro .= 'Action title is required';
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
      $titform = 'Action &bull; Edit';
      //
      $link1 = $this->url->get_link_int('My Tasks');
      $link2 = $this->url->get_link_int($obj->pipeline,'pipeline','ficha',$obj->p_token);
      $link3 = $this->url->get_link_int($obj->acao,$this->route,'ficha',$obj->token);
      $breadcrumb = $this->url->breadcrumb(4,$titform,$link1,$link2,$link3);
      //
      $exibe = '';
      $exibe .= $this->html->filtro_header($obj->pipeline,'','',$titform,$breadcrumb);
      $exibe .= $this->html->formdiv_inicio();
      $linkform = $this->url->get_href_int($this->route,'editar',$obj->token);
      $exibe .= $this->form->inicio($linkform,$msg_erro);
      $exibe .= $this->form->texto('acao','Action',$obj->acao,100,100);
      $exibe .= $this->form->memo('info','Info',$obj->info,10,100);
      $botoes = $this->form->botao_cancelar($this->url->get_href_int($this->route,'ficha',$obj->token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
}
