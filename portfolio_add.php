<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error","message"=>"Login required"]); exit; }
$in = json_decode(file_get_contents("php://input"), true);
$title = $conn->real_escape_string($in['title']??"");
$link = $conn->real_escape_string($in['link']??"");
$desc = $conn->real_escape_string($in['description']??"");
$image = $conn->real_escape_string($in['image']??"");
$tag = $conn->real_escape_string($in['tag']??"");

$verified = 0;
$fname = strtolower($image);
if(strpos($fname,"coursera")!==false || strpos($fname,"udemy")!==false || strpos($fname,"nptel")!==false || strpos($fname,"oracle")!==false){
  $verified = 1;
}
$uid = $_SESSION['user_id'];
$q = "INSERT INTO portfolio (user_id,title,link,description,image,tag,verified_cert) VALUES ($uid,'$title','$link','$desc','$image','$tag',$verified)";
if($conn->query($q)){ echo json_encode(["status"=>"success","message"=>"Portfolio item added"]); }
else{ echo json_encode(["status"=>"error","message"=>"DB error"]); }
