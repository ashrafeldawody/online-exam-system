<?php
session_start();
include_once 'autoloader.inc.php';
if($_SESSION['mydata']->isAdmin){
if (isset($_GET['suspendStudent'])){
$_admin = new admin();
$_admin->suspendStudent($_GET['suspendStudent']);
header('Location: ' . $_SERVER['HTTP_REFERER']);
}elseif (isset($_GET['activateStudent'])){
  $_admin = new admin();
  $_admin->activateStudent($_GET['activateStudent']);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}elseif (isset($_GET['suspendInstructor'])){
$_admin = new admin();
$_admin->suspendInstructor($_GET['suspendInstructor']);
header('Location: ' . $_SERVER['HTTP_REFERER']);
}elseif (isset($_GET['activateInstructor'])){
  $_admin = new admin();
  $_admin->activateInstructor($_GET['activateInstructor']);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}else if (isset($_GET['createInvites'])){
  $count = !empty($_POST['count']) ? trim($_POST['count']) : null;
  $_admin = new admin();
  $_admin->generateInvitations($count);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}else if (isset($_GET['clearInvites'])){
  $_admin = new admin();
  $_admin->deleteInvitations();
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}else if (isset($_GET['addStudent'])){
  print_r($_POST);
  $id = is_numeric($_POST['studentID']) ? $_POST['studentID'] : null;
  $name = is_numeric($_POST['studentName']) ? $_POST['studentName'] : null;
  if(($id != null) and ($name != null)){
    $_admin = new admin();
    $_admin->addStudent($id,$name);
  }
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}else if (isset($_GET['exportStudents'])){
  $_admin = new admin();
  $students = $_admin->getAllStudents();
  $data = array("student ID, Name, Email Address, Phone Number");
  foreach($students as $std){
    $line = $std->id . ',' . $std->name . ',' . $std->email . ',' . $std->phone;
    array_push($data,$line);
  }
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="students.csv"');


  $fp = fopen('php://output', 'wb');
  foreach ( $data as $line ) {
      $val = explode(",", $line);
      fputcsv($fp, $val);
  }
  fclose($fp);


}elseif (isset($_GET['importStudents'])){
  $excelFile = $_FILES['excel']['tmp_name'];
  $inputFileType = 'Xlsx';
  class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
  {
      private $startRow = 0;
      private $endRow   = 0;
      private $columns  = [];

      public function __construct($startRow, $endRow, $columns) {
          $this->startRow = $startRow;
          $this->endRow   = $endRow;
          $this->columns  = $columns;
      }

      public function readCell($column, $row, $worksheetName = '') {
          if ($row >= $this->startRow && $row <= $this->endRow) {
              if (in_array($column,$this->columns)) {
                  return true;
              }
          }
          return false;
      }
  }
  $filterSubset = new MyReadFilter(2,1000,range('A','B'));
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
  $reader->setReadFilter($filterSubset);
  $spreadsheet = $reader->load($excelFile);
  $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
  $sheetData = array_filter($sheetData, function($v) { return implode('', $v) !== ''; });
  foreach($sheetData as $row){
    $id = is_numeric($row['A']) ? $row['A'] : 0;
    $name = (strlen($row['B']) > 10? $row['B'] : null);
    $students[] = '('. $id . ',"' . $name . '")';
    $values = implode(', ', $students);
  }
  if(!empty($values)){
         $_admin = new admin();
         $_admin->importStudents($values);
         header('Location: ../../?students&succImp=1');
         exit;
       }
    header('Location: ../../?students&succImp=0');
  exit;

}
}else{
  header('Location: /');
}
