<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['post_id'];
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM posts WHERE id='$post_id' AND user_id='$user_id'";
if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "Post deleted"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete post"]);
}
?>