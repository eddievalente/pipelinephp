<?php

/*
 *  ########  #######  ########  ##     ## 
 *  ##       ##     ## ##     ## ###   ### 
 *  ##       ##     ## ##     ## #### #### 
 *  ######   ##     ## ########  ## ### ## 
 *  ##       ##     ## ##   ##   ##     ## 
 *  ##       ##     ## ##    ##  ##     ## 
 *  ##        #######  ##     ## ##     ## 
 */

class form {
  
  function inicio($acao,$msg_erro='') {
    $nome = 'FRM'.strtoupper( md5( uniqid( rand(), true )));
    $exibe = '<form action="'.$acao.'" method="POST" class="form" enctype="multipart/form-data" id="'.$nome.'"><fieldset>
      ';
    if ( !empty($msg_erro) ) {
      $exibe .= '<span class=msg_erro>'.$msg_erro.'</span>
      ';
    }
    return $exibe;
  }
  
  function fim($botoes='') {
    $exibe = '</fieldset><div class=botoeira>'.$botoes.'</div></form>
      ';
    return $exibe;
  }
  
  function oculto($nome,$valor) {
    $exibe = '<input type="hidden" name="'.$nome.'" id="'.$nome.'" value="'.$valor.'" />
      ';
    return $exibe;
  }

  function get_classe_largura($largura) {
    $ret = '';
    if ( ( $largura > 0 ) && ( $largura <= 5 ) ) $ret=' larg_5';
    elseif ( ( $largura > 5 ) && ( $largura <= 10 ) ) $ret=' larg_10';
    elseif ( ( $largura > 10 ) && ( $largura <= 15 ) ) $ret=' larg_15';
    elseif ( ( $largura > 15 ) && ( $largura <= 20 ) ) $ret=' larg_20';
    elseif ( ( $largura > 20 ) && ( $largura <= 25 ) ) $ret=' larg_25';
    elseif ( ( $largura > 25 ) && ( $largura <= 30 ) ) $ret=' larg_30';
    elseif ( ( $largura > 30 ) && ( $largura <= 35 ) ) $ret=' larg_35';
    elseif ( ( $largura > 35 ) && ( $largura <= 40 ) ) $ret=' larg_40';
    elseif ( ( $largura > 40 ) && ( $largura <= 45 ) ) $ret=' larg_45';
    elseif ( ( $largura > 45 ) && ( $largura <= 50 ) ) $ret=' larg_50';
    elseif ( ( $largura > 50 ) && ( $largura <= 55 ) ) $ret=' larg_55';
    elseif ( ( $largura > 55 ) && ( $largura <= 60 ) ) $ret=' larg_60';
    elseif ( ( $largura > 60 ) && ( $largura <= 65 ) ) $ret=' larg_65';
    elseif ( ( $largura > 65 ) && ( $largura <= 70 ) ) $ret=' larg_70';
    elseif ( ( $largura > 70 ) && ( $largura <= 75 ) ) $ret=' larg_75';
    elseif ( ( $largura > 75 ) && ( $largura <= 80 ) ) $ret=' larg_80';
    elseif ( ( $largura > 80 ) && ( $largura <= 85 ) ) $ret=' larg_85';
    elseif ( ( $largura > 85 ) && ( $largura <= 90 ) ) $ret=' larg_90';
    elseif ( ( $largura > 90 ) && ( $largura <= 95 ) ) $ret=' larg_95';
    else $ret=' larg_100';
    return $ret;
  }
  
  function texto($nome,$etiqueta,$valor,$largura,$maximo) {
    $classe_larg = $this->get_classe_largura($largura);
    $parametros = '';
    if ( $maximo > 0 ) $parametros .= ' maxlength='.$maximo;
    $exibe = '<div class="campo '.$classe_larg.'" >';
    $exibe .= '<span class="etiqueta" id="'.$nome.'_lb" >' . $etiqueta . '</span>
      <span class="controle"><input name="'.$nome.'" type="text" id="'.$nome.'" value="'.$valor.'" '.$parametros.' /></span>
      </div>
      ';
    return $exibe;
  }
  
  function memo($nome,$etiqueta,$valor,$linhas,$largura) {
    $classe_larg = $this->get_classe_largura($largura);
    $exibe = '<div class="campo '.$classe_larg.'" >
       <span class="etiqueta" id="'.$nome.'_lb" >'.$etiqueta.'</span>
       <span class="controle"><textarea class=normal name="'.$nome.'" id="'.$nome.'" rows='.$linhas.' width=100% >'.$valor.'</textarea></span>
       </div>
       ';
    return $exibe;
  }
  
  function get_formdata($nome) {
    $data = isset($_POST[$nome]) ? $_POST[$nome] : 'NULL';
    if ( $data == '00/00/0000' ) $data = '';
    if ( !empty($data) ) {
      $dtpost = explode("/", $data);
      list($dia, $mes, $ano) = $dtpost;
      $data = $ano.'-'.$mes.'-'.$dia;
    }
    return $data;
  }
  
  function data($nome,$etiqueta,$data,$largura='100') {
    if ( $data == '0000-00-00' ) $data = '';
    if ( empty($data) ) $strdata = ''; else $strdata = date('d/m/Y', strtotime($data));
    $script = '<script>
      $(function() {
        $( "#'.$nome.'" ).datepicker( {
            changeMonth: true,
            changeYear: true
          });
        $( "#'.$nome.'" ).datepick("option", $.datepicker.regional[ "pt-BR" ]); ;
      });
      </script>
      ';
    $classe_larg = $this->get_classe_largura($largura);
    $exibe = $script.'<div class="campo '.$classe_larg.'" >
        <span class="etiqueta" id="'.$nome.'_lb" >' . $etiqueta . '</span>
        <span class="controle"><input type="text" name="'.$nome.'" id="'.$nome.'"value="'.$strdata.'" autocomplete="off" /></span>
      </div>
      ';    
    return $exibe;
  }
  
  function getcombo_vetor($nome, $valcampo, $vetor) {
    $exibe = '';
    $exibe = '<select name="'.$nome.'" id="'.$nome.'" >
      ';
    $count = count($vetor);
    for ($i = 0; $i < $count; $i++) {
      list($valor,$exibicao) = explode("¦", $vetor[$i]);
      $selecao = '';
      if ( $valor == $valcampo ) $selecao = ' selected ';
      $exibe .= '<option value="'.$valor.'" '.$selecao.' >'.$exibicao.'</option>
        ';
    }
    $exibe .= '</select>
      ';
    return $exibe;
  }

  function combo($nome,$etiqueta,$valcampo,$vetor,$largura="100") {
    $classe_larg = $this->get_classe_largura($largura);
    $exibe = '<div class="campo'.$classe_larg.'" >';
    $etiqueta = '<span class="etiqueta" id="'.$nome.'_lb" >' . $etiqueta . '</span>
                ';
    $exibe .= $etiqueta . '<span class="controle">
  	      '.$this->getcombo_vetor($nome, $valcampo,$vetor).
              '</span>
               </div>
              ';
    return $exibe;
  }
  
  function botao_ok($texto='') {
    if ( empty($texto) ) $texto = "OK";
    return '<button type="submit" name="ok" class=ok value="ok" >'.$texto.'</button>';
  }
  
  function botao_cancelar($link='#',$texto='') {
    if ( empty($texto) ) $texto = "Cancel";
    return '<button type="button" name="cancelar" class=cancelar value="Cancelar" onclick="location.href=\''.$link.'\'">'.$texto.'</button>';
  }
  
}

?>