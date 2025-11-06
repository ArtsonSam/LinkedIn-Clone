<?php
header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php"; $skill = isset($_GET['skill'])?$conn->real_escape_string($_GET['skill']):"";
$sql = "SELECT * FROM jobs ".($skill?"WHERE skills LIKE '%$skill%' ":"")."ORDER BY created_at DESC";
$r=$conn->query($sql); $out=[]; while($row=$r->fetch_assoc()) $out[]=$row; echo json_encode($out);
