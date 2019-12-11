<?php
$error =[];
if (secCheckMethod('GET') || secCheckMethod('POST')) {
        $get = secGetInputArray(INPUT_GET);
            if (isset($get['id']) && !empty($get['id']) && is_numeric($get['id'])) {
            	$messageId = $get['id'];
            } else {
            	// 404
                echo 'Der var fejl i besked id!';
            	header('Location: index.php?side=besked');
            }
}
$stmt = $conn->prepare("SELECT * FROM `tbl_message`
                        WHERE message_id = :id");
$stmt->bindParam(':id', $messageId, PDO::PARAM_STR);
                        if ($stmt->execute() && ($stmt->rowCount() === 1)) {
                            $resultat = $stmt->fetch(PDO::FETCH_OBJ);
                            
                        }
// print_r($resultat);
//echo 'ID: '.print_r($resultat->profilID, true);
// print_r($messageId);



foreach ($error as $message) {
   @$msg .= "<p>$message</p>" . PHP_EOL;
}
?>
<h5>Læs besked</h5>
<div class="row">
	<div class="col s12 right">
	<a href="./index.php?side=beskeder" class="btn-floating btn-large waves-effect waves-light blue right"><i class="material-icons">navigate_before</i></a>
	</div><br>

<?=@$msg?>
<div class="row">
    <div class="col s12">
    <!-- Mail Info -->
        <div class="row">
            <div class="card grey lighten-4 col s4">
                <div class="card-content">
                        <div><strong>Modtaget:</strong> <?=$resultat->message_created?></div>
                        <div><strong>Fra:</strong> <?=$resultat->message_name?></div>
                        <div><strong>Mail:</strong> <?=$resultat->message_email?></div>
                        <div><strong>Telefon:</strong> <?=$resultat->message_phone?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card grey lighten-4 col s8">
                <div class="card-content">
                    <div><strong>Besked:</strong><br> 
                       <?=$resultat->message_content?> 
                    </div>
                </div>
            </div>
        </div>
    </div>