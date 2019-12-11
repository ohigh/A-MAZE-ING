<?php
$error = [];
// Tjek om URL'en indeholder "encrypt kode" og bruger ID. 
// Hvis ja - hent "encrypt kode", bruger ID og udløbs tidspunkt i db.
if (secCheckMethod('POST') || secCheckMethod('GET')) {
    $get = secGetInputArray(INPUT_GET);
    $post = secGetInputArray(INPUT_POST);
        if (isset($get['encrypt']) && !empty($get['encrypt']) && isset($get['id']) && !empty($get['id'])) {
            $getEncrypt = $get['encrypt'];
            $getId = $get['id'];

        $stmt = $conn->prepare("SELECT `reset_expire`, `reset_user`, `reset_encrypt` FROM `tbl_reset` WHERE `reset_encrypt` = :encrypt AND `reset_user` = :id");
        $stmt->bindParam(':encrypt', $getEncrypt,  PDO::PARAM_STR);
        $stmt->bindParam(':id', $getId,  PDO::PARAM_INT);
            if ($stmt->execute() && ($stmt->rowCount() === 1)) {
                $resultat = $stmt->fetch(PDO::FETCH_OBJ);
                $encrypt = $resultat->reset_encrypt;
                $id = $resultat->reset_user;
                $expire = $resultat->reset_expire;
            } else {
                $error['email']   = 'Fejl i reset linket.';
            }
        }
   
// ret adgangskode
if(isset($post['retKode'])) {
    if (secValidateToken($post['_once'], 300)) {
    $adgangskode = validMatch($post['gentagkode'], $post['kode']) ? $post['kode'] : $error['gentagkode']   = 'Adgangskoderne er ikke ens.';
    $adgangskode = validMixedBetween($post['kode'], 4) ? $post['kode']            : $error['kode']   = 'Adgangskode er for kort.';
    $user_id = $post['id'];

    if(sizeof($error) === 0) {
        $adgangskode = password_hash($adgangskode, PASSWORD_BCRYPT);
        
        $sql = "UPDATE `tbl_user` SET `user_password` = :password
                WHERE user_id = $user_id";
        $stmt = $conn->prepare($sql);
        if(!$stmt->execute(array(
            ':password' => $adgangskode,
        ))) {$error['retkode'] = 'Der er sket en fejl ved redigeringen af adgangskoden! Prøv igen.';}
        
        
        
        // if(!sqlQueryPrepared("UPDATE `tbl_user` SET `user_password` = :password
        //                                   WHERE user_id = $user_id", 
        //                                   array(
                                                
        //                                         ':password' => $adgangskode,
        //                                     ))){
        //                                         $error['retkode'] = 'Der er sket en fejl ved redigeringen af adgangskoden! Prøv igen.';
        // } 
        else {
            // sqlQueryPrepared("DELETE FROM `tbl_reset` WHERE `reset_encrypt` = :encrypt AND `reset_user` = :id",
            // array(':encrypt' =>$getEncrypt,
            //       ':id' => $getId,));
            echo '<h5>Din adgangskode er blevet rettet!</h5>';
            $page = 'index.php?side=logind';
            $sec = "5";
            header("Refresh: $sec; url=$page");
            die();
        }
    }
    } else { $error['session'] = 'Din session er udløbet! Prøv igen.';
    }
}
}

foreach ($error as $message) {
    @$msg .= "<p>$message</p>" . PHP_EOL;
}
$date = time();
$tokenInput = secCreateTokenInput();
// Hvis ikke "encrypt kode", bruger ID er ens i db og URL og udløbs tidspunkt ikke er overskredet, gå tilbage til Glemt adgangskode.
if ($encrypt !== $getEncrypt && $id !== $getId || $date > $expire) {  
    header('location: ?side=glemtKode'); 

} else {
// Hvis "encrypt kode", bruger ID er ens og udløbs tidspunkt OK, vis Ret adgangskode.    
?>
<div class="input-field col s12 m6" >
<h5>Glemt adgangskode</h5>
<form method="post" action="">
    <?=$tokenInput?>
    <?=@$msg?>
    <input type="hidden" name="id" value="<?=$id?>">
    <fieldset>
        <legend>Indtast ny adgangskode</legend>
        <div class="input-field col s12">
            <label for="kode">Adgangskode</label>
            <input type="password" name="kode" value=""  id="kode">
        </div>
        <div class="input-field col s12">
            <label for="gentagkode">Gentag Adgangskode</label>
            <input type="password" name="gentagkode" value=""  id="gentagkode">
        </div>
        <div class="input-field col s12">
            <button type="submit" name="retKode" class="btn waves-effect waves-light blue ">Gem</button>
        </div>
    </fieldset>

</form>
</div>
<?php } ?>