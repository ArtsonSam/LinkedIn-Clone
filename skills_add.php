<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error"]); exit; }
$in = json_decode(file_get_contents("php://input"), true);
$skill = $conn->real_escape_string(trim($in['skill']??""));
if(!$skill){ echo json_encode(["status"=>"error","message"=>"No skill"]); exit; }
$uid = $_SESSION['user_id'];
$conn->query("INSERT IGNORE INTO skills (user_id, skill_name, endorsements) VALUES ($uid,'$skill',0)");
echo json_encode(["status"=>"success","message"=>"Skill saved"]);
