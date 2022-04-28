<?php

/***
 *     ######  #### ######## ######## 
 *    ##    ##  ##     ##    ##       
 *    ##        ##     ##    ##       
 *     ######   ##     ##    ######   
 *          ##  ##     ##    ##       
 *    ##    ##  ##     ##    ##       
 *     ######  ####    ##    ######## 
 */

class site {

  var $url;
  
  function __construct() {
    include_once('Application/core/url.php');
    include_once('Application/core/banco.php');
    include_once('Application/core/html.php');
    include_once('Application/core/utili.php');
    include_once('Application/core/form.php');
    $this->url = new url;
    setlocale(LC_NUMERIC, 'en_US');
  }
  
  function site_cabecalho() {
    $path = $this->url->get_host_path();
    $link = $this->url->get_href_int();
    $ret = '';
    $ret .= '<!DOCTYPE html>
      <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Gesher PTM - Personal Task Manager</title>
        <meta name="viewport" content="width=device-width">
        <meta name="description" content="Personal Task Manager" />
        <meta name="robots" content="index, follow">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@300;400;500;600;700&display=swap" rel="stylesheet">        
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">        
        <script type="text/javascript" src="'.$path.'js/jquery-3.5.0.js"></script>
        <link rel="shortcut icon" href="'.$path.'favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="'.$path.'js/bootstrap/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="'.$path.'js/zebra-dialog/css/materialize/zebra_dialog.min.css" type="text/css" />
        <link rel="stylesheet" href="'.$path.'css/style.css" type="text/css" />
      </head>
      <body>
      <div id=corpo_site>
        <div class="mainmenu-wrapper" style="background: silver;"><div class="container">
          <nav class="navbar navbar-sticky bootsnav" style="background: transparent;">
            <div class="container">
              <div class="navbar-header">
                <a class="navbar-brand" href="'.$link.'"><img src="'.$path.'img/logo.png" class="logo" title="Gesher" ></a>
              </div>
            </div>   
          </nav>
        </div>
      </div>
      '; 
    return $ret;
  }
  
  function site_footer_scripts() {
    $path = $this->url->get_host_path();
    $ret = '<!-- Javascripts -->
      <script type="text/javascript" src="'.$path.'js/jquery-ui-1.13.1/jquery-ui.js"></script>
      <script type="text/javascript" src="'.$path.'js/zebra-dialog/zebra_dialog.min.js"></script>
      <script type="text/javascript" src="'.$path.'js/bootstrap/js/bootstrap.bundle.min.js.js"></script>
      ';
    return $ret;
  }
  
  function site_footer_sticky() {
    $ret = '<script>
      // Window load event used just in case window height is dependant upon images
      $(window).bind("load", function() {         
        var footerHeight = 0,
            footerTop = 0,
            $footer = $("#footer");
            $site = $("#corpo_site");
           
         positionFooter();
         function positionFooter() {
           footerHeight = $footer.height();
           siteHeight = $site.height();
           windowHeight = $(window).height();
           footerTop = (windowHeight-footerHeight-10)+"px";
           if ( (siteHeight+footerHeight) < windowHeight) {
             $footer.css({
               position: "absolute",
               bottom: 0
             })
           } else {
             $footer.css({
                position: "static"
             })
           }              
       }
       $(window)
         .scroll(positionFooter)
         .resize(positionFooter)         
      });
      </script>
      ';
    return $ret;
  }
  
  function site_footer() {
    $footer = '<div id="footer" class="footer sticky" ><div class="container"><div class="row">
      <div class="col-md-6"><div class="footer-social-media"></div></div>
      <div class="col-md-6">
        <div class="footer-copyright"><div class=rodape-copyright>Copyright &copy; '.date("Y").' &bull; All Rights Reserved<br/>Powered by Gesher</div></div>
      </div>
      </div></div></div>
      ';
    $footer .= $this->site_footer_scripts();
    $footer .= $this->site_footer_sticky();
    
    $ret = '</div>
      '.$footer.'
      </body>
      </html>
      ';
    return $ret;
  }
  
  function start() {
    $route = $this->url->route;
    $modo = $this->url->modo;
    $id = $this->url->id;
    if ( $route == 'action' ) {
      include_once('Application/controllers/ActionController.php');
      $controller = new ActionController();
    } elseif ( $route == 'task' ) {
      include_once('Application/controllers/TaskController.php');
      $controller = new TaskController();
    } else {
      include_once('Application/controllers/PipelineController.php');
      $controller = new PipelineController();
    }
    $ret = '';
    $ret .= $this->site_cabecalho();
    $ret .= '<div class="section section-breadcrumbs" style="padding: 5px 0;"><div class="container"><div class="row" >
      <div class="col-md-12">
        <h1 style="display:block; float:left; clear:left; width: auto; ">Personal Task Manager</h1>
      </div>
      </div></div></div>
      <div class="section" style="padding: 5px 0;"><div class="container"><div class="row" >
      <div class="col-md-12"><div id="filtro">
      ';
    
    $ret .= $controller->painel($modo,$id);
    $ret .= '</div></div>
       </div></div></div>
      ';
    $ret .= $this->site_footer();
    return $ret;
  }
  
}

?>