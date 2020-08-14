<?php
session_start();
date_default_timezone_set('Africa/Cairo');
define('NotDirectAccess', true);
require_once 'app/model/student.class.php';
require_once 'app/model/test.class.php';
require_once 'app/model/group.class.php';
if(isset($_GET['code']) and strlen($_GET['code']) > 10 and !isset($_SESSION['student'])){
	$_SESSION['linkID'] = $_GET['code'];
}
if (isset($_SESSION['student'])) {
			$_student = new student;
			if(!$_student->checkSession($_SESSION['student']->id)){
				unset($_SESSION['student']);
				header("Location: /");
				exit;
			}
			if(isset($_SESSION['linkID']) and strlen($_SESSION['linkID']) > 10){
				$code = $_SESSION['linkID'];
				unset($_SESSION['linkID']);
				header('Location: ?tests&code='. $code .'&start');
			}
			if (isset($_GET['results']))
				require_once 'app/view/results.php';
			elseif (isset($_GET['groups']))
				require_once 'app/view/groups.php';
			elseif (isset($_GET['profile']))
				require_once 'app/view/profile.php';
      elseif (isset($_GET['logout']))
			 {unset($_SESSION['student']);
			  header("Location: /");}
			else
        require_once 'app/view/tests.php';
}else{
    if (isset($_GET['register']))
        require_once 'app/view/register.php';
      elseif (isset($_GET['reset']))
        require_once 'app/view/reset.php';
	  	else
        require_once 'app/view/login.php';
}
