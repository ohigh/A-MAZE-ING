<?php
	if(secCheckLevel() > 98){
	} else {echo '<h5>Du har ikke adgang til abonnenter!</h5>';
			$page = 'index.php?side=dash';
			$sec = "5";
			header("Refresh: $sec; url=$page");
		die();
	}
		$options = [
			'class' => 'striped responsive-table',
			'actions' => [
				'selector' => 'newsletter_id',
				'delete' => 'index.php?side=sletAbonnenter&id=',
			]
		];
	echo '<h5>Oversigt over Abonnenter</h5>';
	echo buildTable(
		['Abonnents email'], 
		sqlQueryAssoc('SELECT `newsletter_email`, `newsletter_id`
						FROM `tbl_newsletter`'),
		$options
	);
?>