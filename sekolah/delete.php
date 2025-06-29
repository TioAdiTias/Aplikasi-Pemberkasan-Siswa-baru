<?php
include 'config.php';
header('Content-Type:application/json');
if(!isset($_SESSION['admin'])) exit(json_encode(['status'=>'err']));
$id=(int)$_POST['id'];
if($id){
  $stm=$conn->prepare("DELETE FROM siswa WHERE id=?");
  $stm->bind_param('i',$id); $stm->execute();
  echo json_encode(['status'=>'ok']);
} else echo json_encode(['status'=>'err']);
