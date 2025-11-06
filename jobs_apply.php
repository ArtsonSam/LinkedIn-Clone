<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error"]); exit; }
$in=json_decode(file_get_contents("php://input"),true); $jid=(int)($in['job_id']??0);
$msg=$conn->real_escape_string($in['message']??""); $uid=$_SESSION['user_id'];
if(!$jid){ echo json_encode(["status"=>"error","message"=>"No job"]); exit; }
$conn->query("INSERT INTO job_applications (job_id,user_id,message) VALUES ($jid,$uid,'$msg')");
echo json_encode(["status"=>"success","message"=>"Applied"]);
