<?php

/**
 * Description of PipelineView
 *
 * @author Eduardo Valente
 */
class PipelineView {

  var $url;
  var $html;
  var $utili;
  var $form;
  var $route = 'pipeline';  

  function __construct() {
    $this->url = new url;
    $this->html = new html;
    $this->utili = new utili;
    $this->form = new form;
  }
  
  function lista($obj) {
    $lista = $obj->listAll();
    //
    $lista_pipe = '';
    $q_pipeline = 0;
    foreach( $lista as $pipe ) {
      $q_pipeline++;
      $idpipeline = $pipe->idpipeline;
      $p_token = $pipe->token;
      $o_pipeline = $pipe->ordem;
      $info = $pipe->info;
      $q_acao = $pipe->q_acao;
      $q_task = $pipe->q_task;
      $max_ordem = $pipe->max_ordem;
      $pipeline = $this->url->get_link_int($pipe->pipeline,$this->route,'ficha',$p_token);
      //
      $bloco = '<table style="width:100%; border: 0;">
        <tr>'.$this->utili->get_schedule_title().'</tr>
        </table>
        ';
      $lista_pipe .= '<tr><td class=pipeline colspan=3 width=65% >'.$o_pipeline.' &bull; '.$pipeline.'</td><td class=pipeline width=35%>'.$bloco.'</td></tr>
          ';
      foreach( $pipe->action_list as $action ) {
        $idacao = $action->id_acao;
        $o_acao = $action->ordem;
        $a_token = $action->token;
        $acao = $this->url->get_link_int($action->acao,'action','ficha',$a_token);
        $info = $action->info;
        $q_task = $action->q_tarefa;
        //
        if ( $q_task == 0 ) {
          $lista_pipe .= '<tr><td class=acao width=18% >'.$o_pipeline.'.'.$o_acao.' &bull; '.$acao.'</td><td class=tarefa width=82% colspan=3>&nbsp;</td></tr>
            ';
        } else {
          $lista_pipe .= '<tr><td class=acao width=18% rowspan='.$q_task.' >'.$o_pipeline.'.'.$o_acao.' &bull; '.$acao.'</td>
            ';
          foreach( $action->task_list as $task ) {
            $idtask = $task->id_task;
            $o_task = $task->ordem;
            $t_token = $task->token;
            $instrucao = $task->instrucao;
            $dtinicio = $task->dtinicio;
            $dtentrega = $task->dtentrega;
            $progresso = $task->progresso;
            $indicador = $task->indicador;
            $prioridade = $task->prioridade;
            $tarefa = $this->url->get_link_int($o_pipeline.'.'.$o_acao.'.'.$o_task.' &bull; '.$task->tarefa,'task','ficha',$t_token,'Ficha da tarefa');
          
            $dtentrega_str = $this->utili->get_strdata($dtentrega);
            if ( empty($dtentrega_str) ) $dtentrega_str = 'N/I';
            $cor_prioridade = $this->utili->get_prioridade_cor($prioridade);
            $prioridade_str = $this->utili->get_prioridade($prioridade);
            $prioridade_str = '<span class=prioridade_tag style="border: solid 1px '.$cor_prioridade.';">'.$prioridade_str.'</span>';
            $progresso_str = $this->utili->get_str_progresso($dtinicio,$progresso,$indicador);
            $bloco = '<table style="width:100%; border: 0;">
              <tr>
              ';
            $bloco .= $this->utili->get_schedule($dtinicio,$dtentrega,$prioridade);
            $bloco .= '</tr>
              <tr><td style="border:0; padding: 5px 0 0 0;" colspan=20>'.$progresso_str.'</td></tr>
              </table>
              ';
            $icone = '<icone class="icon fa-bolt" style="background: '.$cor_prioridade.'; margin:0; display: block; float: right; "></icone>';
            if ( $progresso < 100 ) $icone = $this->url->get_link_int($icone,'task','progresso',$t_token,'Informar Progresso');
            $lista_pipe .= '<td width=35% class=tarefa><b>'.$tarefa.'</b>'.'</td>'.
              '<td width=12% class=tarefa><b>'.$dtentrega_str.$icone.'</b><br/>'.$prioridade_str.'</td>'.
              '<td width=35% class=dia>'.$bloco.'</td></tr>
              ';
          }
        }
      }
    }
    //
    $etiq_header = 'Pipelines';
    $link1 = $this->url->get_link_int('My Tasks');
    $breadcrumb = $this->url->breadcrumb(2,$etiq_header,$link1);
    //
    $exibe = '';
    $vetor = array();
    $vetor[] = $this->url->get_link_int('novo pipeline',$this->route,'novo');
    $exibe .= $this->html->filtro_header($etiq_header,'',$vetor,'',$breadcrumb);
    $exibe .= '<div id=dashboard>
      <div id=fullpanel style="height: calc( 80vh - 150px );"><table class=listapipe>
      ';
    if ( $q_pipeline > 0 ) {
      $exibe .= $lista_pipe;
    } else {
      $exibe .= '<span class=msg_info>CREATE YOUR FIRST PIPELINE!</span>
       ';
    }
    $exibe .= '</table></div>
      </div>
      ';
    return $exibe;
  }
  
  function ficha($obj) {
    $id = $obj->idpipeline;
    $token = $obj->token;
    $o_pipeline = $obj->ordem;
    $pipeline = $obj->pipeline;
    $info = $obj->info;
    $q_acao = $obj->q_acao;
    $max_ordem = $obj->max_ordem;
    //
    $exibe = '';
    if ( $q_acao == 0 ) {
      $exibe .= $this->url->dlg_question('exclui_bt','Confirm to delete the pipeline ?',
                                         $this->url->get_href_int($this->route,'exclusao',$token));
    }
    $link1 = $this->url->get_link_int('My Tasks');
    $breadcrumb = $this->url->breadcrumb(2,$pipeline,$link1);
    //
    $vetor = array();
    $vetor[] = $this->url->get_link_int('Edit',$this->route,'editar',$token);
    if ( $q_acao == 0 ) $vetor[] = $this->url->dlg_button('exclui_bt','Delete'); 
    $exibe .= $this->html->filtro_header('Pipeline','',$vetor,$o_pipeline.'. '.$pipeline,$breadcrumb);
    //
    if ( $q_acao == 0 ) $q_acao = 'None';
    //
    $exibe .= $this->html->ficha_inicio();
    $vetor = Array();
    $vetor[] = 'Pipeline¦'.$pipeline.'¦90';
    $vetor[] = 'Actions¦'.$q_acao.'¦10';
    $exibe .= $this->html->ficha_vetor($vetor);
    $vetor = Array();
    $vetor[] = 'Info¦'.nl2br($info).'¦100';
    $exibe .= $this->html->ficha_vetor($vetor);
    $exibe .= $this->html->ficha_fim();
    //
    $vetor = array();
    $vetor[] = $this->url->get_link_int('new action','action','novo',$token);
    $exibe .= $this->html->filtro_divisor('Actions',$vetor);
    $colunas = array();
    $colunas[] = 'Action¦85¦false';
    $colunas[] = 'Tasks¦10¦false';
    $colunas[] = 'Options¦5¦false';
    $exibe .= $this->html->scroll_inicio($colunas,'30vh');
    foreach( $obj->action_list as $action ) {
      $idacao = $action->id_acao;
      $ordem = $action->ordem;
      $a_token = $action->token;
      $info = $action->info;
      $q_tarefa = $action->q_tarefa;
      $acao = $this->url->get_link_int($action->acao,'action','ficha',$a_token);
      //
      $icones = '';
      if ( $action->ordem == $max_ordem ) {
        $ico_down = $this->html->icone('DWOFF'); 
      } else {
        $ico_down = $this->url->get_link_int($this->html->icone('DW'),'action','down',$a_token);
      }
      if ( $action->ordem == 1 ) {
        $ico_up = $this->html->icone('UPOFF'); 
      } else {
        $ico_up = $this->url->get_link_int($this->html->icone('UP'),'action','up',$a_token);
      }
      $icones = $ico_up.$ico_down;
      //
      $vetor = Array();
      $vetor[] = $o_pipeline.'.'.$action->ordem.' &bull; '.$acao.'¦left';
      $vetor[] = $q_tarefa.'¦left';
      $vetor[] = $icones.'¦left';
      $exibe .= $this->html->scroll_linha($vetor);      
    }
    $exibe .= $this->html->scroll_fim(); 
    
    //
    return $exibe;
  }
  
  function exclui($obj) {
    $obj->remove();
    return $this->url->redirect($this->url->get_href_int());
  }

  function novo($obj) {
    $exibe_form = empty($_POST);
    $msg_erro = '';
    if( !$exibe_form ) {
      $obj->pipeline = $obj->get_post("pipeline");
      $obj->info = $obj->get_post("info");
      $dados_ok = true;
      if ( empty($obj->pipeline) ) {
        $msg_erro .= 'Pipeline name is required';
        $dados_ok = false;
      }
      if ( $dados_ok ) {
        $obj->save();
        return $this->url->redirect($this->url->get_href_int());
      } else {
        $exibe_form = true;
      }
    }
    if( $exibe_form ) {
      $exibe = '';
      $titform = 'New';
      //
      $link1 = $this->url->get_link_int('My Tasks');
      $breadcrumb = $this->url->breadcrumb(2,$titform,$link1);
      $exibe .= $this->html->filtro_header('Pipelines','','',$titform,$breadcrumb);
      $exibe .= $this->html->formdiv_inicio();
      $linkform = $this->url->get_href_int($this->route,'novo');
      $exibe .= $this->form->inicio($linkform,$msg_erro);
      $exibe .= $this->form->texto('pipeline','Pipeline',$obj->pipeline,100,100);
      $exibe .= $this->form->memo('info','Info',$obj->info,10,100);
      $botoes = $this->form->botao_cancelar($this->url->get_href_int());
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
      $obj->pipeline = $obj->get_post("pipeline");
      $obj->info = $obj->get_post("info");
      $dados_ok = true;
      if ( empty($obj->pipeline) ) {
        $msg_erro .= 'Pipeline name is required';
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
      $exibe = '';
      $link1 = $this->url->get_link_int('My Tasks');
      $link2 = $this->url->get_link_int($obj->pipeline,$this->route,'ficha',$obj->token);
      $breadcrumb = $this->url->breadcrumb(3,$titform,$link1,$link2);
      $exibe .= $this->html->filtro_header('Pipelines',$titform,'',$obj->ordem.'. '.$obj->pipeline,$breadcrumb);
      $exibe .= $this->html->formdiv_inicio();
      $linkform = $this->url->get_href_int($this->route,'editar',$obj->token);
      $exibe .= $this->form->inicio($linkform,$msg_erro);
      $exibe .= $this->form->texto('pipeline','Pipeline',$obj->pipeline,100,100);
      $exibe .= $this->form->memo('info','Info',$obj->info,10,100);
      $botoes = $this->form->botao_cancelar($this->url->get_href_int($this->route,'ficha',$obj->token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
}
