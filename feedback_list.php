<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode([]); exit; }
$uid = $_SESSION['user_id']; $r=$conn->query("SELECT * FROM feedback WHERE to_user_id=$uid ORDER BY created_at DESC");
$out=[]; while($row=$r->fetch_assoc()) $out[]=$row; echo json_encode($out);
