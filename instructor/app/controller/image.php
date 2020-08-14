<?php
session_start();
if(isset($_SESSION['mydata']) and isset($_GET['i'])){
header('Content-type: image/jpeg');
$image = file_get_contents('../../../style/images/uploads/'. $_GET['i'] . '.jpg');
echo $image;
}
?>