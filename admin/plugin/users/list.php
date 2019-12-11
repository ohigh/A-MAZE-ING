<?php
	if(secCheckLevel() > 49){
		echo '<h5>Oversigt over medlemmer</h5>';
		$age = 17;
		// foreach($conn->query('SELECT `profile_firstname`, `profile_sirname`, `role_name`, `user_id`, `profile_username`,
		// 							  `profile_age`, `user_email`
		// 					FROM `tbl_profile` 
		// 					INNER JOIN tbl_user 
		// 					ON tbl_user.fk_user_profile = tbl_profile.profile_id
		// 					INNER JOIN tbl_role
		// 					ON tbl_user.fk_user_role = tbl_role.role_id') as $row); 
		// 					{
		// 	if ($row['profile_age']){'Begynder';}
		// }
		
	} else {
echo '<h5>Du har ikke adgang til medlemmer!</h5>';
        	$page = 'index.php?side=dash';
        	$sec = "5";
        	header("Refresh: $sec; url=$page");
        die();
	}
	
	?>
	<div class"row">
	<div class="col s12 right">
		<a href="index.php?side=opretBruger" class="btn-floating btn-large waves-effect waves-light blue  right"><i class="material-icons">add</i></a>
	</div>
	<div class="col s12">
		<table class="striped responsive-table">
			<thead>
				<tr>
					<th>Fornavn</th>
					<th>Efternavn</th>
					<th>E-mail</th>
					<th>Mobil</th>
					<th>Rolle</th>
					<th>Niveau</th>
				</thead>
				<tbody>
					
				</tr>
				<?php
			foreach($conn->query('SELECT `profile_firstname`, `profile_sirname`, `role_name`, `user_id`, `profile_username`,
												`profile_age`, `user_email`
							FROM `tbl_profile` 
							INNER JOIN tbl_user 
							ON tbl_user.fk_user_profile = tbl_profile.profile_id
							INNER JOIN tbl_role
							ON tbl_user.fk_user_role = tbl_role.role_id') as $row) 
							{$bithdayDate = $row['profile_age'];
							 $age = floor((time() - strtotime($bithdayDate)) / 31556926);
								
				echo '
				<tr>
				<td>'.$row["profile_firstname"].'</td>
				<td>'.$row["profile_sirname"].'</td>
				<td>'.$row["user_email"].'</td>
				<td>'.$row["profile_username"].'</td>
				<td>'.$row["role_name"].'</td>
				<td>';if ($age < 15){echo "Kid";} else if ($row["profile_age"] > 14 && $age < 25) {echo "Youngster";} else if ($age > 24) {echo "Oldtimer";} echo'</td>
				<td style="width: 20px;"><a href="index.php?side=retBruger&id='.$row["user_id"].'"><i class="material-icons">mode_edit</i></a></td>
				<td style="width: 20px;"><a onclick="return(confirm(\'Er du sikker på at du vil slette?\'))" href="index.php?side=sletBruger&id='.$row["user_id"].'"><i class="material-icons">delete</i></a></td>
				</tr>';
			}
		?>	
				</tbody>
		</table>
	</div>
	</div