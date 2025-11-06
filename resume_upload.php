<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
$targetDir = "../uploads/resumes/";
if(!file_exists($targetDir)) mkdir($targetDir, 0777, true);

if(isset($_FILES['resume'])){
    $fileName = time() . "_" . basename($_FILES["resume"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    if(move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFilePath)){
        echo json_encode(["status"=>"success","filename"=>$fileName]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Upload failed"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"No file uploaded"]);
}
