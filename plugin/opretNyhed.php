<?php
require_once './includes/media.php';
$error   		= [];

if (secCheckMethod('POST')) {
	$post    		= secGetInputArray(INPUT_POST);
	// print_r(@$post);
	if (!secValidateToken($post['_once'], 600)) {
		$error['session'] = 'Din session er udløbet! Prøv igen.';
	}

	// Opret indlæg
	if (isset($post['opretBlog'])) {
		$titel     = validStringBetween($post['titel'], 2, 128) ? $post['titel'] : $error['titel'] = 'Fejl i titlen.';
		$indhold = validMixedBetween($post['indhold'], 1, 1200) ? $post['indhold'] : $error['indhold'] = 'fejl besked indhold!';
		// $forfatter = @$resultat->profile_firstname.' '.@$resultat->profile_sirname;

		if (sizeof($error) === 0) {


			sqlQueryPrepared(
				"
						INSERT INTO `tbl_news`(`news_title`, `news_content`) 
						VALUES (:titel, :indhold);
					",
				array(
					':titel' => $titel,
					':indhold' => $indhold,
				)
			);

			// billede opload

			$eventId = $conn->lastInsertId();






			// if(sizeof($error) === 0){
			// Opret billeder i db
			if ($_FILES['filUpload']['name'] !== '') {
				$billede = mediaImageUploader('filUpload', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'], 'media/');
				if ($billede['code']) {
					// Resize fuktion start
					$imgPath = 'media/' . $billede['name'];
					$Path = $billede['name'];
					$mediumPath = 'medium/' . 'medium_' . $billede['name'];
					$smallPath = 'small/' . 'small_' . $billede['name'];
					resize_image($imgPath, 210, 140, $smallPath);
					resize_image($imgPath, 300, 200, $mediumPath);
					resize_image($imgPath, 450, 300, $Path);

					$sql =
						"
                    INSERT INTO `tbl_media`(`media_path`, `media_small`, `media_medium`, `media_type`, `fk_media_news`) 
                    VALUES (:sti, :small, :medium, :type, :lastId);
                    SELECT LAST_INSERT_ID() INTO @lastId;
                    ";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array(
						':sti' => $Path,
						':small' => $smallPath,
						':medium' => $mediumPath,
						':type' => $billede['type'],
						':lastId' => $eventId,
					));
				} else {
					$error[] = $billede['msg'];
				}
			}
		} else {
			$error = ['Du mangler at udfylde et felt eller uploade min. et billede!'];
		}
		header('Location: ?side=nyheder#news');

	}
}

foreach ($error as $message) {
	@$msg .= "<p>$message</p>" . PHP_EOL;
}
$tokenInput = secCreateTokenInput();
?>
<h5>Opret nyhed</h5>
<div class="row">
	<div class="col s12 right">
		<a href="./index.php?side=nyheder" class="btn-floating btn-large waves-effect waves-light blue  right"><i class="material-icons">navigate_before</i></a>
	</div>
</div>

<!--Opret nyhede-->
<div class="row">
	<div class="col s12">
		<form action="" method="post" enctype="multipart/form-data">
			<?= $tokenInput ?>
			<?= @$msg ?>
			<fieldset>
				<legend>Opret nyhed</legend>
				<div class="input-field col s12">
					<label for="titel">Titel</label>
					<input type="text" name="titel" id="titel" min="2" maxlength="128" placeholder="Skriv nyhederens titel (skal udfyldes)">
				</div>
				<div class="col s12">
					<label for="articleContent">Indhold (skal udfyldes)</label>
					<textarea name="indhold" rows="" cols="" id="articleContent" placeholder="Skriv sidens indhold (skal udfyldes)"></textarea>
				</div>
				<div class="input-field col s12">
        <div class="file-field input-field">
            <div class="btn blue ">
                <span>Vælg fil</span>
                <input name="filUpload" type="file" value="<?=$_FILES['filUpload']?>">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload profilbillede (valgfrit)"> 
            </div>
        </div>
    </div>
				<div class="input-field col s12">
					<button name="opretBlog" type="submit" class="btn waves-effect waves-light blue ">Opret</button>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript">
	CKEDITOR.replace('articleContent');
</script>