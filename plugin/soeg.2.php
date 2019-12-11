<?php
if (isset($_SESSION['searchText']) && !empty($_SESSION['searchText'])) {
    
    $date = date("Y-m-d H:i:s",time() - (7 * 24 * 60 * 60));
    $limit = 5;

    if (!isset($_GET['res'])
    || !is_numeric($_GET['res'])
    || $_GET['res'] < 1) {
        $page = 1;
    } else {
        $page = $_GET['res'];
    }

    $offset = ($page-1)*$limit;
if (isset($_SESSION['searchText']) && !empty($_SESSION['searchText'])) {
        
    // print_r($_POST['searchText']);
    $searchText = $_SESSION['searchText'];
    $sql = "SELECT `news_title`, `news_content`, news_created  FROM `tbl_news` 
            WHERE ((news_created > '$date') AND (news_title LIKE '%$searchText%' OR news_content LIKE '%$searchText%'))"; 
            

    $result = $conn->query($sql);

    $totalResults = $result->rowCount();

    $totalPages = ceil($totalResults / $limit);
    // SØGERESULTAT
?>
    <div class="col s12 m8 l8">
     <div class="row">
     <h5>Søgeresultater</h5>    
<?php    

    $sql = "SELECT `news_title`, `news_content`, `news_created`, `news_id` FROM `tbl_news` 
            WHERE ((news_created > '$date') AND (news_title LIKE '%$searchText%' OR news_content LIKE '%$searchText%')) ORDER BY news_created DESC LIMIT :offset,:max ";


    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':max', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    // $stmt->bindParam(':searchText', '%'. $searchText. '%', PDO::PARAM_INT);
    $stmt->execute();

    foreach($stmt->fetchAll() as $row) {
// DATO
$dag = date('d', strtotime($row['news_created']));
$maaneder = array("Jannuar","Februar","Marts","April","Maj","Juni","Juli","August","Oktober","September","November","Decmber",);
$pos = date('n', strtotime($row['news_created']))-1; //Er det i Marts giver date funktionen 3, trækkes 1 fra har vi positionen i arrayet ovenfor
$aar = date('Y', strtotime($date));
?>
      <div class="card ">
        <div class="card-content clearfix">
          <span class="card-title "><?=$row['news_title']?></span>
          <p class="date">Nyhed fra den <?=$dag.'. '.$maaneder[$pos].' '.$aar?></p>
          <p><?=htmlspecialchars_decode(substr($row['news_content'], 0, 100))?>...</p>
          <br>
          <?php if($row['news_created'] > $date) {echo'
          <a class="right button-news btn" href="?side=nyheder&nyhedVis&id='.$row['news_id'].'">Læs mere...</a>
          ';} else {echo '<h6 class="right blue-text">Resultatet er forældet!</h6>';}?>
        </div>
      </div>
    
<?php
    }
}     
}
if (1 < $page) {
    
    echo "<ul class='pagination left'>";
    echo "<li><a href='?side=soeg.2&res=".($page-1)."'><< Første resultater</a></li>"; 
    echo "</ul>";   
}
//     for ($i=1; $i<=$totalPages; $i++) {  
//         echo "<li><a href='?side=soeg&res=".$i."'>".$i."</a></li>";
// }; 

if ($totalPages > $page) {
    echo "<ul class='pagination right'>";
    echo "<li><a href='?side=soeg.2&res=".($page+1)."'>Flere Resultater >></a></li>";
    echo "</ul>";   
}
?>
     </div>
    </div>
    <div class="col s12 m4 l4 sidebar ">
        <h5>Nyhedsbrev</h5>
        <form action=""  class="search">
          <button class="button" method="post" type="submit">Tilmeld</button>
          <input  type="text" placeholder="E-mail..." value="<?=@$resultat->user_email?>" required>
        </form>
    <?php
          if(isset($_GET['logind'])){
	      	
	      			include './plugin/logind.php';
              }
    ?>
    </div>
