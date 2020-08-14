<?php
session_start();
include_once 'autoloader.inc.php';

if (isset($_GET['assignToLink'])){
  $testID     = !empty($_POST['testID']) ? trim($_POST['testID']) : 0;
  $linkName   = !empty($_POST['linkName']) ? trim($_POST['linkName']) : null;
  $useLimit   = !empty($_POST['useLimit']) ? trim($_POST['useLimit']) : 0;
  $startTime  = !empty($_POST['startTime']) ? date('Y-m-d H:i:s',strtotime($_POST['startTime'])) : null;
  $endTime    = !empty($_POST['endTime']) ? date('Y-m-d H:i:s',strtotime($_POST['endTime'])) : null;
  $random     = !empty($_POST['random']) ? 1 : 0;
  $prevQuestion = !empty($_POST['prevQuestion']) ? 1 : 0;
  $duration   = !empty($_POST['duration']) ? trim($_POST['duration']) : 0;
  $percent    = !empty($_POST['percent']) ? trim($_POST['percent']) : 0;
  $viewAnswers= $_POST['showAnswers'];
  $releaseResult = $_POST['releaseResult'];
  $sendToS    = isset($_POST['sendToS']) ? 1 : 0;
  $sendToI    = isset($_POST['sendToI']) ? 1 : 0;

  if($duration == 0     || !is_numeric($duration))
    echo 'Test Duration must be a valid Number greater Then Zero';
  elseif($percent == 0  || !is_numeric($percent))
    echo 'Pass Percent must be a valid Number greater Then Zero';
  elseif($testID == 0   || !is_numeric($testID))
    echo 'Test ID is not valid';
  elseif($useLimit == 0 || !is_numeric($useLimit))
    echo 'Link Usage Limit is not valid';
  else{
    $newAssign = new assign;
    $newAssign->createSetting($startTime,$endTime,$random,$prevQuestion,$duration,$percent,$viewAnswers,$releaseResult,$sendToS,$sendToI);
    $newAssign->AssignToLink($linkName,$testID,$useLimit);
    header('Location: ../../?tests=view&id=' . $testID);
  }
}elseif (isset($_GET['updateLinkAssign'])){
  $testID     = !empty($_POST['testID']) ? trim($_POST['testID']) : 0;
  $linkID     = !empty($_POST['linkID']) ? trim($_POST['linkID']) : 0;
  $linkName   = !empty($_POST['linkName']) ? trim($_POST['linkName']) : null;
  $useLimit   = !empty($_POST['useLimit']) ? trim($_POST['useLimit']) : 0;
  $startTime  = !empty($_POST['startTime']) ? date('Y-m-d H:i:s',strtotime($_POST['startTime'])) : null;
  $endTime    = !empty($_POST['endTime']) ? date('Y-m-d H:i:s',strtotime($_POST['endTime'])) : null;
  $random     = !empty($_POST['random']) ? 1 : 0;
  $prevQuestion = !empty($_POST['prevQuestion']) ? 1 : 0;
  $duration   = !empty($_POST['duration']) ? trim($_POST['duration']) : 0;
  $percent    = !empty($_POST['percent']) ? trim($_POST['percent']) : 0;
  $viewAnswers= $_POST['showAnswers'];
  $releaseResult = $_POST['releaseResult'];
  $sendToS    = isset($_POST['sendToS']) ? 1 : 0;
  $sendToI    = isset($_POST['sendToI']) ? 1 : 0;
  if($duration == 0     || !is_numeric($duration))
    echo 'Test Duration must be a valid Number greater Then Zero';
  elseif($percent == 0  || !is_numeric($percent))
    echo 'Pass Percent must be a valid Number greater Then Zero';
  elseif($useLimit == 0 || !is_numeric($useLimit))
    echo 'Link Usage Limit is not valid';
  else{
    $newAssign = new assign;
    $newAssign->createSetting($startTime,$endTime,$random,$prevQuestion,$duration,$percent,$viewAnswers,$releaseResult,$sendToS,$sendToI);
    $newAssign->updateLinkAssign($linkID,$linkName,$useLimit);
    $newAssign->deleteUnusedSettings();
    header("Location: ../../?tests=view&id=" . $testID);exit;
  }
}else if (isset($_GET['assignToGroup'])){
  $testID     = !empty($_POST['testID']) ? trim($_POST['testID']) : 0;
  $groupID    = !empty($_POST['groupID']) ? trim($_POST['groupID']) : 0;
  $startTime  = !empty($_POST['startTime']) ? date('Y-m-d H:i:s',strtotime($_POST['startTime'])) : null;
  $endTime    = !empty($_POST['endTime']) ? date('Y-m-d H:i:s',strtotime($_POST['endTime'])) : null;
  $random     = !empty($_POST['random']) ? 1 : 0;
  $prevQuestion = !empty($_POST['prevQuestion']) ? 1 : 0;
  $duration   = !empty($_POST['duration']) ? trim($_POST['duration']) : 0;
  $percent    = !empty($_POST['percent']) ? trim($_POST['percent']) : 0;
  $viewAnswers= $_POST['showAnswers'];
  $releaseResult = $_POST['releaseResult'];
  $sendToS    = isset($_POST['sendToS']) ? 1 : 0;
  $sendToI    = isset($_POST['sendToI']) ? 1 : 0;
  if($duration == 0 || !is_numeric($duration))
    echo 'Test Duration must be a valid Number greater Then Zero';
  elseif($percent == 0 || !is_numeric($percent))
    echo 'Pass Percent must be a valid Number greater Then Zero';
  elseif($testID == 0 || !is_numeric($testID))
    echo 'Test ID is not valid';
  elseif($groupID == 0 || !is_numeric($groupID))
    echo 'Group ID is not valid';
  else{
    $newAssign = new assign;
    $newAssign->createSetting($startTime,$endTime,$random,$prevQuestion,$duration,$percent,$viewAnswers,$releaseResult,$sendToS,$sendToI);
    $newAssign->AssignToGroup($groupID,$testID);
    header("Location: ../../?groups");exit;
  }
}else if (isset($_GET['deleteLink']) && (!empty($_GET['deleteLink']))){
    if(is_numeric($_GET['deleteLink'])){
      $_assign = new assign();
      $_assign->deleteLink($_GET['deleteLink']);
    }
    if($_SERVER['HTTP_REFERER'])
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    else
    header('Location: ?tests');
}else if (isset($_GET['deleteAssignedTest'])){
  $id = !empty($_GET['deleteAssignedTest']) ? trim($_GET['deleteAssignedTest']) : null;
  $_assign = new assign();
  $_assign->deleteGroupAssign($id);
  if($_SERVER['HTTP_REFERER'])
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  else
  header('Location: ?tests');
}
