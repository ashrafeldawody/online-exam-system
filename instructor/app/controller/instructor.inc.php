<?php
session_start();
include_once 'autoloader.inc.php';
if (isset($_GET['action']) && $_GET['action'] == 'login'){
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
  $hashedpwd = md5($pass);
  $inst = new instructor();
  $loggedIn = $inst->login($email,$hashedpwd);
  if ($loggedIn){
    $mydata = $inst->getByEmail($email);
    $_SESSION['mydata']= $mydata;
    header("Location: ../../");
  }else{
    $_SESSION["error"][] = 'Email or password is wrong!';
    header("Location: ../../");
  }
}else if (isset($_GET['action']) && $_GET['action'] == 'register'){
  $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
  $invite = !empty($_POST['invite']) ? trim($_POST['invite']) : null;
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
  $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
  $hashedpwd = md5($pass);
  $inst = new instructor();
  if ($inst->checkEmail($email)){
    $_SESSION["error"][] = 'Email Already Exists!';
  }
  if (!$inst->isValidInvite($invite)){
    $_SESSION["error"][] = 'Invitation Code is not valid';
  }
  if(!is_numeric($phone)) {
      $_SESSION["error"][] = 'Phone Number is not valid';
  }
  if(isset($_SESSION["error"])){
    header("Location: ../../?register");
  }else{
  $inst->register($name,$hashedpwd,$email,$phone,$invite);
  $_SESSION["info"][] = 'You account has been registered!<br> You can login now';
  header("Location: ../../?login");
}
}else if (isset($_GET['action']) && $_GET['action'] == 'manualinsert'){
  $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
  $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
  $hashedpwd = md5($pass);
  $inst = new instructor();
  if ($inst->checkEmail($email)){
    $_SESSION["error"][] = 'Email Already Exists!';
  }
  if(!is_numeric($phone)) {
      $_SESSION["error"][] = 'Phone Number is not valid';
  }
  if(empty($_SESSION["error"])){
  $inst->register($name,$hashedpwd,$email,$phone);
  $_SESSION["info"][] = 'The account has been registered';
  }
  header("Location: ../../?instructors");
}else if (isset($_GET['action']) && $_GET['action'] == 'requestReset'){
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $token = bin2hex(random_bytes(25));
    $inst = new instructor();
    if ($inst->checkEmail($email)){
      $inst->generatePasswordToken($email,$token);
      $_SESSION["info"][] = 'Reset Email will be sent in 2 minutes';
    }else{
      $_SESSION["error"][] = 'Email Address is not registered';
    }
    header("Location: ../../?login");
}
else if (isset($_GET['action']) && $_GET['action'] == 'resetPassword')
  {
  $token = !empty($_POST['token']) ? trim($_POST['token']) : null;
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
  $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : null;
    if ($password != $repassword){
        $_SESSION["error"][] = 'Password fields does not match';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    if (strlen($password) < 6){
        $_SESSION["error"][] = 'Password is too short';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    $inst = new instructor();
    if (!$inst->isValidReset($email,$token)){
      $_SESSION["error"][] = 'The Reset Token is not valid.. You may have followed a broken link';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    if(!isset($_SESSION["error"])){
      $inst = new instructor();
      $inst->resetPassword($email,md5($password));
      $_SESSION["info"][] = 'Your Password Has Been Successfully Updated';
      header("Location: ../../?login");
    }
}else if (isset($_GET['action']) && $_GET['action'] == 'updateInfo'){
  $name = !empty($_POST['profname']) ? trim($_POST['profname']) : null;
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $phonenum = !empty($_POST['phonenum']) ? trim($_POST['phonenum']) : null;
  if (!(preg_match('/^[0-9]+$/', $phonenum) and strlen($phonenum) == 11)) {
    $_SESSION["error"][] = 'Phone Number Is not valid';
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["error"][] = "Email format is not valid";
  }
  if(empty($_SESSION["error"])){
    $inst = new instructor();
    $inst->updateInfo($name,$email,$phonenum);
    $mydata = $inst->getByEmail($email);
    $_SESSION['mydata']= $mydata;
  }
  header('Location: ' . $_SERVER['HTTP_REFERER']);

}else if (isset($_GET['action']) && $_GET['action'] == 'updatePassword'){
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
  $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : null;
  if ($password != $repassword){
      $_SESSION["error"][] = 'Password fields does not match';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  if (strlen($password) < 6){
      $_SESSION["error"][] = 'Password is too short';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
  if(!isset($_SESSION["error"])){
  $hashedpwd = md5($password);
  $inst = new instructor();
  $inst-> updatePassword($email,$hashedpwd);
  $_SESSION["info"][] = 'Your Password Has Been Successfully Updated';
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
}
