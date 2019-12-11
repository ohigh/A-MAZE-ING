<?php
$error           = [];

if (!secValidateToken($post['_once'], 600)) {
    $error['session'] = 'Din session er udløbet! Prøv igen.';
}
if (isset($post['play'])) {
    $user     = validIntBetween($post['user'], 1, 128) ? $post['user'] : $error['user'] = 'Fejl i titlen.';
    $event   = validIntBetween($post['game'], 1, 128) ? $post['game'] : $error['game'] = 'Spillet findes ikke!';
    if (sizeof($error) === 0) {
        // sqlQueryPrepared(
        //     "INSERT INTO `tbl_entrant`(`fk_entrant_game`, `fk_entrant_user`) 
        //      VALUES (:user, :events);",
        //     array(
        //         ':user' => $user,
        //         ':events' => $event,
        //     )
        // );
        // 
        $_SESSION['game'] = $event;
        $userId = $_SESSION['userid'];
        $gameId = $_SESSION['game'];
        print_r($userId . ' & ' . $gameId);
        header("Location: ?side=forside&play");
    }
}
foreach ($error as $message) {
    @$msg .= "<p>$message</p>" . PHP_EOL;
}
$tokenInput = secCreateTokenInput();
?>
<section id="game">
    <div class="col s12 ">
        <div class="row">
            <div class="col s12 m6 ">
                <h5> A-MAZE-ING</h5>
            </div>
            <div class="col s12 m6 l5 xl5 offset-l1 offset-xl1">
                <h5> HIGH SCORE - TOP 5</h5>
            </div>
            <div class="row">
                <?php
                // $date = date("Y-m-d");

                $sql = "SELECT * FROM tbl_game
                        INNER JOIN tbl_media
                        ON fk_game_media = media_id 
                        WHERE game_id = 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // DATO

                ?>

                <div class="col s12 m6">
                    <div class="card">
                        <div class="card-image">
                            <img src="assets/images/<?= $row['media_path'] ?>">
                        </div>
                        <div class="card-content">
                            <span class="card-title "><?= $row['game_name'] ?></span>

                            <p><?= htmlspecialchars_decode($row['game_description']) ?></p>
                        </div>
                        <div class="card-action clearfix">
                            <?php
                            if (secIsLoggedIn()) {
                                ?>


                                <form class="search" action="" method="post">
                                    <?= $tokenInput ?>
                                    <input type="hidden" name="user" value="<?= $_SESSION['userid'] ?>">
                                    <input type="hidden" name="game" value="<?= $row['game_id'] ?>">
                                    <?php if (secIsLoggedIn()) {
                                            // echo '<input  class="btn button-game right pulse" type="submit" value="PLAY" name="play">';
                                        } ?>
                                    <button class="btn waves-effect waves-light blue right pulse" type="submit" name="play">PLAY</button>
                                </form>


                            <?php
                            } else {
                                echo '<a href="index.php?side=forside&logind"><i class="material-icons">lock_open</i>Log ind</a><p>Du skal være logget ind for at spille!</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <?php


                ?>
                <div class="col s12 m6 l5 xl4 offset-l1 offset-xl1">


                    <ul class="collection">


                        <?php
                        $row_count = 1;
                        $stmt = $conn->prepare('SELECT game_name, media_path, result_score, result_point, result_time, 
                        profile_username, entrant_date, profile_age
             FROM tbl_game
             INNER JOIN tbl_entrant
             ON game_id = fk_entrant_game
             LEFT JOIN tbl_result
             ON result_id = fk_entrant_result
             INNER JOIN tbl_user
             ON user_id = fk_entrant_user
             INNER JOIN tbl_profile
             ON fk_user_profile = profile_id
             LEFT JOIN `tbl_media` 
ON `tbl_profile`.`fk_profile_media` = `tbl_media`.`media_id`
             WHERE game_id = 1
             ORDER BY result_score DESC LIMIT 5');

                        $stmt->execute();
                        foreach ($stmt->fetchAll() as $row) {
                            $entry = $row_count++;
                            if ($entry == 1) {
                                $icon = '<i class="material-icons">sentiment_very_satisfied</i>';
                            } else if ($entry == 2) {
                                $icon = '<i class="material-icons">sentiment_satisfied</i>';
                            } else if ($entry == 3) {
                                $icon = '<i class="material-icons">sentiment_neutral</i>';
                            } else if ($entry == 4) {
                                $icon = '<i class="material-icons">sentiment_dissatisfied</i>';
                            } else if ($entry == 5) {
                                $icon = '<i class="material-icons">sentiment_very_dissatisfied</i>';
                            }
                            $bithdayDate = $row['profile_age'];
                            $age = floor((time() - strtotime($bithdayDate)) / 31556926);

                            $dag = date('d', strtotime($row['entrant_date']));
                            $maaneder = array("Jannuar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "Oktober", "September", "November", "Decmber",);
                            $pos = date('n', strtotime($row['entrant_date'])) - 1; //Er det i Marts giver date funktionen 3, trækkes 1 fra har vi positionen i arrayet ovenfor
                            $aar = date('Y', strtotime($row['entrant_date']));
                            $profilImg = 'assets/images/' . $row['media_path'];
                            ?>
                            <li class="collection-item avatar">
                                <img src="<?= $profilImg ?>" alt="" class="circle">
                                <span class="title"><?php echo $row['profile_username'] . ' - ';
                                                        if ($age < 15) {
                                                            echo "Kid";
                                                        } else if ($age > 14 && $age < 25) {
                                                            echo "Youngster";
                                                        } else if ($age > 24) {
                                                            echo "Oldtimer";
                                                        } ?></span>
                                <p>- fik <?= $row['result_point'] ?> point,<br>
                                    med <?= $row['result_time'] ?> sekunder tilbage.</p>
                                <p><b>Total Score: <?= $row['result_score'] ?></b></p>
                                <p class="date"> Dato: <?= $dag . '. ' . $maaneder[$pos] . ' ' . $aar ?></p>

                                <?php echo '<p class="secondary-content">' . $entry . ' ' . $icon . '</p>'
                                    ?>

                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</section>