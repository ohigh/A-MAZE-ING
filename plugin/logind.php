<?php
$error = [];
    if(secCheckMethod('POST')) {
        $post = secGetInputArray(INPUT_POST);
        $error =[];
        if (secValidateToken($post['_once'], 300)) {
            $mail        = validEmail($post['email']) ? ($post['email'])      : $error['email']   = 'Brugernavnet skal være en e-mail adresse.';
            $adgangskode = validMixedBetween($post['kode'], 4) ? $post['kode']: $error['adgangskode'] = 'Adgangskode er for kort.';
            $remember = $post['remember'];
            if(sizeof($error) === 0) {
                if(secSetLogIn($mail, $adgangskode, $remember) == false) {
                    $error['fejl'] = 'Der er fejl i brugernavn eller adgangskode';
                }
                if ($get['side'] == 'admin') {
                    header('location: admin/index.php?side=dash');
                }  else {header('location: index.php?side=profil');}  
            }
        }
    }
foreach ($error as $message) {
    @$msg .= "<p>$message</p>" . PHP_EOL;
}    
?>

<h5>Log Ind</h5>
<!-- <?='<h5>'.@$msg.'</h5>'?> -->
<div class="row">
<form action="" method="post" autocomplete="off">
<?=secCreateTokenInput()?>

<div class="input-field col s12 login">
    <p><?='<p class="error">'.@$error['email'].'</p>'?></p>
    <input type="text" name="email" value="" placeholder="Skriv din E-mail..." required>
</div>
<div class="input-field col s12 login">
    <p><?='<p class="error">'.@$error['adgangskode'].'</p>'?></p>
    <input type="password" name="kode" value="" placeholder="Skriv din kode..." required>
</div>
<div class="input-field col s12 login">
    <input type="checkbox" name="remember" id="remember-me" value="checked" />
    <label for="remember-me">Husk mig.</label>
</div>
<div class="input-field col s12">
    <button type="submit" class="btn button-login left">Log ind</button> 
    <a class="btn button-login left " href="?side=glemtKode">Glemt kode</a>
</div>
</form>
</div>
<br>
