<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error"]); exit; }
$in = json_decode(file_get_contents("php://input"), true);
$skill = $conn->real_escape_string($in['skill']??"");
$uid = $_SESSION['user_id']; // Self-endorse allowed for demo; in real app, track endorser
$conn->query("UPDATE skills SET endorsements=endorsements+1 WHERE user_id=$uid AND skill_name='$skill'");
echo json_encode(["status"=>"success"]);
