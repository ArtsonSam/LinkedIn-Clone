<?php
header("Access-Control-Allow-Origin:*"); header("Content-Type:application/json");
include "config.php";
$skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : "";
$sql = "
SELECT u.id, u.name, u.email,
       GROUP_CONCAT(DISTINCT s.skill_name ORDER BY s.endorsements DESC SEPARATOR ', ') AS skills,
       COUNT(DISTINCT p.id) as posts_count
FROM users u
LEFT JOIN skills s ON s.user_id = u.id
LEFT JOIN posts p ON p.user_id = u.id
".($skill ? "WHERE s.skill_name LIKE '%$skill%'" : "")."
GROUP BY u.id, u.name, u.email
ORDER BY posts_count DESC, u.name ASC
LIMIT 100";
$r=$conn->query($sql); $out=[]; while($row=$r->fetch_assoc()) $out[]=$row; echo json_encode($out);
