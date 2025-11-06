<?php
session_start(); header("Content-Type:text/html; charset=UTF-8");
include "config.php"; if(!isset($_SESSION['user_id'])){ echo "Login required"; exit; }
$uid = $_SESSION['user_id'];
$u = $conn->query("SELECT name,email FROM users WHERE id=$uid")->fetch_assoc();
$skills=[]; $r=$conn->query("SELECT skill_name,endorsements FROM skills WHERE user_id=$uid");
while($x=$r->fetch_assoc()) $skills[]=$x;
$projects=[]; $p=$conn->query("SELECT title,link,description,tag,verified_cert FROM portfolio WHERE user_id=$uid ORDER BY created_at DESC");
while($x=$p->fetch_assoc()) $projects[]=$x;
?>
<!DOCTYPE html><html><head><meta charset="utf-8">
<title>Resume - <?=htmlspecialchars($u['name'])?></title>
<style>
body{font-family:Arial, sans-serif; margin:30px;}
h1{margin:0;} .muted{color:#666}
.section{margin-top:20px;}
.badge{display:inline-block;padding:2px 8px;border:1px solid #999;border-radius:999px;margin:2px;}
</style>
</head>
<body>
  <h1><?=htmlspecialchars($u['name'])?></h1>
  <div class="muted"><?=htmlspecialchars($u['email'])?></div>

  <div class="section">
    <h3>Skills</h3>
    <?php foreach($skills as $s){ ?>
      <span class="badge"><?=htmlspecialchars($s['skill_name'])?> • 🔥 <?=$s['endorsements']?></span>
    <?php } ?>
  </div>

  <div class="section">
    <h3>Projects & Certificates</h3>
    <?php foreach($projects as $pr){ ?>
      <div>
        <b><?=htmlspecialchars($pr['title'])?></b>
        <?php if($pr['verified_cert']) echo " ✅"; ?>
        <?php if($pr['tag']) echo " — ".htmlspecialchars($pr['tag']); ?>
        <?php if($pr['link']) echo ' — <a href="'.htmlspecialchars($pr['link']).'">'.htmlspecialchars($pr['link']).'</a>'; ?>
        <div class="muted"><?=nl2br(htmlspecialchars($pr['description']))?></div>
      </div>
      <hr>
    <?php } ?>
  </div>
  <script>window.print();</script>
</body></html>
