<?php
function textSearch($searchText){
    // print_r($serie);
    // die();
    global $conn;
    $sql = "SELECT `news_title`, `news_content` FROM `tbl_news`;
            SELECT `event_title`, `event_content` FROM `tbl_event` WHERE";
    
    if (isset($searchText) && !empty($searchText)) {
    $sql .= " OR `news_title` LIKE '%$searchText%' 
    OR `news_content` LIKE '%$searchText%'
    OR `event_title` LIKE '%$searchText%' 
    OR `event_content` LIKE '%$searchText%'";  

    $stmt = $conn->prepare($sql);
    if ($stmt->execute() && ($stmt->rowCount() >= 1)) {		
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }    
        return $result; 
    } else {
        return false;
    } 
}

