
<?php
// require_once './includes/db_conn.php';
// require_once './includes/security.php';
// require_once '/includes/validate.php';
// require_once '/includes/sqlOperations.php';
// require_once '/includes/media.php';
require_once '/db_conn.php';
require_once '/security.php';
require_once '/validate.php';
require_once '/sqlOperations.php';



if (secCheckMethod('POST') || secCheckMethod('GET')) {
  $get = secGetInputArray(INPUT_GET);
  $post = secGetInputArray(INPUT_POST);
}

if (secIsLoggedIn()) {
  $stmt = $conn->prepare("SELECT `profile_firstname`, `user_email`
                            FROM `tbl_profile`
                            INNER JOIN tbl_user 
                            ON tbl_user.fk_user_profile = tbl_profile.profile_id 
                            WHERE user_id = :id");
  $stmt->bindParam(':id', $_SESSION['userid'], PDO::PARAM_INT);
  if ($stmt->execute() && ($stmt->rowCount() === 1)) {
    $resultat = $stmt->fetch(PDO::FETCH_OBJ);
  }
}



if (isset($get['side']) && !empty($get['side'])) {
  $titel = ucwords($get['side']);
}
?>



<!DOCTYPE html>
<html lang="da">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= @$titel ?> - A-MAZE-ING</title>
  <!-- Material Icon CDN -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Materialize CSS CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Bitter|Peddana&amp;subset=latin-ext,telugu" rel="stylesheet"> <!-- Your custom styles -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">

</head>


