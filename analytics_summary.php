<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode([]); exit; }
$uid=$_SESSION['user_id'];
$total_posts = $conn->query("SELECT COUNT(*) c FROM posts WHERE user_id=$uid")->fetch_assoc()['c'] ?? 0;
$total_portfolio = $conn->query("SELECT COUNT(*) c FROM portfolio WHERE user_id=$uid")->fetch_assoc()['c'] ?? 0;
$total_skills = $conn->query("SELECT COUNT(*) c FROM skills WHERE user_id=$uid")->fetch_assoc()['c'] ?? 0;

$rows = [];
for($i=6;$i>=0;$i--){
  $day = date('Y-m-d', strtotime("-$i days"));
  $c = $conn->query("SELECT COUNT(*) c FROM posts WHERE user_id=$uid AND DATE(created_at)='$day'")->fetch_assoc()['c'] ?? 0;
  $rows[] = ["day"=>$day, "count"=>(int)$c];
}
echo json_encode([
  "total_posts"=>(int)$total_posts,
  "total_portfolio"=>(int)$total_portfolio,
  "total_skills"=>(int)$total_skills,
  "posts_by_day"=>$rows
]);
