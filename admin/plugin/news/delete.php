<?php
if (secCheckMethod('GET')) {
        $get = secGetInputArray(INPUT_GET);
            if (isset($get['id']) && !empty($get['id']) && is_numeric($get['id'])) {
            	$userId = $get['id'];
            } else {
            	// 404
                echo 'Nyhedn findes ikke!';
            	// header('Location: index.php?side=bruger');
            }
}

if(sqlQueryPrepared("
                        DELETE FROM `tbl_news` WHERE news_id = :id", 
                                                      array(
                                                            
                                                            ':id' => $userId,
                                                        ))) {header('Location: index.php?side=nyheder');
                                                            } else {
                                                                echo 'Siden blev ikke slettet!';
                                                            }

