<?php
if (secIsLoggedIn()) {
        session_destroy();
        setcookie("userid", "", time() - 3600);
        header ('Location: ./index.php?side=forside');
}