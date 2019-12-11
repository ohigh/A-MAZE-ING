<?php
// $maxAge = 300;

/**
 * Tjekker request method (GET, POST, PUT, DELETE)
 *
 * @param [STRING] $method
 * @return Boolean
 */
function secCheckMethod($method)
{
    return (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS) === strtoupper($method)) ? true : false;
}

/**
 * Retunér filtreret SuperGlobal
 *
 * @param [STRING] $input
 * @return string
 */

function secGetInputArray($input)
{
    return filter_input_array(strtoupper($input), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Generere en "Token", der sikre at formular sendes fra dette site
 *
 * @return void
 */

function secGenerateToken()
{
    if (function_exists('random_bytes')) {
        $_SESSION['Token'] = bin2hex(randome_bytes(32));
    } elseif (function_exists('mcrypt_create_iv')) {
        $_SESSION['Token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['Token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
    $_SESSION['TokenAge'] = time();
}
// }

/**
 * Indsæt et "hidden" inputfelt med navnet "_once" og value "Token" 
 *
 * @return STRING (HTML)
 */

function secCreateTokenInput()
{
    secGenerateToken();
    return  '<input name="_once" type="hidden" value="' . $_SESSION['Token'] . '">';
}

/**
 * Validerer Token og alder
 * Sætter en begrænsning på hvor længe $_SESSION['Token'] er gyldig 
 *
 * @param [STRING] $token
 * @param [INT] $maxAge (levetid på Token i sekunder)
 * @return boolean 
 * 
 */

function secValidateToken($token, $maxAge = 300)
{
    // if (!isSessionStarted()) {
    // 	session_start();
    // }
    if ($token != $_SESSION['Token'] || (time() - $_SESSION['TokenAge']) > $maxAge) {
        return false;
    } else {
        unset($_SESSION['Token'], $_SESSION['TokenAge']);
        return true;
    }
}

/**
 * Tjekker brugernavn og adgangskode
 * og sætter cookie eller session ved login
 * 
 * @param [STRING] $mail (fra loginformular)
 * @param [STRING] $adgangskode (fra loginformular)
 * @return boolean 
 */

function secSetLogIn($mail, $adgangskode, $remember)
{
    global $conn;
    $stmt = $conn->prepare("SELECT user_id, user_password, user_email, fk_user_profile FROM tbl_user WHERE user_email = :email");
    $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
    if ($stmt->execute() && ($stmt->rowCount() === 1)) {
        $resultat = $stmt->fetch(PDO::FETCH_OBJ);
        if (!password_verify($adgangskode, $resultat->user_password)) {
            return false;
        } else  if ($remember == 'checked') {
            setcookie('userid', $resultat->user_id, time() + (3600 * 24 * 30));
            header('Location: index.php');
            return true;
        } else {
            $_SESSION['userid'] = $resultat->user_id;
            $_SESSION['username'] = $resultat->user_email;
            header('Location: index.php');
            return true;
            // print_r($_SESSION);
        }
    }
}

/**
 * Tjekker om bruger er logget ind
 *
 * @return boolean
 */

function secIsLoggedIn()
{
    // if (!isSessionStarted()) {
    //     session_start();
    // }
    if (isset($_SESSION['userid']) && isset($_SESSION['username']) && !empty($_SESSION['userid']) && !empty($_SESSION['username'])) {
        global $conn;
        $stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE user_email = :email AND user_id = :id");
        $stmt->bindParam(':email', $_SESSION['username'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $_SESSION['userid'], PDO::PARAM_INT);
        return ($stmt->execute() && $stmt->rowCount() === 1) ? true : false;
    } else if (isset($_COOKIE['userid']) && !empty($_COOKIE['userid'])) {
        global $conn;
        $stmt = $conn->prepare("SELECT user_id, user_email FROM tbl_user WHERE user_id = :id");
        $stmt->bindParam(':id', $_COOKIE['userid'], PDO::PARAM_INT);
        if ($stmt->execute() && ($stmt->rowCount() === 1)) {

            $resultat = $stmt->fetch(PDO::FETCH_OBJ);
            $_SESSION['userid'] = $resultat->user_id;
            $_SESSION['username'] = $resultat->user_email;
            return  true;
        } else {
            return false;
        }
    }
}

/**
 * Tjekker brugerens retigheder
 * og returnerer brugerniveau
 * 
 *
 * @return int 
 */
function secCheckLevel()
{
    global $conn;
    $stmt = $conn->prepare("SELECT role_level FROM `tbl_user`
								INNER JOIN `tbl_role` ON `tbl_role`.`role_id` = `tbl_user`.`fk_user_role`
								WHERE `tbl_user`.`user_email` = :mail");
    $stmt->bindParam(':mail', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $resultat = $stmt->fetch(PDO::FETCH_OBJ);
        return $resultat->role_level;
    } else {
        return 0;
    }
}
