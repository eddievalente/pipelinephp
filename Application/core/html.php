<?php

/*
 *  ##     ## ######## ##     ## ##       
 *  ##     ##    ##    ###   ### ##       
 *  ##     ##    ##    #### #### ##       
 *  #########    ##    ## ### ## ##       
 *  ##     ##    ##    ##     ## ##       
 *  ##     ##    ##    ##     ## ##       
 *  ##     ##    ##    ##     ## ######## 
 */

class html {
  
  function ficha_inicio() {
    $exibe = '<div id="ficha">
      ';
    return $exibe;
  }

  function ficha_vetor($vetor) {
    $exibe = '';
    $count = count($vetor);
    $linhatit = '';
    $linhacont = '';
    for ($i = 0; $i < $count; $i++) {
      //list($titulo,$conteudo,$tamanho) = explode("¦", $vetor[$i]);
      $lista = explode("¦", $vetor[$i]);
      $itens = count($lista);
      $titulo = $lista[0];
      $conteudo = $lista[1];
      $tamanho = $lista[2];
      if ( $itens > 3 ) $alinhamento = $lista[3]; else $alinhamento = '';
      if ( $itens > 4 ) $fundo = $lista[4]; else $fundo = '';
      if ( $itens > 5 ) $fonte = $lista[5]; else $fonte = '';
      if ( $itens > 6 ) $borda = $lista[6]; else $borda = '';
      
      if ( !empty($tamanho) ) $tamanho=' width='.$tamanho.'%; ';
      
      if ( !empty($alinhamento) ) $alinhamento = ' text-align: '.$alinhamento.'; ';
      if ( !empty($fundo) ) $fundo = ' background: '.$fundo.'; ';
      if ( !empty($fonte) ) $fonte = ' color: '.$fonte.'; ';
      if ( !empty($borda) ) $borda = ' border: '.$borda.'; ';
              
      $estilo = ' style="'.$alinhamento.$fundo.$fonte.$borda.'" ';
      
      if ( empty($titulo) ) $titulo = '&nbsp;';
      if ( empty($conteudo) ) $conteudo = '&nbsp;';

      $linhatit .= '<th '.$tamanho.' >'.$titulo.'</th>';
      $linhacont .= '<td '.$estilo.' data-label="'.$titulo.'">'.$conteudo.'</td>';
    }
    $exibe .= '<table width=100%><tr>'.$linhatit.'</tr><tr>'.$linhacont.'</tr></table>
        ';
    return $exibe;
  }

  function ficha_fim() {
    $exibe = '</div>
      ';
    return $exibe;
  }
  
  function icone($tipo) {
    $icone = '';
    $ret = '';
    if ( $tipo == 'UP' ) {
      $icone = 'fa-arrow-up';
      $cor = '#DAA520';
    } elseif ( $tipo == 'UPOFF' ) { 
      $icone = 'fa-arrow-up'; 
      $cor = 'silver';
    } elseif ( $tipo == 'DW' ) {
      $icone = 'fa-arrow-down';
      $cor = '#DAA520';
    } elseif ( $tipo == 'DWOFF' ) {
      $icone = 'fa-arrow-down';
      $cor = 'silver';
    }
    $ret = '<span class="icofiltro '.$icone.'"  style="color:'.$cor.';" ></span>';
    return $ret;
  }
  
  function botoeira( $vet_botao="" ) {
    $exibe = '';
    if ( !empty($vet_botao) ) {
      $exibe .= '<div class=botoeira>
        ';
      $qtd = count($vet_botao);
      for ( $i=0; $i<$qtd; $i++ ) {
        $item = $vet_botao[$i];
        if ( $item == '¦' ) {
          $exibe .= '<span class=btdivisor>&nbsp;</span>
            ';
        } else {
          $exibe .= '<span class=botao>'.$item.'</span>
            ';
        }
      }
      $exibe .= '</div>
        ';
    }
    return $exibe;
  }
  
  function formdiv_inicio() { 
    $exibe = '<div id="formulario" >
        ';
    return $exibe;
  }

  function formdiv_fim() { 
    $exibe = '</div>
        ';
    return $exibe;
  } 
  
  function getGUID(){
    if ( function_exists('com_create_guid') ) {
      return com_create_guid();
    } else {
      mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
      $charid = strtoupper(md5(uniqid(rand(), true)));
      $hyphen = '-';
      $uuid = substr($charid,0,8).$hyphen.substr($charid,8,4).$hyphen.substr($charid,12,4).$hyphen.substr($charid,16,4).$hyphen.substr($charid,20,12);
      return $uuid;
    }
  }
  
  function scroll_inicio($colunas='',$altura='',$modo=0,$classe_tabela='') { 
    $tabela_id = $this->getGUID();
    $exibe = '';
    $cabecalho='';
    $cabecalho_fake='';
    if ( !empty($colunas) ) {
      $qtd = count($colunas);
      for ( $i=0; $i<$qtd; $i++ ) {
        $coluna = explode('¦',$colunas[$i]);
        $etiqueta = $coluna[0];
        $largura = $coluna[1];
        $alinhamento = isset($coluna[3]) ? $coluna[3] : '';
        if ( !empty($alinhamento) ) $alinhamento = ' style="text-align: '.$alinhamento.';" ';
        $cabecalho .= '<th width="'.$largura.'%" '.$alinhamento.' class=sticky>'.$etiqueta.'</th>'; 
        $cabecalho_fake .= '<th width="'.$largura.'%" class=vazio></th>'; 
      }
    }
    $tipo='rolagem';
    if ( !empty($altura) ) $altura = 'style="height:'.$altura.';"';
    if ( $modo == 3 ) {
      $tipo='rolagembloco';
    } elseif ( $modo == 4 ) {
      $tipo='rolagempainel';
    }
    $exibe .= '<div class="'.$tipo.'" '.$altura.' >
      <div class="scroll" id="'.$tabela_id.'_scroll" >
      ';
    if ( !empty($cabecalho) ) {
      $exibe .= '<table class=sticky> 
        <tr>'.$cabecalho.'</tr>
        </table>
        ';
    }
    $exibe .= '<table id="'.$tabela_id.'" class="'.$classe_tabela.'"> 
      <tbody id="'.$tabela_id.'_corpo" >
      ';
    if ( !empty($cabecalho) ) {
      $exibe .= '<thead><tr>'.$cabecalho_fake.'</tr></thead>
        ';
    }
    $exibe .= '<tbody id="'.$tabela_id.'_corpo" >
      ';
    return $exibe;
  }
  
  function scroll_linha($vetor) {
    $count = count($vetor);
    $exibe = '<tr>';
    for ($i = 0; $i < $count; $i++) {
      $vet = explode("¦", $vetor[$i]);
      $campo = isset($vet[0]) ? $vet[0] : '';
      $alinhamento = isset($vet[1]) ? $vet[1] : '';
      $colspan = isset($vet[2]) ? $vet[2] : 1;
      $fundo = isset($vet[3]) ? $vet[3] : '';
      $fonte = isset($vet[4]) ? $vet[4] : '';
      $borda = isset($vet[5]) ? $vet[5] : '';
      $rowspan = isset($vet[6]) ? $vet[6] : '';
      //
      if ( !empty($alinhamento) ) $alinhamento = ' text-align:'.$alinhamento.'; ';
      if ( !empty($fundo) ) $fundo = ' background:'.$fundo.'; ';
      if ( !empty($fonte) ) $fonte = ' color:'.$fonte.'; ';
      if ( !empty($borda) ) $borda = ' border: '.$borda.'; ';
      $estilo = ' style="'.$alinhamento.$fundo.$fonte.$borda.'" ';
      $colspanning = '';
      $rowspanning = '';
      if ( $colspan > 1 ) $colspanning = ' colspan='.$colspan.' ';
      if ( $rowspan > 1 ) $rowspanning = ' rowspan='.$rowspan.' ';
      $exibe .= '<td '.$estilo.' '.$colspanning.' '.$rowspanning.'>'.$campo.'</td>';
    }
    $exibe .= '</tr>
      ';
    return $exibe;
  }
  
  function scroll_fim() { 
    $exibe = '</tbody>
      </table></div>
      </div>
      ';
    return $exibe;
  }
  
  function filtro_divisor($titulo="",$vet_botao="") {
    $exibe = '<span class=divisor><h1>'.$titulo.'</h1>';
    $exibe .= $this->botoeira($vet_botao);
    $exibe .= '</span>
        ';
    return $exibe;
  }
  
  function filtro_header($titulo='',$informacao='',$botoes='',$extra='',$breadcrumb='') {
    if ( empty($informacao) ) $informacao = '&nbsp;';
    $botoeira = '';
    if ( !empty($botoes) ) {
      $botoeira .= '<div class=painel_botao>
        ';
      $qtd = count($botoes);
      for ( $i=0; $i<$qtd; $i++ ) {
        $item = $botoes[$i];
        if ( $item == '¦' ) {
          $botoeira .= '<span class=btdivisor>&nbsp;</span>
            ';
        } else {
          $botoeira .= '<span class=botao>'.$item.'</span>
            ';
        }
      }
      $botoeira .= '</div>
        ';
    }
    if ( !empty($extra) ) $extra = '<span class=extra>'.$extra.'</span>
      ';
    $exibe = '';
    $exibe .= $breadcrumb;
    $exibe .= '<div class=header><div class=esquerda>'.$titulo.'</div><div class=direita>'.$informacao.'</div>
      '.$extra.$botoeira.'
      </div>
      ';
    return $exibe;
  }

}

?>