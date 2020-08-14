<?php
session_start();
date_default_timezone_set('Africa/Cairo');
include_once 'app/controller/autoloader.inc.php';
$inst = new instructor();
define('NotDirectAccess', true);
if (isset($_SESSION['mydata']) and !$_SESSION['mydata']->isAdmin and $inst->checkAccount($_SESSION['mydata']->id)) {
    if (isset($_GET['questions'])) {
        require_once 'app/view/questions.php';
    } elseif (isset($_GET['tests'])) {
        require_once 'app/view/tests.php';
    } elseif (isset($_GET['groups'])) {
        require_once 'app/view/groups.php';
    } elseif (isset($_GET['courses'])) {
        require_once 'app/view/courses.php';
    } elseif (isset($_GET['users'])) {
        require_once 'app/view/users.php';
    } elseif (isset($_GET['results'])) {
        require_once 'app/view/results.php';
    } elseif (isset($_GET['profile'])) {
        require_once 'app/view/profile.php';
    } elseif (isset($_GET['assign'])) {
        require_once 'app/view/assign.php';
    } elseif (isset($_GET['students'])) {
        require_once 'app/view/students.php';
    } elseif (isset($_GET['logout'])) {
      unset($_SESSION['mydata']);
      header("Location: ../index.php");
        require_once 'app/view/login.php';
    } else {
        require_once 'app/view/courses.php';
    }
}elseif (isset($_SESSION['mydata']) and $_SESSION['mydata']->isAdmin) {
  if (isset($_GET['students'])) {
      require_once 'app/view/admin_students.php';
  } elseif (isset($_GET['results'])) {
      require_once 'app/view/results.php';
  } elseif (isset($_GET['profile'])) {
      require_once 'app/view/profile.php';
  } elseif (isset($_GET['logout'])) {
    unset($_SESSION['mydata']);
    header("Location: ../index.php");
      require_once 'app/view/login.php';
  } else {
      require_once 'app/view/admin_instructors.php';
  }
} else {
    if (isset($_GET['register'])) {
        require_once 'app/view/register.php';
      } elseif (isset($_GET['reset'])) {
          require_once 'app/view/reset.php';
    } else {
        require_once 'app/view/login.php';
    }
}
