<?php
if (secCheckMethod('GET')) {
        $get = secGetInputArray(INPUT_GET);
            if (isset($get['id']) && !empty($get['id']) && is_numeric($get['id'])) {
            	$beskedId = $get['id'];
            } else {
            	// 404
                echo 'Der var fejl i beskedenss id!';
            	// header('Location: index.php?side=bruger');
            }
}

if(sqlQueryPrepared("
                        DELETE FROM `tbl_message` WHERE message_id = :id", 
                                                      array(
                                                            
                                                            ':id' => $beskedId,
                                                        ))) {header('Location: index.php?side=beskeder');
                                                            } else {
                                                                echo 'Siden blev ikke slettet!';
                                                            }

