<?php
	if(secCheckLevel() > 98){
		$options = [
			'class' => 'striped responsive-table',
			'actions' => [
				'selector' => 'news_id',
				'edit' => 'index.php?side=retNyheder&id=',
				'delete' => 'index.php?side=sletNyheder&id=',
				'create' => 'index.php?side=opretNyheder'
			]
		];
	} else {
echo '<h5>Du har ikke adgang til nyheder!</h5>';
        	$page = 'index.php?side=logind';
        	$sec = "5";
        	header("Refresh: $sec; url=$page");
        die();
	}
	echo '<h5>Oversigt over nyheder</h5>';
	echo buildTable(
		['Titel', 'Indhold', 'Oprettet' ], 
		sqlQueryAssoc('SELECT `news_title`, `news_content`, `news_created`, `news_id` 
						FROM `tbl_news` ORDER BY news_created DESC'),
		$options
	);
?>