<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo (isset($_SESSION['mydata']) ? ($_SESSION['mydata']->isAdmin ? 'Adminstrator Dashboard' : 'Instructor Dashboard'):'Dashboard') ?> - Online Exam System</title>
    <meta name="description" content="Instructor - Online Exam System">
    <meta name="viewport" content="width=650, initial-scale=0.6">
    <link rel="apple-touch-icon" href="../favicon.ico">
    <link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" href="../style/css/icheck-bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/b-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/sl-1.3.1/datatables.min.css"/>
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
		<link rel="stylesheet" type="text/css" href="style/css/summernote-lite.min.css">
		<link rel="stylesheet" type="text/css" href="style/css/instructor.css">
		<link rel="stylesheet" type="text/css" href="style/css/percent.css" />
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://use.fontawesome.com/be6a3729fc.js"></script>

</head>
<body style="min-width:700px">
