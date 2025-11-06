<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error"]); exit; }
$dir = "../uploads/videos/"; if(!file_exists($dir)) mkdir($dir,0777,true);
if(!isset($_FILES['video'])){ echo json_encode(["status"=>"error","message"=>"No file"]); exit; }
$name = time()."_".preg_replace("/[^A-Za-z0-9._-]/","_", $_FILES['video']['name']);
if(move_uploaded_file($_FILES['video']['tmp_name'], $dir.$name)){
  $uid = $_SESSION['user_id'];
  $conn->query("INSERT INTO videos (user_id, filename) VALUES ($uid,'$name')");
  echo json_encode(["status"=>"success","message"=>"Video uploaded"]);
}else{ echo json_encode(["status"=>"error","message"=>"Upload failed"]); }
