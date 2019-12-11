<?php
require_once '../includes/media.php';

$error = [];
if (secCheckMethod('GET') || secCheckMethod('POST')) {
	$get = secGetInputArray(INPUT_GET);
	if (isset($get['id']) && !empty($get['id']) && is_numeric($get['id'])) {
		$newsId = $get['id'];
	} else {
		// 404
		echo 'Nyheden findes ikke!';
		header('Location: index.php?side=nyheder');
	}
}
$stmt = $conn->prepare("SELECT `news_title`, `news_content`, `news_created`, `news_id`, fk_media_news, media_path 
						FROM `tbl_news`
						INNER JOIN tbl_media
        ON fk_media_news = news_id
                        WHERE news_id = :id");
$stmt->bindParam(':id', $newsId, PDO::PARAM_STR);
if ($stmt->execute() && ($stmt->rowCount() === 1)) {
	$resultat = $stmt->fetch(PDO::FETCH_OBJ);
	$id = $resultat->news_id;
	$eksistImg = $resultat->fk_media_news;
}
// print_r($resultat);
// echo 'ID: '.print_r($resultat->profilID, true);
// print_r($newsId);

if (secCheckMethod('POST')) {
	$post    		= secGetInputArray(INPUT_POST);
	print_r(@$post);
	if (!secValidateToken($post['_once'], 600)) {
		$error['session'] = 'Din session er udløbet! Prøv igen.';
	}
	$titel     = validStringBetween($post['titel'], 2, 20) ? $post['titel'] : $error['titel'] = 'Fejl i titlen.';
	$indhold = validMixedBetween($post['indhold'], 1, 1200) ? $post['indhold'] : $error['indhold'] = 'fejl besked indhold!';



	if (sizeof($error) === 0) {


		sqlQueryPrepared(
			"
					UPDATE `tbl_news` SET `news_title`=:titel, 
					`news_content`=:indhold
					WHERE news_id = $id;
				",
			array(

				':titel' => $titel,
				':indhold' => $indhold,

			)
		);
		// $eventId = $conn->lastInsertId();
		// Opret nyt billede

		if ($_FILES['filUpload']['name'] !== '') {
			$billede = mediaImageUploader('filUpload', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'], '../media');
			if ($billede['code']) {
				// Resize fuktion start
				$imgPath = '../media/' . $billede['name'];
				$Path =  $billede['name'];
				$mediumPath = 'medium/' . 'medium_' . $billede['name'];
				$smallPath = 'small/' . 'small_' . $billede['name'];
				resize_image($imgPath, 210, 140, $smallPath);
				resize_image($imgPath, 300, 200, $mediumPath);
				resize_image($imgPath, 450, 300, $Path);

		


						sqlQueryPrepared(
							"
									UPDATE `tbl_media` SET `media_path`=:sti, `media_small`=:small, `media_medium`=:medium, `media_type`=:type
									WHERE fk_media_news =  $eksistImg;
								",
							array(
				
								':sti' => $Path,
						':small' => $smallPath,
						':medium' => $mediumPath,
						':type' => $billede['type']
						
				
							)
						);
						
				
				// $eksistImg = $conn->lastInsertId();
			} else {
				$error['filUpload'] = $billede['msg'];
			}
		}




		header('Location: ?side=nyheder');
	}
}


foreach ($error as $message) {
	@$msg .= "<p>$message</p>" . PHP_EOL;
}
?>
<h5>Ret nyhed</h5>
<div class="row">
	<div class="col s12 right">
		<a href="./index.php?side=nyheder" class="btn-floating btn-large waves-effect waves-light blue  right"><i class="material-icons">navigate_before</i></a>
	</div><br>
	<form action="" method="post" enctype="multipart/form-data">
		<?= secCreateTokenInput() ?>
		<?= @$msg ?>
		<fieldset>
			<legend>Ret nyhed</legend>

			<div class="input-field col s12">
				<label for="titel">Titel (skal være udfyldt)</label>
				<input type="text" name="titel" id="titel" min="2" maxlength="20" value="<?= $resultat->news_title ?>">
			</div>

			<div class="col s12">
				<label for="articleContent1">Indhold (skal være udfyldt)</label>
				<textarea name="indhold" rows="" cols="" id="articleContent"><?= $resultat->news_content ?></textarea>
			</div>
			<div>Eksisterende billede tilhørende:</div>
			<?php
			// $stmt = $conn->prepare("SELECT `media_path`, `media_id`, `fk_boat_media`
			//                         FROM `tbl_media`
			//                         INNER JOIN `tbl_boat`
			//                         ON `media_id` = `fk_boat_media`
			//                         WHERE `media_id` = $eksistImg");

			// $stmt->execute();
			//     $resultat = $stmt->fetch(PDO::FETCH_OBJ);
			// Print_r($resultat->fk_event_media);
			// Print_r($eksistImg );
			?>
			<!-- <div class="row"> -->
			<div class="input-field col s12 m4">
				<img src="../media/<?= $resultat->media_path ?>" class="responsive-img" alt="">
			</div>


			<div class="input-field col s12 m6">
				<div>Skift eksisterende billede:</div>
				<div class="file-field input-field">
					<div class="btn blue ">
						<span>Vælg fil</span>
						<input name="filUpload" type="file" value="<?= $_FILES['filUpload'] ?>">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text" placeholder="Upload billede (hvis du ønker et nyt billede)">
					</div>
				</div>
			</div>
			<!-- </div>  -->
			<div class="input-field col s12">
				<button name="opretSide" type="submit" class="btn waves-effect waves-light blue ">Ret</button>
			</div>
		</fieldset>
	</form>
</div>
</div>
<script type="text/javascript">
	CKEDITOR.replace('articleContent');
</script>