<?php
if (secCheckMethod('GET')) {
        $get = secGetInputArray(INPUT_GET);
            if (isset($get['id']) && !empty($get['id']) && is_numeric($get['id'])) {
            	$userId = $get['id'];
            } else {
            	// 404
                echo 'Der var fejl i brugerens id!';
            	// header('Location: index.php?side=bruger');
            }
}

if(sqlQueryPrepared("
                        
                        DELETE FROM `tbl_user` WHERE user_id = :id", 
                                                      array(
                                                            
                                                            ':id' => $userId,
                                                        ))) {header('Location: index.php?side=bruger');
                                                            } else {
                                                                echo 'Brugeren blev ikke slettet!';
                                                            }

// if(sqlQueryPrepared("DELETE FROM `users` WHERE user_id = :id", 
//                                                       array(
                                                            
//                                                             ':id' => $userId,
//                                                         ))) {header('Location: index.php?side=bruger');
//                                                             } else {
//                                                                 echo 'Produktet blev ikke slettet!';
//                                                             }
                                                        

