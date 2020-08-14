<?php
session_start();
include_once 'autoloader.inc.php';
if ($_GET['action'] == 'approveAnswer'){
  $res = new result;
  $ans = $_POST['ansID'];
  $points = $_POST['points'];
  if($points == 0){
    $res->acceptAnswer($ans);
  }else{
    $res->acceptAnswer($ans,1,$points);
  }
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
}elseif ($_GET['action'] == 'rejectAnswer' and isset($_GET['id']) and is_numeric($_GET['id'])){
  $res = new result;
  $res->acceptAnswer($_GET['id']);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
}
