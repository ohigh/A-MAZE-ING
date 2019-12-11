<?php
$error           = [];

if (!secValidateToken($post['_once'], 600)) {
    $error['session'] = 'Din session er udløbet! Prøv igen.';
}
if (isset($post['play'])) {
    $user     = validIntBetween($post['user'], 1, 128) ? $post['user'] : $error['user'] = 'Fejl i titlen.';
    $event   = validIntBetween($post['game'], 1, 128) ? $post['game'] : $error['game'] = 'Spillet findes ikke!';
    if (sizeof($error) === 0) {


        $_SESSION['game'] = $event;
        $userId = $_SESSION['userid'];
        $gameId = $_SESSION['game'];


        if ($gameId != 1) {
            sqlQueryPrepared(
                "INSERT INTO `tbl_entrant`(`fk_entrant_game`, `fk_entrant_user`) 
                 VALUES (:events, :user);",
                array(
                    ':user' => $user,
                    ':events' => $event,
                )
            );
        }
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
            <h5> Games</h5>
            <div class="row">
                <?php
                // $date = date("Y-m-d");

                $sql = "SELECT * FROM tbl_game
                        INNER JOIN tbl_media
                        ON fk_game_media = media_id 
                        WHERE fk_game_media = media_id ORDER BY game_id ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                foreach ($stmt->fetchAll() as $row) {
                    // DATO

                    ?>

                    <div class="col s12 m6">
                        <div class="card large">
                            <div class="card-image">
                                <img src="assets/images/<?= $row['media_path'] ?>">
                            </div>
                            <div class="card-content">
                                <span class="card-title "><?= $row['game_name'] ?></span>

                                <p><?= htmlspecialchars_decode($row['game_description']) ?></p>
                            </div>
                            <div class="card-action ">
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
                }

                ?>

            </div>
        </div>
    </div>
</section>