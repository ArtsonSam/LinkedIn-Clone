<?php
session_start(); header("Access-Control-Allow-Origin: *");
$dir = "../uploads/"; if(!file_exists($dir)) mkdir($dir,0777,true);
if(!isset($_FILES['image'])) { echo json_encode(["status"=>"error","message"=>"No file"]); exit; }
$clean = time()."_".preg_replace("/[^A-Za-z0-9._-]/","_", $_FILES['image']['name']);
$path = $dir.$clean;
if(move_uploaded_file($_FILES['image']['tmp_name'], $path)){
  echo json_encode(["status"=>"success","filename"=>$clean]);
}else{ echo json_encode(["status"=>"error","message"=>"Upload failed"]); }
