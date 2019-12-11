<?php
// Tjek brugerrolle
if(secCheckLevel() > 98){
} else {echo '<h5>Du har ikke adgang til beskeder!</h5>';	
		$page = 'index.php?side=dash';
		$sec = "5";
		header("Refresh: $sec; url=$page");
	die();
}

// Parameter til liste
$options = [
	'class' => 'striped responsive-table',
	'actions' => [
		'selector' => 'message_id',
		'view' => 'index.php?side=visBesked&id=',
		'delete' => 'index.php?side=sletBesked&id='
	]
];

?>
<!--// Side indhold-->
<h5>Oversigt over meddelser</h5>
<!--  -->
<?php
print_r(@$post);
// print_r($value->user_id);
	echo buildTable(
		['Afsender', 'Email', 'Mobil', 'Indhold', 'Modtaget' ], 
		sqlQueryAssoc('SELECT `message_name`, `message_email`, `message_phone`, `message_content`, `message_created`, `message_id`
						FROM `tbl_message`'),
		$options
	);
?>