<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error"]); exit; }
$in = json_decode(file_get_contents("php://input"), true);
$to = $_SESSION['user_id']; $from = $conn->real_escape_string($in['from_name']??"Recruiter");
$msg = $conn->real_escape_string($in['message']??"");
if(!$msg){ echo json_encode(["status"=>"error","message"=>"Empty"]); exit; }
$conn->query("INSERT INTO feedback (to_user_id, from_name, message) VALUES ($to,'$from','$msg')");
echo json_encode(["status"=>"success","message"=>"Thanks for your feedback!"]);
