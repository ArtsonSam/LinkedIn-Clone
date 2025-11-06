<?php
session_start();
header("Access-Control-Allow-Origin: *");
include "config.php";

$targetDir = "../uploads/";
if(!file_exists($targetDir)) mkdir($targetDir, 0777, true);

if(isset($_FILES['image'])){
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)){
        echo json_encode(["status"=>"success","filename"=>$fileName]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Upload failed"]);
    }
}
?>
