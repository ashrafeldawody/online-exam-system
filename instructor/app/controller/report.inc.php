<?php
session_start();
include_once 'autoloader.inc.php';
if ($_GET['action'] == 'getTopTests'){
  $rep = new report();
  $mostActiveTests = $rep->mostActiveTests();
  $t = array();
  $finalArray = array();
  foreach($mostActiveTests as $test){
      $t['name'] = $test->name;
      $t['taken'] = $test->taken;
      array_push($finalArray,$t);
  }
  echo json_encode($finalArray);
}
elseif (($_GET['action'] == 'getResultRightWrong') && isset($_GET['id'])){
  $_result = new result();
  $result = $_result->getByID($_GET['id']);
  $t = array();
      $t['correct'] = ($result->RightQuestions / $result->Questions) * 100;
      $t['wrong'] = ($result->WrongQuestions / $result->Questions) * 100;
  echo json_encode($t);
}
