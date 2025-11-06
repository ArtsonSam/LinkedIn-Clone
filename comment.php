<?php
session_start();
include "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['post_id'];
$user_id = $_SESSION['user_id'];
$comment = trim($data['comment']);

/* Only allow positive comments */
$negative = ["bad","hate","worst","angry","ugly"];
foreach($negative as $word){
    if(stripos($comment, $word) !== false){
        echo json_encode(["status"=>"error","message"=>"Only positive comments allowed!"]);
        exit;
    }
}

$sql = "INSERT INTO comments (post_id,user_id,comment) VALUES ('$post_id','$user_id','$comment')";
if($conn->query($sql)){
    echo json_encode(["status"=>"success","message"=>"Comment added"]);
}
?>
