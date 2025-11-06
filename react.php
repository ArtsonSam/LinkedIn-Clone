<?php
session_start();
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data["post_id"];
$reaction = $data["reaction"];

$sql = "SELECT reactions FROM posts WHERE id=$post_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$reactions = json_decode($row["reactions"], true);

$reactions[$reaction] += 1;
$updated = json_encode($reactions);

$conn->query("UPDATE posts SET reactions='$updated' WHERE id=$post_id");
echo json_encode(["status"=>"success","reactions"=>$reactions]);
?>
