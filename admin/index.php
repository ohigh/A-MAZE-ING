<?php
  require_once '../includes/db_conn.php';
  require_once '../includes/security.php';
  require_once '../includes/validate.php';
  require_once '../includes/sqlOperations.php';
  require_once '../includes/helpers.php';

  if (secCheckMethod('POST') || secCheckMethod('GET')) {
    $get = secGetInputArray(INPUT_GET);
    $post = secGetInputArray(INPUT_POST);
    
  }

  if (secIsLoggedIn()) {
    $stmt = $conn->prepare("SELECT `profile_firstname`
                            FROM `tbl_profile`
                            INNER JOIN tbl_user 
                            ON tbl_user.fk_user_profile = tbl_profile.profile_id 
                            WHERE user_id = :id");
    $stmt->bindParam(':id', $_SESSION['userid'], PDO::PARAM_INT);
                            if ($stmt->execute() && ($stmt->rowCount() === 1)) {
                                $resultat = $stmt->fetch(PDO::FETCH_OBJ);
                            }
                   
  } else {
          header('Location: ../index.php?side=admin#admin');
      die();
    }

    if (isset($get['side']) && !empty($get['side'])) {
      $titel = ucwords($get['side']);
    }
?>


<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?=$titel?> - Pagaj</title>
    <!-- Material Icon CDN -->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Materialize CSS CDN -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
    <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Bitter|Peddana&amp;subset=latin-ext,telugu" rel="stylesheet">     
    <!-- CSS -->
      <link rel="stylesheet" href="assets/css/adminStyle.css">
      <link href="assets/css/font-awesome.css" rel="stylesheet" />
      <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">

    <!-- CKEditor CDN -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.8.0/ckeditor.js"></script>
    
  </head>
  <body>
    <header>
      <nav>
      <div class="nav-wrapper container">
				<ul>
          <li class="left"><a <?=($get['side'] == 'dash') ? 'class="nav-active"' : ''?> href="index.php">A-MAZE-ING</a></li>
          <li class="right"><?=' ' .@$resultat->profile_firstname?></li>
          <li class="right"><i class="material-icons">account_circle</i></a></li>
          <?php
                    if (secIsLoggedIn()) {
                      echo '<li class="left"><a href="../index.php?side=forside">Tilbage til hjemmesiden</a></li>';
                      echo '<li class="right"><a href="../index.php?side=logud">Log ud</a></li>';
                    } else {
                      echo '<li class="right"><a href="index.php?side=forside&logind">Log ind</a></li>';
                    }
                    
                    ?>
				</ul>
				
			</div>
      </nav>
      
      <nav class="main-nav">
        <div class="nav-wrapper">
          <div class="container">

            <?php
              if(secCheckLevel() == 60) {
            ?>
            <ul class="small-nav-ul">
              <?php
              } else {
              ?>
            <ul class="nav-ul">
              <?php
              }
              if(secCheckLevel() >= 50) {
                ?>
                <li><a <?=($get['side'] == 'nyheder') ? 'class="nav-active"' : ''?> href="?side=nyheder">Nyheder</a></li>
                  <li><a <?=($get['side'] == 'spil') ? 'class="nav-active"' : ''?> href="?side=spil">Games</a></li>
                <?php
                } if(secCheckLevel() >= 90) {
                ?>
                  <li><a <?=($get['side'] == 'bruger') ? 'class="nav-active"' : ''?>  href="?side=bruger">Medlemmer</a></li>
                  <li><a <?=($get['side'] == 'beskeder') ? 'class="nav-active"' : ''?> href="?side=beskeder">Beskeder</a></li>
                  <li><a <?=($get['side'] == 'abonnenter') ? 'class="nav-active"' : ''?> href="?side=abonnenter">Abonnenter</a></li>
                <?php
                } if(secCheckLevel() == 30) {
                 ?>
                 <li><a <?=($get['side'] == 'profil') ? 'class="nav-active"' : ''?> href="?side=profil">Min Side</a></li>
              <?php
              }//    else {
              //   header('Location: ../index.php');
              //   die();
              // }
              ?>
            </ul>
            
          </div>
        </div>
      </nav>
    </header>
    <main>
      <div class="container">
        <div class="row">
        <?php
    
    if (secCheckMethod('POST') || secCheckMethod('GET')) {
        $get = secGetInputArray(INPUT_GET);
        if (!secIsLoggedIn() && $get['side'] !== 'logind') {
            header('Location: ?side=logind');
            die();
        }
        if (isset($get['side']) && !empty($get['side'])) {
            switch ($get['side']) {
              // Bruger
            case 'opretBruger':
              include_once './plugin/users/opret.php';
            break;
            case 'bruger':
					    include_once './plugin/users/list.php';
					  break;
            case 'sletBruger':
					    include_once './plugin/users/delete.php';
					  break;
            case 'retBruger':
					    include_once './plugin/users/edit.php';
					  break;
            case 'logud':
              include_once '../plugin/logud.php';
            break;
            case 'profil':
                  include_once './plugin/users/profil.php';
            break; 
            // Games
            case 'spil':
              include_once './plugin/game/create.php';
            break;
            // Nyheder
            case 'opretNyheder':
					    include_once './plugin/news/create.php';
					  break; 
            case 'nyheder':
					    include_once './plugin/news/list.php';
					  break; 
            case 'sletNyheder':
					    include_once './plugin/news/delete.php';
					  break;
            case 'retNyheder':
					    include_once './plugin/news/edit.php';
            break;
            // Dashboard
            case 'dash':
					    include_once './plugin/dash.php';
					  break;
            // Beskeder
            case 'beskeder':
					    include_once './plugin/message/list.php';
					  break;
            case 'visBesked':
					    include_once './plugin/message/view.php';
            break;
            case 'sletBesked':
              include_once './plugin/message/delete.php';
            break;
            // Abinnenter
            case 'abonnenter':
					    include_once './plugin/newsletter/list.php';
					  break;
            case 'sletAbonnenter':
					    include_once './plugin/newsletter/delete.php';
            break;
            // Standard
            default:
              header('Location: ../index.php?side=404');
            break;    
            }
        } else {
          header('Location: index.php?side=dash');
        }
    }
?>
        
    </main>
    <footer class="page-footer black white-text">
      <!-- <div class="footer-copyright"> -->
        <div class="container">
          <div class="row">
          <div class="col s12 m10 offset-m1">
            <ul>
              <li>Kajakklubben Pagaj</li>
              <li>Loremvej 4</li>
              <li>tlf.22 22 22 22</li>
              <li>mail@adresse.dk</li>
            </ul>
          </div>
          </div>
          
        </div>
      <!-- </div> -->
    </footer>
    <!-- jQuery CDN -->
      <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!-- Materialize JS CDN -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

    <!-- Matrialize Scripts -->
    <script>
      $("document").ready(function(){
        $(".dropdown-button").dropdown({
				belowOrigin: true
			  });
            $('select').material_select();
        $(".button-collapse").sideNav();
        $('.datepicker').pickadate({
          selectMonths: true, // Creates a dropdown to control month
          selectYears: 15, // Creates a dropdown of 15 years to control year,
          today: 'Today',
          clear: 'Clear',
          close: 'Ok',
          closeOnSelect: false // Close upon selecting a date,
        });
      });
    </script>
  </body>
</html>