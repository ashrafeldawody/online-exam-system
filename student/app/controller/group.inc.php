<?php
session_start();
require_once '../model/group.class.php';

if (($_GET['action'] == 'joinGroup') && isset($_POST['code'])){
  $group = new group();
  if(!$group->checkCode($_POST['code'])){
    echo "The Code is not valid";
  }elseif($group->alreadyMemeber($_POST['code'])){
    echo "You already a member of this group";
  }else{
  $group->joinGroup($_POST['code']);
  echo 'success';
  }
}else if (($_GET['action'] == 'leaveGroup') && isset($_POST['id'])){
  $group = new group();
  if(!is_numeric($_POST['id'])){
    echo 'Group ID is not valid';
  }else{
  $group->leaveGroup($_POST['id']);
  echo 'success';
  }
}
