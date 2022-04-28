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
  
  function tarefa_ficha($obj) {    
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
    $exibe .= $this->url->dlg_question('exclui_bt','Confirma a exclusão da tarefa ?',
                                       $this->url->get_href_int($this->route,'exclusao',$token));
    //
    $link1 = $this->url->get_link_int('My Tasks');
    $link2 = $this->url->get_link_int($pipeline,'pipeline','ficha',$p_token);
    $link3 = $this->url->get_link_int($acao,'action','ficha',$a_token);
    $breadcrumb = $this->url->breadcrumb(4,$tarefa,$link1,$link2,$link3);
    //
    $vetor = array();
    $vetor[] = $this->url->dlg_button('exclui_bt','excluir'); 
    $vetor[] = $this->url->get_link_int('Editar',$this->route,'editar',$token);
    //$exibe .= $this->html->filtro_header($pipeline,'',$vetor,$acao,$breadcrumb);
    $exibe .= $this->html->filtro_header('Pipeline &rarr; Ação &rarr; Tarefa',$o_pipeline.'. '.$pipeline,$vetor,
                                         $o_pipeline.'.'.$o_acao.'. '.$acao,$breadcrumb);
    //
    $exibe .= $this->html->ficha_inicio();
    $vetor = Array();
    $vetor[] = 'Pipeline¦'.$pipeline.'¦40';
    $vetor[] = 'Ação¦'.$acao.'¦40';
    $vetor[] = 'Prioridade¦'.$this->utili->get_prioridade($prioridade).'¦20';
    $exibe .= $this->html->ficha_vetor($vetor);
    $vetor = Array();
    $vetor[] = 'Tarefa¦'.$tarefa.'¦80';
    $vetor[] = 'Início¦'.$this->utili->get_strdata($dtinicio).'¦10';
    $vetor[] = 'Entrega¦'.$this->utili->get_strdata($dtentrega).'¦10';
    $exibe .= $this->html->ficha_vetor($vetor);
    $vetor = Array();
    $vetor[] = 'Instruções¦'.nl2br($instrucao).'¦100';
    $exibe .= $this->html->ficha_vetor($vetor);
    $exibe .= $this->html->ficha_fim();
    return $exibe;
  }
  
  function tarefa_exclui($obj) {
    $obj->remove();
    return $this->url->redirect($this->url->get_href_int('action','ficha',$obj->a_token));
  }

  function tarefa_up($obj) {
    $obj->up($obj);
    return $this->url->redirect($this->url->get_href_int('action','ficha',$obj->a_token));
  }

  function tarefa_down($obj) {
    $obj->down($obj);
    return $this->url->redirect($this->url->get_href_int('action','ficha',$obj->a_token));
  }
  
  function tarefa_nova($obj) {
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
        $msg_erro .= 'Tarefa não informada<br/>';
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
      $titform = 'Tarefa &bull; Novo';
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
      $exibe .= $this->form->data('dtinicio','Início',$obj->dtinicio,10);
      $exibe .= $this->form->data('dtentrega','Entrega',$obj->dtentrega,10);
      $exibe .= $this->form->combo('prioridade','Prioridade',$obj->prioridade,$vet_prioridade,30);
      $exibe .= $this->form->texto('tarefa','Tarefa',$obj->tarefa,100,100);
      $exibe .= $this->form->memo('instrucao','Instruções',$obj->instrucao,10,100);
      
      $botoes = $this->form->botao_cancelar($this->url->get_href_int('action','ficha',$obj->a_token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
  function tarefa_editar($obj) {
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
        $msg_erro .= 'Tarefa não informada<br/>';
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
      $titform = 'Editar';
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
      $exibe .= $this->form->data('dtinicio','Início',$obj->dtinicio,10);
      $exibe .= $this->form->data('dtentrega','Entrega',$obj->dtentrega,10);
      $exibe .= $this->form->combo('prioridade','Prioridade',$obj->prioridade,$vet_prioridade,30);
      $exibe .= $this->form->texto('tarefa','Tarefa',$obj->tarefa,100,100);
      $exibe .= $this->form->memo('instrucao','Instruções',$obj->instrucao,10,100);
      $botoes = $this->form->botao_cancelar($this->url->get_href_int($this->route,'ficha',$obj->token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      return $exibe;
    }
  }
  
  function tarefa_progresso($obj) {
    $exibe_form = empty($_POST);
    if( !$exibe_form ) {
      $progresso = $_POST["progresso"];
      $indicador = $_POST["indicador"];
      $prioridade = $_POST["prioridade"];
      $dtinicio = $this->form->get_formdata("dtinicio");
      $dtentrega = $this->form->get_formdata("dtentrega");
      $this->stproc->proc_tarefa_progresso($idtask,$dtinicio,$dtentrega,$prioridade,$progresso,$indicador);
      return $this->url->redirect($this->url->get_href_int());
    }
    if( $exibe_form ) {
      $titform = 'Progresso da Tarefa';
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
      $exibe .= $this->html->filtro_header('Pipeline &rarr; Ação &rarr; Tarefa',$o_pipeline.'. '.$pipeline,'',
                                           $o_pipeline.'.'.$o_acao.'. '.$acao,$breadcrumb);
      $exibe .= $this->html->ficha_inicio();
      $vetor = Array();
      $vetor[] = 'Pipeline¦'.$pipeline.'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $vetor = Array();
      $vetor[] = 'Ação¦'.$acao.'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $vetor = Array();
      $vetor[] = 'Tarefa¦'.$tarefa.'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $vetor = Array();
      $vetor[] = 'Instruções¦'.nl2br($instrucao).'¦100';
      $exibe .= $this->html->ficha_vetor($vetor);
      $exibe .= $this->html->ficha_fim();
      //
      $linkform = $this->url->get_href_int($this->route,'progresso',$token);
      $exibe .= $this->html->formdiv_inicio();
      $exibe .= $this->form->inicio($linkform);
      $exibe .= $this->form->data('dtinicio','Início',$dtinicio,10);
      $exibe .= $this->form->data('dtentrega','Entrega',$dtentrega,10);
      $exibe .= $this->form->combo('prioridade','Prioridade',$prioridade,$vet_prioridade,30);
      $exibe .= $this->form->combo('progresso','Porcentagem Executada',$progresso,$vet_progresso,25);
      $exibe .= $this->form->combo('indicador','Viés de Entrega',$indicador,$vet_vies,25);
      //
      $botoes = $this->form->botao_cancelar($this->url->get_href_int($this->route,'ficha',$token));
      $botoes .= $this->form->botao_ok();
      $exibe .= $this->form->fim($botoes);
      $exibe .= $this->html->formdiv_fim();
      $exibe .= '</div>
        ';
      return $exibe;
    }
  }
  
}
