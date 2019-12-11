<?php
// Hent billed opload & resize funktion
require_once './includes/media.php';
// lav et tomt "error" array
$error = [];

if (secCheckMethod('POST')) {
    $post = secGetInputArray(INPUT_POST);
    if (!secValidateToken($post['_once'], 600)) {
        $error['session'] = 'Din session er udløbet! Prøv igen.';
    }
    print_r ($_FILES["filUpload"]);

    // if(sizeof($error) === 0){
        // if($post['filUpload'] !== ''){
    if($_FILES['filUpload']['name'] !== ''){
        $billede = mediaImageUploader('filUpload', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'], 'assets/images');
        if($billede['code']){
            // Resize fuktion start
            $imgPath = 'assets/images/' . $billede['name'];
            $Path = 'user_img/' . $billede['name'];
            resize_image($imgPath, 90, 100, $Path);
                                
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
}       

    // if (isset($post['opretBruger'])) {
        $fornavn     = validCharacter($post['fornavn']) ? $post['fornavn']            : $error['fornavn']   = 'Fejl i fornavnet.';
        $efternavn   = validCharacter($post['efternavn']) ? $post['efternavn']        : $error['efternavn'] = 'Fejl i efternavnet.';
        $brugernavn  = validCharacter($post['brugernavn']) ? $post['brugernavn']             : $error['brugernavn']        = 'Fejl i brugernavn.';
        $alder       = validDate($post['alder']) ?    $post['alder']         : $error['alder']     = 'Fejl i indtatningen af fødselsdag.';
        $mail        = validEmail($post['email']) ? ($post['email'])                  : $error['email']     = 'Fejl i e-mail adressen.';
        $adgangskode = validMatch($post['gentagkode'], $post['kode']) ? $post['kode'] : $error['gentag']   = 'Adgangskoderne er ikke ens.';
        $adgangskode = validMixedBetween($post['kode'], 4) ? $post['kode']            : $error['kode']   = 'Adgangskode er for kort.';

        if (sizeof($error) === 0) {
            if ($stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE user_email = :email")) {
                $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {
                        $error['brugerfindes'] = 'Medlemmet findes allerede! Vælg en anden e-mail.';
                    } else {
                        
                        
                            if(sizeof($error) === 0){

                        $adgangskode = password_hash($adgangskode, PASSWORD_BCRYPT);
                        // ! tegnet i nedestående linje (!sqlQueryPrepared): Hvis inserten ikke gennemføers så, ellers.
                        if (!sqlQueryPrepared("INSERT INTO `tbl_profile`(`profile_firstname`, `profile_sirname`, `profile_age`, `profile_username`, `fk_profile_media`) 
                                    VALUES (:name,:sirname,:age, :username, :fk_media);
                                    SELECT LAST_INSERT_ID() INTO @lastId;
                                    INSERT INTO `tbl_user`(`user_email`, `user_password`, `fk_user_profile`, `fk_user_role`) 
                                    VALUES (:email,:password, @lastId,:fk_role)
                                    ", array(
                            ':name' => $fornavn,
                            ':sirname' => $efternavn,
                            ':age' => $alder,
                            ':username' => $brugernavn,
                            ':fk_media' => @$mediaId,
                            ':email' => $mail,
                            ':password' => $adgangskode,
                            ':fk_role' => 3
                        ))) {
                            $error['brugeropret'] = 'Der er sket en fejl ved oprettelsen! Prøv igen.';
                        } else {
                            header('location: ?side=forside&logind');
                        }
                    }
                
                
                }    
                } else {
                    $error['generel'] = 1801; // execute fejl
                }
            } else {
                $error['generel'] = 1802; // bind fejl
            }
        }
    // }
}
echo '<h5>Opret Bruger Profil</h5>';
foreach ($error as $message) {
    @$msg .= "<p>$message</p>" . PHP_EOL;
}
?>
<div class="row">
    
    <form action="" method="post" enctype="multipart/form-data"> 
        <?= secCreateTokenInput() ?>
        <?= @$msg ?>
        <fieldset>
            <legend>Bruger Oplysninger</legend>
            <div class="input-field col s12 m6">
                <label for="fornavn">Fornavn</label>
                <input type="text" name="fornavn" value="<?= $post['fornavn'] ?>" id="fornavn" min="2" max="30">
            </div>
            <div class="input-field col s12 m6">
                <label for="efternavn">Efternavn</label>
                <input type="text" name="efternavn" value="<?= $post['efternavn'] ?>" id="efternavn" min="2" max="30">
            </div>
            <div class="input-field col s12 m6">
                <label for="alder">Fødselsdag</label>
                <input type="text" class="datepicker" name="alder" id="alder" value="<?= $post['alder']?>" >
            </div>
            <div class="input-field col s12 m6">
                <label for="brugernavn">Brugernavn</label>
                <input type="text" name="brugernavn" value="<?= $post['brugernavn'] ?>" id="brugernavn">
            </div>

        <!-- </fieldset>
        <br>
        <fieldset> -->
            <!-- <legend>Login Oplysninger</legend> -->

            <div class="input-field col s12">
                <label for="email">E-mail</label>
                <input type="email" name="email" value="<?= $post['email'] ?>" id="email">
            </div>
            <div class="input-field col s12 m6">
                <label for="kode">Adgangskode</label>
                <input type="password" name="kode" value="" id="kode">
            </div>
            <div class="input-field col s12 m6">
                <label for="gentagkode">Gentag Adgangskode</label>
                <input type="password" name="gentagkode" value="" id="gentagkode">
            </div>
                        <!-- Billede uplad -->
    <div class="input-field col s12">
        <div class="file-field input-field">
            <div class="btn blue ">
                <span>Vælg fil</span>
                <input name="filUpload" type="file" value="<?=$_FILES['filUpload']?>">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload profilbillede (valgfrit)"> 
            </div>
        </div>
    </div>
            <div class="input-field col s12">
                <button type="submit" name="opretBruger" class="btn waves-effect waves-light blue ">Opret</button>
            </div>
        </fieldset>
    </form>
</div>
<script>
    $(dokument).ready(function() {

    });
</script>