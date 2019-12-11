<?php
if (secCheckMethod('GET')) {
        $get = secGetInputArray(INPUT_GET);
            if (isset($get['id']) && !empty($get['id']) && is_numeric($get['id'])) {
            	$abonnentId = $get['id'];
            } else {
            	// 404
                echo 'Der var fejl i blogindlægets id!';
            	// header('Location: index.php?side=bruger');
            }
}

if(sqlQueryPrepared("
                        DELETE FROM `tbl_newsletter` WHERE newsletter_id = :id", 
                                                      array(
                                                            
                                                            ':id' => $abonnentId,
                                                        ))) {header('Location: index.php?side=abonnenter');
                                                            } else {
                                                                echo 'Siden blev ikke slettet!';
                                                            }

