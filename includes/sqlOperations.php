<?php

    /**
     * Opret bruger i db
     *
     * @param [STRING] $sql
     * @param [ARRAY] $data
     * @return void
     */
    function sqlQueryPrepared($sql, $data) {
        global $conn;
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }
    function sqlQueryAssoc($sql, $data = []){
		global $conn;
		$stmt = $conn->prepare($sql);
		if($stmt->execute($data)){
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
    