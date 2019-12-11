<?php
// session_start();
// session_destroy();
    $error = [];

if(secCheckMethod('POST')) {
    $post = secGetInputArray(INPUT_POST);
    if(!secValidateToken($post['_once'], 600)) {
        $error['session'] = 'Din session er udløbet! Prøv igen.';
    }  



    if(isset($post['opretBruger'])) {
        $fornavn     = validCharacter($post['fornavn']) ? $post['fornavn']            : $error['fornavn']   = 'Fejl i fornavnet.';
        $efternavn   = validCharacter($post['efternavn']) ? $post['efternavn']        : $error['efternavn'] = 'Fejl i efternavnet.';
        $km          = validIntBetween($post['km'], 1, 5) ? $post['km']             : $error['km']        = 'Fejl i km.';
        $tel         = validPhone($post['tel']) ?    $post['tel']         : $error['tel']     = 'Fejl i indtatningen af telefonnummer, (UDEN landekode).';
        $mail        = validEmail($post['email']) ? ($post['email'])                  : $error['email']     = 'Fejl i e-mail adressen.';
        $adgangskode = validMatch($post['gentagkode'], $post['kode']) ? $post['kode'] : $error['gentag']   = 'Adgangskoderne er ikke ens.';
        $adgangskode = validMixedBetween($post['kode'], 4) ? $post['kode']            : $error['kode']   = 'Adgangskode er for kort.';

        if(sizeof($error) === 0) {
            if ($stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE user_email = :email")) {
            $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {
                       $error['brugerfindes'] = 'Medlemmet findes allerede! Vælg en anden e-mail.'; 
                    } else {
                        $adgangskode = password_hash($adgangskode, PASSWORD_BCRYPT);
// ! tegnet i nedestående linje (!sqlQueryPrepared): Hvis inserten ikke gennemføers så, ellers.
                        if(!sqlQueryPrepared("INSERT INTO `tbl_profile`(`profile_firstname`, `profile_sirname`, `profile_username`, `profile_age`) 
                                    VALUES (:name,:sirname,:phone, :km);
                                    SELECT LAST_INSERT_ID() INTO @lastId;
                                    INSERT INTO `tbl_user`(`user_email`, `user_password`, `fk_user_profile`, `fk_user_role`) 
                                    VALUES (:email,:password, @lastId,:fk_role)
                                    ", array(
                                        ':name' => $fornavn, 
                                        ':sirname' => $efternavn,
                                        ':phone' => $tel,
                                        ':km' => $km,
                                        ':email' => $mail,
                                        ':password' => $adgangskode,
                                        ':fk_role' => 3
                                    ))){
                                        $error['brugeropret'] = 'Der er sket en fejl ved oprettelsen! Prøv igen.';
                                    } else {
                                        header('location: ?side=bruger');
                                    }
                    }

                } else {
                    $error['generel'] = 1801; // execute fejl
                }
            
            } else {
                $error['generel'] = 1802; // bind fejl
            }
        }
    }
}
echo '<h5>Opret Medlem</h5>';
foreach ($error as $message) {
   @$msg .= "<p>$message</p>" . PHP_EOL;
}
?>
<div class="row">
	<div class="col s12 right">
	<a href="./index.php?side=bruger" class="btn-floating btn-large waves-effect waves-light blue  right"><i class="material-icons">navigate_before</i></a>
	</div><br>
<form action="" method="post">
    <?=secCreateTokenInput()?>
    <?=@$msg?>
    <fieldset>
        <legend>Medlems Oplysninger</legend>
        <div class="input-field col s12 m6">
            <label for="fornavn">Fornavn</label>
            <input type="text" name="fornavn" value="<?=$post['fornavn']?>"  id="fornavn" min="2" max="30">
        </div>
        <div class="input-field col s12 m6">
            <label for="efternavn">Efternavn</label>
            <input type="text" name="efternavn" value="<?=$post['efternavn']?>"  id="efternavn" min="2" max="30">
        </div>
        <div class="input-field col s12 m6">
            <label for="tel">Mobil</label>
            <input type="text" name="tel"   id="tel" min="2" maxlength="64" value="<?=$post['tel']?>">
	    </div>
        <div class="input-field col s12 m6">
            <label for="km">Roet km</label>
            <input type="number" name="km" value="<?=$post['km']?>"  id="km">
        </div>
    </fieldset>
    <br>
    <fieldset>
        <legend>Login Oplysninger</legend>

        <div class="input-field col s12">
            <label for="email">E-mail</label>
            <input type="email" name="email" value="<?=$post['email']?>"  id="email">
        </div>
        <div class="input-field col s12 m6">
            <label for="kode">Adgangskode</label>
            <input type="password" name="kode" value=""  id="kode">
        </div>
        <div class="input-field col s12 m6">
            <label for="gentagkode">Gentag Adgangskode</label>
            <input type="password" name="gentagkode" value=""  id="gentagkode">
        </div>
        <div class="input-field col s12">
            <button type="submit" name="opretBruger" class="btn waves-effect waves-light blue ">Opret</button>
        </div>
    </fieldset>
</form>
</div>
<script>
$(dokument).ready(function(){
    
});
   
</script>