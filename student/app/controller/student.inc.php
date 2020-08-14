<?php
session_start();
require_once '../model/student.class.php';
require_once 'function.php';
if ($_GET['action'] == 'login'){
  $id = $_POST['id'];
  $pass = $_POST['password'];
  $hashedpwd = md5($pass);
  $_student = new student();
  $loggedIn = $_student->login($id,$hashedpwd);
  if ($loggedIn){
    $student = $_student->getByID($id);
    $_student->setSession($id);
    if($student->suspended) {
      echo 'Your account has been disabled for violating our terms';
      exit;
    }else{
      $_SESSION['student']= $student;
  	   echo 'success';
       exit;
     }
}else{
  echo 'Your ID or password is not correct!';
}
}else if ($_GET['action'] == 'register'){
  $id = !empty($_POST['id']) ? trim($_POST['id']) : null;
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
  $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
  $hashedpwd = md5($pass);
  $_student = new student();
  if (strlen($pass) < 8)
    echo "Password too short!";
  elseif($email == null)
    echo 'Email can\'t be empty';
  elseif($pass == null)
    echo 'Password can\'t be empty';
  elseif(strlen($phone) != 11)
    echo 'Phone Number is not valid';
  elseif ($_student->checkEmail($email))
    echo 'Email Already Used';
  elseif ($_student->checkPhone($phone))
    echo 'Phone Number is already registered';
  elseif (!preg_match("#[0-9]+#", $pass))
    echo "Password must include at least one number!";
  elseif(!preg_match("#[a-zA-Z]+#", $pass))
    echo "Password must include at least one letter!";
  else{
  $_student->register($id,$hashedpwd,$email,$phone);
    echo 'success';
}
}else if ($_GET['action'] == 'checkID'){
  $id = !empty($_POST['id']) ? trim($_POST['id']) : null;
  $_student = new student();
  $status = $_student->checkIDforRegister($id);
  if (!$status){
    echo 'ID doesn\'t exist in our system';
  }else{
    if($status->nullpass == 0){
      echo 'ID is already registered, Try to recover your account';
    }else{
      echo 'success';
    }
  }

}else if ($_GET['action'] == 'requestReset'){
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $_student = new student();
    if (($_student->checkEmail($email))){
      $token = bin2hex(random_bytes(25));
      $_student->generatePasswordToken($email,$token);
      echo 'success';
    }else{
      echo 'Email Address is not Registered';
    }

}
else if ($_GET['action'] == 'resetPasswordWithToken'){
    $token = !empty($_POST['token']) ? trim($_POST['token']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $_student = new student();
    if (strlen($pass) < 8)
      echo "Password too short!";
    elseif($pass == null)
      echo 'Password can\'t be empty';
    elseif (!preg_match("#[0-9]+#", $pass))
      echo "Password must include at least one number!";
    elseif(!preg_match("#[a-zA-Z]+#", $pass))
      echo "Password must include at least one letter!";
    elseif (!$_student->checkPasswordToken($email,$token))
      echo 'The Reset Token is not valid.. You may have followed a broken link';
    else{
      $password = md5($pass);
      $_student->updatePassword($email,$password);
      echo 'success';
    }
}else if ($_GET['action'] == 'resetPassword'){
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
  $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : null;
    $_student = new student();
    if (strlen($password) < 6)
        echo 'Password is too short';
    elseif($password != $repassword)
        echo 'Password Fields doesn\'t match';
    elseif($password == null)
        echo 'Password is not valid';
    else{
      $password = md5($password);
      $_student->updatePassword($email,$password);
      echo 'success';}
}else if ($_GET['action'] == 'updateInfo'){
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $oldemail = !empty($_POST['oldemail']) ? trim($_POST['oldemail']) : null;
  $phonenum = !empty($_POST['phonenum']) ? trim($_POST['phonenum']) : null;
  $_student = new student();
  if ($email != $oldemail && $_student->checkEmail($email))
    echo 'Email already used';
  elseif (strlen($phonenum) != 11)
    echo 'Phone Number Is not valid';
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    echo 'Email format is not valid';
  else{
    $_student->updateInfo($email,$phonenum);
    $newDATA = $_student->getByID($_SESSION['student']->id);
    $_SESSION['student'] = $newDATA;
    echo "success";
  }
}
