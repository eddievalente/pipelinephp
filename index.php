<?php

  ob_start();
  session_start();
  date_default_timezone_set("America/Toronto");
  
  header("Content-Type: text/html; charset=UTF-8", true);

  include_once('Application/core/error.php');    
  $erro_trat = new _Error;
  include_once('Application/core/site.php');      
  //
  try {
    $site = new site();
    echo $site->start();
  }
  catch (Throwable  $t) {
    echo $erro_trat->get_error_msg($t);
    exit;
  }
  
?>
