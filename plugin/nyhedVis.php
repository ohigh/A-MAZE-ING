<?php

$id = $get['id'];
$date = date("Y-m-d H:i:s",time() - (5 * 12 * 7 * 24 * 60 * 60));

$sql = "SELECT * FROM tbl_news WHERE news_created > '$date' AND news_id = :id ORDER BY news_created DESC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

foreach($stmt->fetchAll() as $row) {
// DATO
    $dag = date('d', strtotime($row['news_created']));
    $maaneder = array("Jannuar","Februar","Marts","April","Maj","Juni","Juli","August","Oktober","September","November","Decmber",);
    $pos = date('n', strtotime($row['news_created']))-1; //Er det i Marts giver date funktionen 3, trækkes 1 fra har vi positionen i arrayet ovenfor
    $aar = date('Y', strtotime($row['news_created']));
?>

  <div class="card ">
    <div class="card-content">
      <span class="card-title "><?=$row['news_title']?></span>
      <p class="date"><?=$dag.'. '.$maaneder[$pos].' '.$aar?></p>
      <p><?=htmlspecialchars_decode($row['news_content'])?></p>
    </div>
    <div class="card-action clearfix">
      <a class="right button-news btn" href="?side=nyheder#news">Tilbage...</a>
    </div>
  </div>

<?php
}