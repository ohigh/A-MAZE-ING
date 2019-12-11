<?php
$error = [];
$encrypt = "";
if(secCheckMethod('POST')) {
    $post = secGetInputArray(INPUT_POST);
    if(isset($post['mail'])) {
        if (secValidateToken($post['_once'], 300)) {
            $mail        = validEmail($post['email']) ? ($post['email'])      : $error['email']   = 'Brugernavnet skal være en e-mail adresse.';
            if(sizeof($error) === 0) {
            $stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE user_email = :email");
            $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
                if ($stmt->execute() && ($stmt->rowCount() === 1)) {
                    $resultat = $stmt->fetch(PDO::FETCH_OBJ);
                    $encrypt = md5(rand(10,10000)+$resultat->user_id);
                    $date = time() + (7 * 24 * 60 * 60);
                    $id = $resultat->user_id;
                    echo '<a href="index.php?side=retKode&encrypt='.$encrypt.'&id='.$id.'">Klik her for at ændre din adgangskode</a>';
                    
                    $sql = "INSERT INTO `tbl_reset`(`reset_expire`, `reset_user`, `reset_encrypt`) 
                            VALUES (:expire, :user, :encrypt);";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array(
                        ':expire' => $date, 
                        ':user' => $id,
                        ':encrypt' => $encrypt,
                    ));
                } else {
                    $error['email']   = 'Fejl i brugernavnet (e-mail).';
                }
            }
        }
    }
}
foreach ($error as $message) {
    @$msg .= "<p>$message</p>" . PHP_EOL;
}
$tokenInput = secCreateTokenInput();
?>

<h5>Glemt adgangskode</h5>
<?='<h5>'.@$msg.'</h5>'?>
<div class="row">
<form action="" method="post">
<?=$tokenInput?>
<fieldset>
<legend>Indtast dit Brugernavn</legend>
<div class="input-field col s12">
    <label for="email">E-mail (Brugernavn)</label>
    <input type="text" name="email" value="">
</div>
<div class="input-field col s12">
    <button type="submit" name="mail" class="btn waves-effect waves-light blue ">Send</button>
</div>
</fieldset>
</form>
</div>