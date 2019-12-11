<?php
require_once 'includes/sqlOperations.php';
$error =[];
if(secCheckMethod('POST')){
	$post    		= secGetInputArray(INPUT_POST);

    if(!secValidateToken($post['_once'], 600)) {
           $error['session'] = 'Din session er udløbet! Prøv igen.';
           }
        // Opret tilmelding
           if(isset($post['sendTilmeld'])) { 
            $InputModtager     = validEmail($post['modtager']) ? $post['modtager']        : $error['modtager']     = 'Fejl i indtatningen af modtager.';

            // Tjek om båden allerede er oprettet
            $stmt_name = $conn->prepare("SELECT `newsletter_email` FROM `tbl_newsletter`
                                         WHERE `newsletter_email` = :InputModtager");
            $stmt_name->bindParam(':InputModtager', $InputModtager, PDO::PARAM_STR);
            $stmt_name->execute();
            
            if ($stmt_name->rowCount() > 0) {
            $error['findes'] = 'Du er allerede tilmeldt!'; 
            } else {

if(sizeof($error) === 0 ){

           
				sqlQueryPrepared(
					"
						INSERT INTO `tbl_newsletter`(`newsletter_email`) 
						VALUES (:modtager);
					",
					array(
						':modtager' => $InputModtager,
						
					)
				);
				    $succes = '<b>Tak for din tilmelding.</b>';
        	        $page = "?side=nyheder";
        	        $sec = "5";
        	        header("Refresh: $sec; url=$page");
        // die();
			
		} else {
			echo 'FEJL';
        }
            }
	    }
}
	// print_r ($post);
	$tokenInput = secCreateTokenInput(); 
?>
<!-- NYHEDER -->
<section id="news">   

    <div class="col s12 m8 l8">
        <div class="row">
            <h5> Nyheder</h5>
            
            <?php
                if(isset($_GET['nyhedVis'])){
		        	
		        			include './plugin/nyhedVis.php';
                    } else {
                        include './plugin/nyhederListe.php';
                    }
          ?>
        </div>
    </div>    
    <div class="col s12 m4 l4 sidebar">
    <?php if (secIsLoggedIn()) {?>
        
        <h5>Tilmeld nyhedsbrev</h5>
        <p class="error"><?=@$error['findes']?></p>
        <?=@$succes?>
        <form action=""  method="post" class="search">
        <?=$tokenInput;?>

          <button class="button" method="post" type="submit" name="sendTilmeld">Tilmeld</button>
          <input  type="text" name="modtager" placeholder="E-mail..." value="<?=@$resultat->user_email?>" required>
        </form>
        <?php
        } ?>
        <h5>Søg i nyhederne</h5>
        <form class="search" action="?side=soeg" method="post" role="search">
      <button class="button" type="submit"  name='text'>Søg</button>
      <input  type="text" placeholder="Søg på sitet..." name="searchText" required>
    </form>
    <?php
          if(isset($_GET['logind'])){
	      	
	      			include './plugin/logind.php';
              }
    ?>
    </div>
</section>    
