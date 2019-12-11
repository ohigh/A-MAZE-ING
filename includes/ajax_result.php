<?php
require_once 'db_conn.php';
require_once 'security.php';
require_once 'sqlOperations.php';

if (secCheckMethod('POST')) {
    $post = secGetInputArray(INPUT_POST);
    if (isset($post)) {
        $score = $post['total'];
        $point = $post['point'];
        $time = $post['time'];
        $userId = $_SESSION['userid'];
$gameId = $_SESSION['game'];
        print_r($score . ' & ' . $point . ' & ' . $time);
        print_r($userId . ' & ' . $gameId);

    sqlQueryPrepared("INSERT INTO `tbl_result`(`result_point`, `result_time`, `result_score`)  
                                    VALUES (:point,:time,:score);
                                    SELECT LAST_INSERT_ID() INTO @lastId;
                                    INSERT INTO `tbl_entrant`(`fk_entrant_game`, `fk_entrant_result`, `fk_entrant_user`) 
                                    VALUES (:game, @lastId,:id)
                                    ", array(
                            ':point' => $point,
                            ':time' => $time,
                            ':score' => $score,
                            ':game' => $gameId,
                            ':id' => $userId
                                    ));

       
}
}