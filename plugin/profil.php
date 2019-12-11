<?php
if (secCheckLevel() > 9) {
    $userId = $_SESSION['userid'];
    // print_r($_SESSION['userid']);

    $stmt = $conn->prepare("SELECT `profile_id`, `profile_firstname`, `profile_sirname`, `profile_username`, `profile_age`, 
                                   `user_email`,`media_path`
                            FROM tbl_user 
                            INNER JOIN tbl_profile
							ON `tbl_user`.`fk_user_profile` = `tbl_profile`.`profile_id`
                            LEFT JOIN `tbl_media` 
                            ON `tbl_profile`.`fk_profile_media` = `tbl_media`.`media_id`  
                            
                            WHERE user_id = :id ");
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (is_file('assets/images/' . $row['media_path'])) {
        $profilImg = 'assets/images/' . $row['media_path'];
    } else {
        $profilImg = 'assets/images/profile_placeholder.jpg';
    }
    $bithdayDate = $row['profile_age'];
    $age = floor((time() - strtotime($bithdayDate)) / 31556926);
} else {
    echo '<h5>Du har ikke brugeradgang!</h5>';
    $page = 'index.php?side=forside&logind';
    $sec = "5";
    header("Refresh: $sec; url=$page");
    die();
}

?>
<section id="profil">
<div class="row">
    
    <div class="col s12">

        <div class="card">
            <div class="fixed-action-btn horizontal click-to-toggle p-relative">
                <a class="btn-floating btn-large blue pulse"><i class="large material-icons">add</i></a>
                <ul>
                    <li><a href="?side=forside&ret-profil" class="btn-floating blue tooltipped" data-position="bottom" data-delay="50" data-tooltip="Ret din profil"><i class="material-icons">mode_edit</i></a></li>
                    <li><a href="?side=forside&opret-spil" class="btn-floating blue tooltipped" data-position="bottom" data-delay="50" data-tooltip="Opret dit eget spil"><i class="material-icons">publish</i></a></li>
                    <li><a href="?side=forside&opret-nyhed" class="btn-floating blue tooltipped" data-position="bottom" data-delay="50" data-tooltip="Opret en nyhed"><i class="material-icons">text_fields</i></a></li>
                </ul>
            </div>
            <div class="card-content">
                <span class="card-title">Min Profil</span>
                <div class="row">
                    <div class="col s12 m12">
                        <!-- <div class="row"> -->
                        <table class="tg">
                            <tr>
                                <td class="tg-0pky" rowspan="3"><img src="<?= $profilImg ?>" alt="avatar" class="responsive-img" max-width="90px"></td>
                                <td class="tg-fymr">Navn:</td>
                                <td class="tg-0pky"><?= $row["profile_firstname"] . ' ' . $row["profile_sirname"] ?></td>
                                <td class="tg-fymr">Gamer navn:</td>
                                <td class="tg-0pky"><?= $row["profile_username"] ?></td>

                            </tr>
                            <tr>
                                <td class="tg-fymr">Email:</td>
                                <td class="tg-0pky"><?= $row["user_email"] ?></td>
                                <td class="tg-0pky"></td>
                            </tr>
                            <tr>
                                <td class="tg-fymr">Alder:</td>
                                <td class="tg-0pky"><?= $age . ' År' ?></td>
                                <td class="tg-fymr">Gamer type:</td>
                                <td class="tg-0pky"><?php if ($age < 15) {
                                                        echo "Kid";
                                                    } else if ($age > 14 && $age < 25) {
                                                        echo "Youngster";
                                                    } else if ($age > 24) {
                                                        echo "Oldtimer";
                                                    } ?></td>
                                </td>
                                <td class="tg-0pky"></td>
                            </tr>
                            <tr>
                            <td class="tg-0pky" colspan="5" class="divider"></td>
                            </tr>
                            <tr>
                                <td class="tg-0pky"></td>
                                <td class="tg-fymr" colspan="4">Din spil aktivitet</td>
                            </tr>

                            <tr>
                                <td class="tg-0pky"></td>
                                <td class="tg-fymr" colspan="1">Du har senest spillet:</td>
                                <td class="tg-0pky"><?php
                                                    $stmt = $conn->prepare('SELECT game_name, entrant_date, result_score
                                                              FROM tbl_game
                                                              INNER JOIN tbl_entrant
                                                              ON game_id = fk_entrant_game
                                                              LEFT JOIN tbl_result
                                                              ON result_id = fk_entrant_result
                                                              WHERE fk_entrant_user = :id
                                                              ORDER BY entrant_date DESC');
                                                    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    if ($stmt->rowCount() > 0) {
                                                        $dag = date('d', strtotime($row['entrant_date']));
                                                        $maaneder = array("Jannuar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "Oktober", "September", "November", "Decmber",);
                                                        $pos = date('n', strtotime($row['entrant_date'])) - 1; //Er det i Marts giver date funktionen 3, trækkes 1 fra har vi positionen i arrayet ovenfor
                                                        $aar = date('Y', strtotime($row['entrant_date']));
                                                        echo '<p>' . $row['game_name'] . '</p></td>';
                                                        echo '<td class="tg-fymr">Dato:</td>';
                                                        echo '<td><p>' . $dag . '. ' . $maaneder[$pos] . ' ' . $aar . '</p></td>';
                                                        // print_r($row);
                                                    } else {
                                                        echo '<p>Du har ikke spillet endnu!</p></td>';
                                                    }
                                                    ?>
                                <td class="tg-0pky"></td>
                            </tr>
                            <tr>
                            <td class="tg-0pky" colspan="5" class="divider"></td>
                            </tr>
                            <tr>
                            <td class="tg-0pky"></td>
                                <td class="tg-fymr" colspan="4">Dine A-MAZE-ING (The Game) resultater</td>
                            </tr>
                            <tr>
                                <td class="tg-0pky"></td>
                                <td class="tg-fymr">Din seneste totalscore:</td>
                                <td class="tg-0pky"><?php
                                                    $stmt = $conn->prepare('SELECT game_name, entrant_date, result_score
                                FROM tbl_game
                                INNER JOIN tbl_entrant
                                ON game_id = fk_entrant_game
                                LEFT JOIN tbl_result
                                ON result_id = fk_entrant_result
                                WHERE fk_entrant_user = :id AND game_name = "A-MAZE-ING (The Game)"
                                ORDER BY entrant_date DESC');
                                                    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    if ($row['result_score'] == 0) {
                                                        echo 'Du gennemførte desværre ikke spillet sidst!';
                                                    } else {
                                                        echo $row['result_score'];
                                                    } ?></td>
                                <td class="tg-fymr">Din højeste totalscore:</td>
                                <td class="tg-0pky"><?php
                                                    $stmt = $conn->prepare('SELECT game_name, entrant_date, result_score
                                                              FROM tbl_game
                                                              INNER JOIN tbl_entrant
                                                              ON game_id = fk_entrant_game
                                                              INNER JOIN tbl_result
                                                              ON result_id = fk_entrant_result
                                                              WHERE fk_entrant_user = :id
                                                              ORDER BY result_score DESC');
                                                    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    if ($stmt->rowCount() > 0 && $row['result_score'] > 0) {

                                                        echo '<p>' . $row['result_score'] . '</p></td>';
                                                    } else {
                                                        echo '<p>Du har ikke gennemført spillet endnu!</p></td>';
                                                    }
                                                    ?>

                                <td class="tg-0pky"></td>
                            </tr>
                        </table>




                        <div class="col s5">

                        </div>
                        <div class="col s7"></div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if (isset($_GET['ret-profil'])) { ?>
      <div class="content">
        <div class="container">
          <div class="row">
            <div class="col s12 m6 l4 offset-m3 offset-l4">
            <?php
              include '/plugin/retBruger.php';
            } 
            else if (isset($_GET['minSisdeLogind'])) { ?>
              </div>
                          </div>
                          </div>
                        </div>
              <div class="content">
                <div class="container">
                  <div class="row">
                    <div class="col s12 m6 l4 offset-m3 offset-l4">
                    <?php
                      include '/plugin/minLogind.php';
                    } else if (isset($_GET['opret'])) { ?>
                      <div class="content">
                        <div class="container">
                          <div class="row">
                            <div class="col s12 m10 l8 offset-m1 offset-l2">
                            <?php
                              include '/plugin/opret.php';
                            } else if (isset($_GET['play'])) { ?>
                            </div>
                          </div>

                        </div>
                        <div class="content">
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
                          <!-- <embed src="http://websites.web/cph_game/plugin/maze/index.html"> -->


                        </div>
                      <?php
                      }
                      ?>
</section>