<?php
require_once './includes/media.php';

$error =[];
if (secCheckMethod('GET') || secCheckMethod('POST')) {
        $get = secGetInputArray(INPUT_GET);
            if (isset($_SESSION['userid']) && !empty($_SESSION['userid']) && is_numeric($_SESSION['userid'])) {
            	$userId = $_SESSION['userid'];
            } 
            // else {
            // 	// 404
            //     // echo 'Der var fejl i brugerens id!';
            //     $page = $_SERVER['PHP_SELF'];
            //         $sec = "5";
            //         header("Refresh: $sec; url=$page");
            // 	// header('Location: index.php?side=bruger');
            // }
}
if($stmt = $conn->prepare("SELECT `profile_firstname`, `profile_sirname`, `user_id`, `user_password`,  `role_name`,  `fk_profile_media`,
                                  `profile_username`, `profile_age`, `user_email`, `fk_user_profile`, `fk_user_role`, `media_path`
                        FROM `tbl_profile` 
                        INNER JOIN tbl_user 
                        ON tbl_user.fk_user_profile = tbl_profile.profile_id
                        LEFT JOIN `tbl_media` 
                        ON `tbl_profile`.`fk_profile_media` = `tbl_media`.`media_id`  
                        INNER JOIN tbl_role
						ON tbl_user.fk_user_role = tbl_role.role_id
                        WHERE user_id = :id")) {
$stmt->bindParam(':id', $userId, PDO::PARAM_STR);
                        if ($stmt->execute() && ($stmt->rowCount() === 1)) {
                            $resultat = $stmt->fetch(PDO::FETCH_OBJ);
                            $id = $resultat->fk_user_profile;
                            $rolle = $resultat->fk_user_role;
                            $okkode = $resultat->user_password;
                        } else {
                            echo '<h5>Brugeren eksistere ikke!</h5>';
                            $page = 'index.php?side=bruger';
                            $sec = "5";
                            header("Refresh: $sec; url=$page");
                            die();
                        }
                        $bithdayDate = $resultat->profile_age;
                        $age = floor((time() - strtotime($bithdayDate)) / 31556926);
                        $eksistImg = $resultat->fk_profile_media;
                        if ($age < 15){$niveau = "Kid";} 
                        else if ($age > 14 && $age < 25) {$niveau = "Youngster";} 
                        else if ($age > 24) {$niveau = "Oldtimer";} 
                        print_r($id);

                        }
// print_r($resultat);
// print_r($id);
// print_r($userId);
if(secCheckMethod('POST')) {
    $post = secGetInputArray(INPUT_POST);
    
    // print_r($post);
    if(!secValidateToken($post['_once'], 600)) {
        $error['session'] = 'Din session er udløbet! Prøv igen.';
    }  
        if(isset($post['retProfil'])) {
                $fornavn     = validCharacter($post['fornavn']) ? $post['fornavn']            : $error['fornavn']    = 'Fejl i fornavnet.';
                $efternavn   = validCharacter($post['efternavn']) ? $post['efternavn']        : $error['efternavn']  = 'Fejl i efternavnet.';
                $brugernavn  = validCharacter($post['brugernavn']) ? $post['brugernavn']      : $error['brugernavn'] = 'Fejl i brugernavn.';
                // $mail        = validEmail($post['email']) ? ($post['email'])                  : $error['email']      = 'Fejl i e-mail adressen.';



                // Opret nyt billede

if($_FILES['filUpload']['name'] !== ''){
    $billede = mediaImageUploader('filUpload', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'], 'assets/images');
    if($billede['code']){
        // Resize fuktion start
        $imgPath = 'assets/images/' . $billede['name'];
        $Path = 'user_img/' . $billede['name'];
        resize_image($imgPath, 116, 80, $Path);
                            
        $sql =
            "
            INSERT INTO `tbl_media`(`media_path`, `media_type`) 
                            VALUES (:sti, :type);
                            SELECT LAST_INSERT_ID() INTO @lastId;
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array(
                ':sti' => $Path,
                ':type' => $billede['type'],
            ));
        
            $eksistImg = $conn->lastInsertId();
    } else {
        $error['filUpload'] = $billede['msg'];
    }

}
// Update bruger

                if(sizeof($error) === 0) {
                    if ($stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE user_email = :email AND user_id <> $userId")) {
                        $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
                            if ($stmt->execute()) {
                                if ($stmt->rowCount() > 0) {
                                $error['brugerfindes'] = 'Medlemmet findes allerede! Vælg en anden e-mail.'; 
                                } else {
                                    if(!sqlQueryPrepared("
                                                            UPDATE `tbl_profile` SET `profile_firstname` = :name, `profile_sirname` = :sirname, `profile_username` = :username,`fk_profile_media`= :fk_profile_media
                                                            WHERE profile_id = $id
                                                        ", 
                                                            array(
                                                                ':name' => $fornavn, 
                                                                ':sirname' => $efternavn,
                                                                ':username' => $brugernavn,
                                                                ':fk_profile_media' => $eksistImg
                                                            ))){
                                                                    $error['brugeropret'] = 'Der er sket en fejl ved redigeringen af dine medlemsoplysningerne! Prøv igen.';
                                    } else {
                                        header('location: ?side=profil#profil');
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
        if(isset($post['retKode'])) {
                
                $adgangskode = validMatch($post['gentagkode'], $post['kode']) ? $post['kode'] : $error['gentagkode']   = 'Adgangskoderne er ikke ens.';
                $adgangskode = validMixedBetween($post['kode'], 4) ? $post['kode']            : $error['kode']   = 'Adgangskode er for kort.';
            
                if(sizeof($error) === 0) {
                    $adgangskode = password_hash($adgangskode, PASSWORD_BCRYPT);
                    if(!sqlQueryPrepared("UPDATE `tbl_user` SET `user_password` = :password
                                                      WHERE fk_user_profile = $id", 
                                                      array(
                                                            
                                                            ':password' => $adgangskode,
                                                        ))){
                                                            $error['retkode'] = 'Der er sket en fejl ved redigeringen af adgangskoden! Prøv igen.';
                    } else {
                        header('location: ?side=retBruger&id='.$userId);
                    }
                }
        }
        if(isset($post['retRolle']) && !empty($post['rolle'])) {
            $rolle = $_POST['rolle'];
            if(!sqlQueryPrepared("UPDATE `tbl_user` SET `fk_user_role` = :rolle
                                              WHERE fk_user_profile = $id", 
                                              array(
                                                    
                                                    ':rolle' => $rolle,
                                                ))){
                                                    $error['retrolle'] = 'Der er sket en fejl ved redigeringen af brugerrollen! Prøv igen.';
            } else {
                header('location: ?side=retBruger&id='.$userId);
            }
               
        } else {
            $error['retrolle'] = 'Der er sket en fejl ved redigeringen af brugerrollen! Prøv igen.';
        }
        // Reset brugerrolle knap "UPDATE".
        if(isset($post['resetRolle'])) {
            $rolle = $_POST['resetRolle'];
            if(!sqlQueryPrepared("UPDATE `tbl_user` SET `fk_user_role` = :rolle
                                              WHERE fk_user_profile = $id", 
                                              array(
                                                    
                                                    ':rolle' => $rolle,
                                                ))){
                                                    $error['retrolle'] = 'Der er sket en fejl ved redigeringen af brugerrollen! Prøv igen.';
            } else {
                header('location: ?side=retBruger&id='.$userId);
            }
               
        } else {
            $error['retrolle'] = 'Der er sket en fejl ved redigeringen af brugerrollen! Prøv igen.';
        }
}

echo '<h5>Ret brugerprofil</h5>';
foreach ($error as $message) {
   @$msg .= "<p>$message</p>" . PHP_EOL;
}
$tokenInput = secCreateTokenInput();
?>

<div class="row">
	<div class="col s12 right">
	<a href="./index.php?side=profil#profil" class="btn-floating btn-large waves-effect waves-light blue  right"><i class="material-icons">navigate_before</i></a>
	</div><br>
<div class="input-field col s12">
<form action="" method="post" enctype="multipart/form-data">
    <?=$tokenInput?>
    <?=@$msg?>
    <!--<?='KUN FORNAVN: '.@$error['fornavn']?>-->
    <fieldset>
        <legend>Ret bruger oplysninger</legend>
        <div class="input-field col s12 m6">
            <label for="fornavn">Fornavn</label>
            <input type="text" name="fornavn" value="<?=$resultat->profile_firstname?>"  id="fornavn" min="2" max="30">
        </div>
        <div class="input-field col s12 m6">
            <label for="efternavn">Efternavn</label>
            <input type="text" name="efternavn" value="<?=$resultat->profile_sirname?>"  id="efternavn" min="2" max="30">
        </div>
        <div class="input-field col s12 m6">
            <label for="tel">Gamer navn</label>
            <input type="text" name="brugernavn" value="<?=$resultat->profile_username?>" id="brugernavn">
            
        </div>
        <div class="input-field col s12 m6">
            <label for="email">E-mail (Kan ikke rettes)</label>
            <input type="email" name="email" value="<?=$resultat->user_email?>" disabled id="email">
        </div>
        <div class="input-field col s12 m6">
            <label for="alder">Alder (Kan ikke rettes)</label>
            <input type="number" name="alder" value="<?=$age?>" $age disabled id="alder">
        </div>
        <div class="input-field col s12 m6">
            <label for="niveau">Gamertype (Kan ikke rettes)</label>
            <input type="text" name="niveau" value="<?=$niveau?>" disabled id="niveau">
        </div>
        <div class="input-field col s12 m6">
        <div>Eksisterende billede tilhørende - <?=$resultat->profile_username?>:</div> 
    <?php
        // $stmt = $conn->prepare("SELECT `media_path`, `media_id`, `fk_boat_media`
        //                         FROM `tbl_media`
        //                         INNER JOIN `tbl_boat`
        //                         ON `media_id` = `fk_boat_media`
        //                         WHERE `media_id` = $eksistImg");

        // $stmt->execute();
        //     $resultat = $stmt->fetch(PDO::FETCH_OBJ);
                // Print_r($resultat->fk_event_media);
// Print_r($eksistImg );
    ?>
        <div class="input-field col s12 m4">        
            <img src="assets/images/<?=$resultat->media_path?>" class="responsive-img" alt="">
        </div>

    </div>
    <div class="input-field col s12 m6">
        <div>Skift eksisterende billede:</div>
        <div class="file-field input-field">
            <div class="btn blue ">
                <span>Vælg fil</span>
                <input name="filUpload" type="file" value="<?=$_FILES['filUpload']?>">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload billede (hvis du ønker et nyt billede)"> 
            </div>
        </div>
    </div> 
        <div class="input-field col s12">
            
            <button type="submit" name="retProfil" class="btn waves-effect waves-light blue ">Ret</button>
        </div>
    </fieldset>
</form>
</div>
</div>

<div class="row">
   



</form>
<div class="input-field col s12" >
<form method="post" action="">
    <?=$tokenInput?>
    <?=@$msg?>
    <fieldset>
        <legend>Ret adgangskode</legend>
        <div class="input-field col s6">
            <label for="kode">Ny Adgangskode</label>
            <input type="password" name="kode" value=""  id="kode">
        </div>
        <div class="input-field col s6">
            <label for="gentagkode">Gentag Adgangskode</label>
            <input type="password" name="gentagkode" value=""  id="gentagkode">
        </div>
        <div class="input-field col s12">
            <button type="submit" name="retKode" class="btn waves-effect waves-light blue ">Ret</button>
        </div>
    </fieldset>

</form>
</div>
</div>
<br>

