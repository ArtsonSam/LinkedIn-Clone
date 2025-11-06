<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error"]); exit; }
$in=json_decode(file_get_contents("php://input"),true);
$t=$conn->real_escape_string($in['title']??""); $c=$conn->real_escape_string($in['company']??"");
$l=$conn->real_escape_string($in['location']??""); $s=$conn->real_escape_string($in['skills']??"");
$d=$conn->real_escape_string($in['description']??""); $uid=$_SESSION['user_id'];
if(!$t){ echo json_encode(["status"=>"error","message"=>"Title required"]); exit; }
$conn->query("INSERT INTO jobs (title,company,location,description,skills,posted_by) VALUES ('$t','$c','$l','$d','$s',$uid)");
echo json_encode(["status"=>"success","message"=>"Job posted"]);
