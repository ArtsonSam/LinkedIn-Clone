<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode(["status"=>"error"]); exit; }
$in=json_decode(file_get_contents("php://input"),true);
$skill=$conn->real_escape_string($in['skill']??"Skill"); $score=(int)($in['score']??0); $uid=$_SESSION['user_id'];
$conn->query("INSERT INTO skill_scores (user_id, skill, score) VALUES ($uid,'$skill',$score)");
if($score>=4){ // ⭐ badge idea: auto add/endorse when >=80%
  $conn->query("INSERT IGNORE INTO skills (user_id, skill_name, endorsements) VALUES ($uid,'$skill',0)");
  $conn->query("UPDATE skills SET endorsements=endorsements+3 WHERE user_id=$uid AND skill_name='$skill'");
}
echo json_encode(["status"=>"success","message"=>"Score saved"]);
