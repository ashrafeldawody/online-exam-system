<?php
session_start();
include_once 'autoloader.inc.php';

if (isset($_GET['addCourse'])){
  $name = !empty($_POST['courseName']) ? trim($_POST['courseName']) : null;
  $parent = ($_POST['course'] != 0) ? trim($_POST['course']) : null;
  $newCourse = new course();
  $checkNameAvailable = $newCourse->checkName($name,$parent);
  if($name == null || empty($name))
    $_SESSION['error'][] = 'The Course Name is not valid.';
  if($checkNameAvailable)
    $_SESSION['error'][] = 'The Course name is already used.';
  if(empty($_SESSION['error'])){
    $newCourse->insert($name,$parent);
  $_SESSION['info'][] = 'Course Added successfully';
}
  header('Location: ' . $_SERVER['HTTP_REFERER']);

}else if (isset($_GET['editCourse'])){
  $id = isset($_POST['id']) ? trim($_POST['id']) : null;
  $name = isset($_POST['courseName']) ? trim($_POST['courseName']) : null;
  $parent = ($_POST['course'] != 0) ? trim($_POST['course']) : null;
  $newCourse = new course();
  $checkNameAvailable = $newCourse->checkName($name,$parent);
  if($id == null)
    $_SESSION['error'][] = 'The Course ID is not valid.';
  if($name == null || empty($name))
    $_SESSION['error'][] = 'The Course Name is not valid.';
  if($checkNameAvailable)
    $_SESSION['error'][] = 'The Course name is already used.';
  if(empty($_SESSION['error'])){
  $newCourse->update($id,$name,$parent);
  $_SESSION['info'][] = 'Course updated successfully';
}
  header('Location: ' . $_SERVER['HTTP_REFERER']);

}else if (isset($_GET['deleteCourse'])){
  $id = !empty($_GET['deleteCourse']) ? trim($_GET['deleteCourse']) : null;
  $newCourse = new course();
  $newCourse->delete($id);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
