<?php
session_start();
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['post_id'];
$content = $data['content'];
$user_id = $_SESSION['user_id'];

$sql = "UPDATE posts SET content='$content' WHERE id='$post_id' AND user_id='$user_id'";
if($conn->query($sql)){
    echo json_encode(["status"=>"success","message"=>"Post updated"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Update failed"]);
}
?>
