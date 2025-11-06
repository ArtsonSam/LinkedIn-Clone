<?php
session_start(); header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo json_encode([]); exit; }
$uid = $_SESSION['user_id'];
$tag = isset($_GET['tag']) ? $conn->real_escape_string($_GET['tag']) : "";
$sql = "SELECT * FROM portfolio WHERE user_id=$uid".($tag?" AND tag LIKE '%$tag%'":"")." ORDER BY created_at DESC";
$r = $conn->query($sql); $out=[]; while($row=$r->fetch_assoc()) $out[]=$row; echo json_encode($out);
