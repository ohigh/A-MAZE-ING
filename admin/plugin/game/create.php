<?php
require_once '../includes/media.php';
$error           = [];
if (secCheckMethod('POST')) {
    $post = secGetInputArray(INPUT_POST);
    if (!secValidateToken($post['_once'], 600)) {
        $error['session'] = 'Din session er udløbet! Prøv igen.';
    }

    // Opret båd
    $gameCat = $post['gameCat'] != 0 ? $post['gameCat'] : $error['gameCat'] = 'Der er ikke valget game kategori!';
    $gameLevel = validIntBetween($post['gameLevel'], 1, 2) ? $post['gameLevel'] : $error['gameLevel'] = 'Der er ikke valget sværhedsgrad!';
    $gameName = validMixedBetween($post['gameName'], 2, 64) ? $post['gameName'] : $error['gameName'] = 'OBS Game navnet skal værer udfyldt (bogstaver og tal).';
    $gameDescription = $post['gameDescription'] == validMixedBetween($post['gameDescription'], 1, 65535) ? $post['gameDescription'] : $error['gameDescription'] = 'Lav venligst en beskrivelse af spillet.';
    $gameURL = validMixedBetween($post['gameURL'], 1, 1200) ? $post['gameURL'] : $error['gameURL'] = 'Du skal angive en URL til spillet!';

    // Tjek om båden allerede er oprettet
    $stmt_name = $conn->prepare("SELECT `game_name`, `game_level` FROM `tbl_game`
                             INNER JOIN `tbl_type`
                             ON `tbl_game`.`fk_game_type` = `tbl_type`.`type_id`
                             WHERE `game_name` = :gameName AND `type_id` = :gameCat");
    $stmt_name->bindParam(':gameName', $gameName, PDO::PARAM_STR);
    $stmt_name->bindParam(':gameCat', $gameCat, PDO::PARAM_STR);
    $stmt_name->execute();

    if ($stmt_name->rowCount() > 0) {
        $error['spilfindes'] = 'Spillet er allerede oprettet!';
    } else {

        if (sizeof($error) === 0) {
            $billede = mediaImageUploader('filUpload', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'], '../assets/images');
            print_r($billede);
            if ($billede['code']) {
                // Resize fuktion start
                $imgPath = '../assets/images/' . $billede['name'];
                $Path = 'games/' . $billede['name'];
                resize_image($imgPath, 400, 400, $Path);

                $sql =
                    "
                            INSERT INTO `tbl_media`(`media_path`, `media_type`) 
                            VALUES (:sti, :type);
                            SELECT LAST_INSERT_ID() INTO @lastId;
                            ";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array(
                    ':sti' => $Path,
                    ':type' => $billede['type']
                ));

                $mediaId = $conn->lastInsertId();
            } else {
                $error['filUpload'] = $billede['msg'];
            }

            if (sizeof($error) === 0) {

                $sql =
                    "
                            INSERT INTO `tbl_game`(`game_name`, `game_url`, `game_description`, `fk_game_media`, `game_level`, `fk_game_type`) 
                                VALUES (:name, :gameURL, :gameDescription, :fk_game_media, :gameLevel, :gameCat);
                            ";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array(
                    ':name' => $gameName,
                    ':gameURL' => $gameURL,
                    ':gameDescription' => $gameDescription,
                    ':fk_game_media' => $mediaId,
                    ':gameLevel' => $gameLevel,
                    ':gameCat' => $gameCat,

                ));
            }
        }

        if (sizeof($error) === 0) {
            echo '<h5 class="fejl">Spillet blev oprettet!</h5>';
            $page = '?side=dash';
            $sec = "3";
            header("Refresh: $sec; url=$page");
        }
    }
}
foreach ($error as $message) {
    @$msg .= '<h4 class="fejl">' . $message . '</h4>' . PHP_EOL;
}

?>
<!-- FORMULAR -->
<h5>Opret dit spil</h5>
<div class="row">
    <div class="col s12 right">
        <a href="./index.php?side=profil#profil" class="btn-floating btn-large waves-effect waves-light blue right"><i class="material-icons">navigate_before</i></a>
    </div>
</div>
<div class="row">
    <form action="" method="post" enctype="multipart/form-data">
        <?= secCreateTokenInput() ?>
        <?= @$msg ?>
        <fieldset>
            <legend>Opret spil</legend>
            <!-- Type -->
            <div class="input-field col s12 m6">
                <select name="gameCat">
                    <option value="0" disabled selected>Vælg type</option>
                    <?php
                    $stmt = $conn->prepare("SELECT `type_id`, `type_name` FROM `tbl_type`");
                    $stmt->execute();
                    foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $value) : ?>
                        <option value="<?= $value->type_id; ?>" <?= (@$post['gameCat'] == $value->type_id) ? 'selected' : '' ?>><?= $value->type_name ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Game kategori (Skal vælges)</label>
            </div>
            <!-- Navn -->
            <div class="input-field col s12 m6">
                <label for="gameName">Navn (skal være udfyldt)</label>
                <input type="text" name="gameName" id="gameName" min="2" maxlength="64" value="<?= @$post['gameName'] ?>" placeholder="Skriv navnet på båden (skal udfyldes)">
            </div>
            <!-- Sværhedsgrad -->
            <div class="input-field col s12 m6">
                <select name="gameLevel">
                    <option value="" disabled selected>Vælg sværhedsgrad</option>
                    <option value="1" <?= $post['gameLevel'] == 1 ? 'selected' : '' ?>>Nem</option>
                    <option value="2" <?= $post['gameLevel'] == 2 ? 'selected' : '' ?>>Medium</option>
                    <option value="3" <?= $post['gameLevel'] == 3 ? 'selected' : '' ?>>Svær</option>
                </select>
                <label>Sværhedsgrad (Skal vælges)</label>
            </div>
            <!-- URL -->
            <div class="input-field col s12 m6">
                <label for="gameURL">Link/URL til spillet (skal være udfyldt)</label>
                <input type="url" name="gameURL" id="gameURL" value="<?= @$post['gameURL'] ?>" placeholder="Link til spillet (skal udfyldes)">
            </div>
            <!-- Beskrivelse -->
            <div class="input-field col s12 m6">
                <label for="gameDescription">Beskrivelse (skal udfyldes)</label>
                <textarea name="gameDescription" rows="" cols="" id="gameDescription" placeholder="Skriv en beskrivelse (skal udfyldes)"></textarea>
            </div>
            

            <!-- Billede uplad -->
            <div class="input-field col s12 m6">
                <div class="file-field input-field">
                    <div class="btn blue ">
                        <span>Vælg fil</span>
                        <input name="filUpload" type="file" value="<?= $_FILES['filUpload'] ?>">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload billede (der skal vælges et billede)">
                    </div>
                </div>
            </div>
            <!-- Opret (submit) -->
            <div class="input-field col s12">
                <button name="opretBaade" type="submit" class="btn waves-effect waves-light blue ">Opret</button>
            </div>
        </fieldset>
    </form>
</div>
<script type="text/javascript">
        CKEDITOR.replace( 'gameDescription');
</script>