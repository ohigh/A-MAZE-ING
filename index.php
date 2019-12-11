<?php
require_once '/includes/head.php';
?>

<body>
  <div class="navbar-fixed">
    <nav>
      <div class="nav-wrapper container">
        <?php if (secCheckLevel() >= 60) { ?>

          <ul id="dropdown1" class="dropdown-content">
            <li><a href="index.php?side=profil#profil">Min Profil</a></li>
            <li class="divider"></li>
            <li><a href="admin/index.php">Administation</a></li>

          </ul>
        <?php } ?>
        <ul>


          <?php
          if (secIsLoggedIn()) {
            echo '<li class="right user-nav d-in-flex"><a class="dropdown-button" href="#!" data-activates="dropdown1"><i class="material-icons">account_circle</i>' . @$resultat->profile_firstname . '</a></li>';
            echo '<li class="right d-in-flex login-out"><a href="index.php?side=logud"><i class="material-icons">lock_outline</i>Log ud</a></li>';
          } else {
            echo '<li class="right d-in-flex"><a href="index.php?side=forside&opret"><i class="material-icons pulse">person_add</i>Opret</a></li>';
            echo '<li class="right d-in-flex"><a href="index.php?side=forside&logind"><i class="material-icons">lock_open</i>Log ind</a></li>';
          }

          ?>
        </ul>


        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="nav-ul hide-on-med-and-down">
          <li><a <?= ($get['side'] == 'amazeing') ? 'class="nav-active"' : '' ?> href="?side=amazeing#game">A-MAZE-ING</a></li>
          <li><a <?= ($get['side'] == 'games') ? 'class="nav-active"' : '' ?> href="?side=games#game">Alle spil</a></li>
          <li><a <?= ($get['side'] == 'nyheder') ? 'class="nav-active"' : '' ?> href="?side=nyheder#news">Nyheder</a></li>
          <li><a <?= ($get['side'] == 'profil') ? 'class="nav-active"' : '' ?> <?php if (secIsLoggedIn()) {
                                                                                  echo 'href="?side=profil#profil">Min Side</a></li>';
                                                                                } else {
                                                                                  echo 'href="?side=forside&minSisdeLogind">Min Side</a></li>';
                                                                                } ?> <li><a <?= ($get['side'] == 'kontakt') ? 'class="nav-active"' : '' ?> href="?side=kontakt#contact">Kontakt</a></li>

        </ul>
        <ul class="side-nav" id="mobile-demo">
          <li><a <?= ($get['side'] == 'amazeing') ? 'class="nav-active"' : '' ?> href="?side=amazeing#game">A-MAZE-ING</a></li>
          <li><a <?= ($get['side'] == 'games') ? 'class="nav-active"' : '' ?> href="?side=games#game">Alle spil</a></li>
          <li><a <?= ($get['side'] == 'nyheder') ? 'class="nav-active"' : '' ?> href="?side=nyheder#news">Nyheder</a></li>
          <li><a <?= ($get['side'] == 'profil') ? 'class="nav-active"' : '' ?> <?php if (secIsLoggedIn()) {
                                                                                  echo 'href="?side=profil#profil">Min Side</a></li>';
                                                                                } else {
                                                                                  echo 'href="?side=forside&minSisdeLogind">Min Side</a></li>';
                                                                                } ?> <li><a <?= ($get['side'] == 'kontakt') ? 'class="nav-active"' : '' ?> href="?side=kontakt#contact">Kontakt</a></li>
        </ul>
      </div>
    </nav>
  </div>
  <header>
    <!-- video -->
    <video autoplay muted loop id="headerVideo">
      <source src="assets/video/header-ani-DARK.mp4" type="video/mp4">
    </video>

    <!-- Optional: overlay -->

    <?php
    if (isset($_GET['logind'])) { ?>
      <div class="content">
        <div class="container">
          <div class="row">
            <div class="col s12 m6 l4 offset-m3 offset-l4">
            <?php
              include '/plugin/logind.php';
            } else if (isset($_GET['minSisdeLogind'])) { ?>
              <div class="content">
                <div class="container">
                  <div class="row">
                    <div class="col s12 m6 l4 offset-m3 offset-l4">
                    <?php
                      include '/plugin/minLogind.php';
                    } else if (isset($_GET['opret'])) { ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="content">
                <div class="container">
                  <div class="row">
                    <div class="col s12 m10 l8 offset-m1 offset-l2">
                    <?php
                      include '/plugin/opretBruger.php';
                    } else if (isset($_GET['play'])) { ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="game-wrapper">
                <?php
                  $gameId = $_SESSION['game'];
                  $sql = "SELECT game_url FROM tbl_game
                             WHERE game_id = :id";
                  $stmt = $conn->prepare($sql);
                  $stmt->bindParam(':id', $gameId, PDO::PARAM_INT);
                  $stmt->execute();
                  $resultat = $stmt->fetch(PDO::FETCH_OBJ);
                  print_r($resultat->game_url)
                  ?>
                <embed src="<?= $resultat->game_url ?>">
              </div>
            <?php
            } else if (isset($_GET['ret-profil'])) { ?>
              <div class="content">
                <div class="container">
                  <div class="row">
                    <div class="col s12">
                    <?php
                      include '/plugin/retBruger.php';
                    } else if (isset($_GET['opret-spil'])) { ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="content">
                <div class="container">
                  <div class="row">
                    <div class="col s12">
                    <?php
                      include '/plugin/opretSpil.php';
                    } else if (isset($_GET['opret-nyhed'])) { ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="content">
                <div class="container">
                  <div class="row">
                    <div class="col s12">
                    <?php
                      include '/plugin/opretNyhed.php';
                    }
                    ?>

  </header>
  <main>
    <div class="container">
      <div class="row">
        <?php
        if (isset($_GET['side'])) {
          $page = 'plugin/' . $_GET['side'] . '.php';
          if (file_exists($page)) {
            include $page;
          } else {
            //404
            header('Location: index.php?side=findesIkke#404');
          }
        } else {
          header('Location: index.php?side=forside#frontpage');
          echo 'FEJL!!!!!!!!!!!';
        }
        ?>
      </div>
    </div>


  </main>

  <?php
  include './includes/footer.php';
  ?>