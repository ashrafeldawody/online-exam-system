<?php
spl_autoload_register('myAutoLoader');
  function myAutoLoader ($className){
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if (strpos($url,'controller') !== false){
      $path = '../model/';
    }else{
      $path = "app/model/";
    }

    $extension = ".class.php";
    $fullPath = $path . $className . $extension;
    include_once $fullPath;
  }
  $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  if (strpos($url,'controller') !== false){
    $path = '../model/';
  }else{
    $path = "app/model/";
  }
  require_once $path . '../../vendor/autoload.php';
  include_once 'functions.php';


 ?>
