<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$content = $data['content'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO posts (user_id, content) VALUES ('$user_id', '$content')";
if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "Post added"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to create post"]);
}
?>