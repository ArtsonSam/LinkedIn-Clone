<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode([]); exit; }
$uid=$_SESSION['user_id']; $r=$conn->query("SELECT filename FROM videos WHERE user_id=$uid ORDER BY created_at DESC LIMIT 1");
echo json_encode($r && $r->num_rows? $r->fetch_assoc(): []);
