<?php
session_start();
include_once 'autoloader.inc.php';

if (isset($_GET['insertstudents'])){
  $studentIDs = !empty($_POST['studentIDs']) ? trim($_POST['studentIDs']) : null;
  $ids = explode("\n", str_replace("\r", "", $studentIDs));
  $student = new student();
  foreach ($ids as $key => $val)
    if (!is_numeric($val))
        unset($ids[$key]);
   $student->addStudents($ids);
   header('Location:../../?students&unregistered');

}elseif (isset($_GET['deleteStudent'])){
  $id = !empty($_GET['deleteStudent']) ? trim($_GET['deleteStudent']) : null;
  $student = new student();
  $student->deleteStudent($id);
  if (isset($_GET['temp'])){
    header('Location:../../?students&unregistered');
  }else{
    header('Location:../../?students');

  }
}
