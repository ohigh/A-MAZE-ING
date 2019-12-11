<?php
$date = date("Y-m-d H:i:s", time() - (2 * 52 * 7 * 24 * 60 * 60));
$limit = 3;

if (
  !isset($_GET['res'])
  || !is_numeric($_GET['res'])
  || $_GET['res'] < 1
) {
  $page = 1;
} else {
  $page = $_GET['res'];
}

$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM tbl_news WHERE news_created > '$date'";

$result = $conn->query($sql);

$totalResults = $result->rowCount();

$totalPages = ceil($totalResults / $limit);




$sql = "SELECT * FROM tbl_news
        INNER JOIN tbl_media
        ON fk_media_news = news_id
        WHERE news_created > '$date' ORDER BY news_created DESC LIMIT :offset,:max ";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':max', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

foreach ($stmt->fetchAll() as $row) {
  // DATO
  $dag = date('d', strtotime($row['news_created']));
  $maaneder = array("Jannuar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "Oktober", "September", "November", "Decmber",);
  $pos = date('n', strtotime($row['news_created'])) - 1; //Er det i Marts giver date funktionen 3, trækkes 1 fra har vi positionen i arrayet ovenfor
  $aar = date('Y', strtotime($row['news_created']));
  ?>
  <div class="card horizontal">
    <div class="card-image">
      <img src="media/<?=$row['media_path']?>">
    </div>
    <div class="card-stacked">
      <div class="card-content">
        <span class="card-title "><?= $row['news_title'] ?></span>
        <p class="date"><?= $dag . '. ' . $maaneder[$pos] . ' ' . $aar ?></p>
        <p><?= htmlspecialchars_decode(substr($row['news_content'], 0, 200)) ?>...</p>
      </div>
      <div class="card-action">
        <a class="right button-news btn" href="?side=nyheder&nyhedVis&id=<?= $row['news_id'] ?>#news">Læs mere...</a>
      </div>
    </div>
  </div>
  <?php
  }

  if (1 < $page) {

    echo "<ul class='pagination left'>";
    echo "<li><a href='?side=nyheder&res=" . ($page - 1) . "#news'><< Nyere nyheder</a></li>";
    echo "</ul>";
  }
  //     for ($i=1; $i<=$totalPages; $i++) {  
  //         echo "<li><a href='?side=nyheder&res=".$i."'>".$i."</a></li>";
  // }; 

  if ($totalPages > $page) {
    echo "<ul class='pagination right'>";
    echo "<li><a href='?side=nyheder&res=" . ($page + 1) . "#news'>Ældre nyheder >></a></li>";
    echo "</ul>";
  }

  //     if ($totalPages > $page) {

  //         echo "<ul class='pagination'>";
  // echo "<li><a href='?side=nyheder&res=".($page-1)."'><< Nyere nyheder</a></li>"; 

  // for ($i=1; $i<=$totalPages; $i++) {  
  //     echo "<li><a href='?side=nyheder&res=".$i."'>".$i."</a></li>";
  // };  

  // echo "<li><a href='?side=nyheder&res=".($page+1)."'>Ældre nyheder >></a></li>";
  // echo "</ul>";   



  //     }
  ?>