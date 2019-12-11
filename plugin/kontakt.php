<?php
require_once 'includes/sqlOperations.php';
$error =[];
if(secCheckMethod('POST')){
	$post    		= secGetInputArray(INPUT_POST);

    if(!secValidateToken($post['_once'], 600)) {
           $error['session'] = 'Din session er udløbet! Prøv igen.';
           }
    	// Opret ny besked
    		if(isset($post['sendMessage'])) { 
                $name     = validCharacter($post['name'], 2, 128) ? $post['name']        : $error['name']     = 'Fejl i indtatningen af navn (kun bogstaver).';
                $mail     = validEmail($post['mail']) ? $post['mail']        : $error['mail']     = 'Fejl i indtatningen af email.';
                if ($post['phone'] != '') {
                    $phone         = validPhone($post['phone']) ?    validPhone($post['phone'])         : $error['phone']     = 'Fejl i indtatningen af telefonnummer, (UDEN landekode).';
                } else {$phone = NULL;}
                $mesage     = validMixedBetween($post['message'], 1, 1200) ? $post['message']        : $error['message']     = 'Du skal skrive en besked.';
                
                
                if(sizeof($error) === 0 ){

    				sqlQueryPrepared(
    					"
    						INSERT INTO `tbl_message`(`message_name`, `message_email`, `message_phone`, `message_content`) 
    						VALUES (:navn, :email, :tlf, :indhold);
    					",
    					array(
    						':navn' => $name,
                            ':email' => $mail,
                            ':tlf' => $phone,
    						':indhold' => $mesage,

    					)
    				);
    				    $succes = '<b>Tak for dit spørgsmål eller din besked - vi vender tilbage hurtigst muligt.</b>';
            	        $page = "?side=kontakt";
            	        $sec = "10";
            	        header("Refresh: $sec; url=$page");
            // die();
                
    		} 
        
        }
    }
        $tokenInput = secCreateTokenInput(); 

?>
<section id="contact">
<div class="row">
    <div class="col s12 m10 offset-m1">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Kontakt</span>
                <p><?='<p class="error">'.@$error['session'].'</p>'?></p>                   
                
                <div class="row">
                    <div class="col s12">
                            <?=@$succes?><br>
                    <p>Skriv til os, hvis du har spørgsmål eller andet på hjertet :)</p><br>
                    <div class="row">
                    <div class="col s12 m8 offset-m2 login kontakt">
                        <form action="" method="post" >
                        <?=$tokenInput;?>
                          <div class="input-field col s12 ">
                            <p><?='<p class="error">'.@$error['name'].'</p>'?></p>
                            <input class="validate" type="text" name="name" placeholder="Navn" value="<?= $post['name']?>">
                          </div>
                          <div class="input-field col s12 ">
                            <p><?='<p class="error">'.@$error['mail'].'</p>'?></p>
                            <input type="email" class="validate" name="mail" placeholder="Email" value="<?= $post['mail']?>">
                          </div>
                          <div class="input-field col s12 ">
                            <p><?='<p class="error">'.@$error['phone'].'</p>'?></p>
                            <input type="tel" class="validate" name="phone" placeholder="Telefonnummer (valgfrit)" value="<?=$post['phone']?>">                          
                          </div>
                          <div class="input-field col s12">
                            <p><?='<p class="error">'.@$error['message'].'</p>'?></p>
                            <textarea class="validate" name="message" rows="12" placeholder="Besked:"><?= $post['message']?></textarea>
                          </div>
                          <div class="input-field col s12 login">
                              <button class="button" type="submit" name="sendMessage">Send besked</button>
                          </div>
                          </div>
                        </form>                    </div>
                    <div class="col s7"></div>
                    <!-- </div> -->
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>