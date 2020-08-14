<?php
session_start();
include_once 'autoloader.inc.php';
if (isset($_GET['addTest'])){
  $name = !empty($_POST['testName']) ? trim($_POST['testName']) : null;
  $course = ($_POST['Course'] != 0) ? trim($_POST['Course']) : null;
  $newTest = new test();
  if($name == null || empty($name))
    $_SESSION['error'][] = 'The Test Name is not valid.';
  if(empty($_SESSION['error'])){
    $newTest->insert($name,$course);
  $_SESSION['info'][] = 'Test Added successfully';
}
  header('Location: ' . $_SERVER['HTTP_REFERER']);exit;

}else if (isset($_GET['addQuestionsToTest'])){
  $testID = $_GET['addQuestionsToTest'];
  $questions = $_POST['Question'];
  $newTest = new test();
  $newTest->addQuestionsToTest($testID,$questions);
  header('Location: ../../?tests=view&id=' . $testID);
  exit;
}else if (isset($_GET['deleteQuestionsFromTest'])){
  $testID = $_GET['deleteQuestionsFromTest'];
  $questions = $_POST['Question'];
  $newTest = new test();
  $newTest->deleteQuestionsFromTest($testID,$questions);
  header('Location: ../../?tests=view&id=' . $testID);
  exit;
}
else if (isset($_GET['editTest'])){
  $id = !empty($_POST['testid']) ? trim($_POST['testid']) : null;
  $name = !empty($_POST['testName']) ? trim($_POST['testName']) : null;
  $course = ($_POST['Course'] != 0) ? trim($_POST['Course']) : null;
  $newTest = new test();
  if($id == null)
    $_SESSION['error'][] = 'The Test ID is not valid.';
  if($name == null || empty($name))
    $_SESSION['error'][] = 'The Test Name is not valid.';
  if(empty($_SESSION['error'])){
  $newTest->update($id,$name,$course);
  $_SESSION['info'][] = 'Test updated successfully';

}
  header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
}else if (isset($_GET['deleteTest'])){
  $id = !empty($_GET['deleteTest']) ? trim($_GET['deleteTest']) : null;
  $newTest = new test();
  $newTest->setTestDelete($id);
  header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
}else if (isset($_GET['restoreTest'])){
  $id = !empty($_GET['restoreTest']) ? trim($_GET['restoreTest']) : null;
  $newTest = new test();
  $newTest->restoreTest($id);
  header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
}else if (isset($_GET['delete'])){
  $id = !empty($_GET['delete']) ? trim($_GET['delete']) : null;
  $newTest = new test();
  $newTest->delete($id);
  header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
}else if(isset($_GET['settingID']) && isset($_GET['testID'])){
  $settingID = !empty($_GET['settingID']) ? trim($_GET['settingID']) : null;
  $testID = !empty($_GET['testID']) ? trim($_GET['testID']) : null;
  $newTest = new test();
  $newTest->releaseResult($settingID);
  $newTest->sendMails($testID,$settingID);
  header('Location: ../../?tests=sessions');exit;
}else if(isset($_GET['viewAnswers'])){
  $id = !empty($_GET['viewAnswers']) ? trim($_GET['viewAnswers']) : null;
  $newTest = new test();
  $newTest->viewAnswers($id);
  header('Location: ../../?tests=sessions');exit;
}else if(isset($_GET['hideAnswers'])){
  $id = !empty($_GET['hideAnswers']) ? trim($_GET['hideAnswers']) : null;
  $newTest = new test();
  $newTest->hideAnswers($id);
  header('Location: ../../?tests=sessions');exit;

}else if(isset($_GET['duplicateTest'])){
  $id = !empty($_GET['duplicateTest']) ? trim($_GET['duplicateTest']) : null;
  $newTest = new test();
  $newTest->duplicateTest($id);
  $nid = $newTest->lastAddedTest();
  header('Location: ../../?tests=view&id=' . $nid);exit;
}else if (isset($_GET['deleteQuestionsFromTest'])){
  $tid = !empty($_POST['testID']) ? trim($_POST['testID']) : null;
  $qid = !empty($_POST['questionID']) ? trim($_POST['questionID']) : null;
  $newTest = new test();
  if (($tid != null) && ($qid != null)){
    $newTest->deleteQuestionsFromTest($tid,$qid);
  }
  header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
}else if (isset($_GET['deleteRandomRule'])){
  $tid = !empty($_POST['testID']) ? trim($_POST['testID']) : null;
  $cid = !empty($_POST['courseID']) ? trim($_POST['courseID']) : null;
  $diff = !empty($_POST['diff']) ? trim($_POST['diff']) : 1;
  $newTest = new test();
  if (($tid != null) && ($cid != null)){
    $newTest->deleteRandomRule($tid,$cid,$diff);
  }
}else if (isset($_GET['addRandomRule'])){
  $testID = !empty($_POST['testID']) ? trim($_POST['testID']) : null;
  $courseID = !empty($_POST['courseID']) ? trim($_POST['courseID']) : null;
  $diff = !empty($_POST['diff']) ? trim($_POST['diff']) : null;
  $count = !empty($_POST['Count']) ? trim($_POST['Count']) : null;
  $newTest = new test();
  if(!$newTest->checkAvailableCount($testID,$courseID,$diff,$count)){
    echo 'You Don\'t have enought question in this topic';
  }elseif (($testID != null) && ($courseID != null) && ($count > 0)){
    $newTest->addRandomRule($testID, $courseID,$count,$diff);
    echo 'success';
  }
}
