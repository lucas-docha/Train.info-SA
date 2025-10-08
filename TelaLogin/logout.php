<?php
/**
 * LOGOUT - ENCERRA SESSÃO
 */

session_start();
session_unset();
session_destroy();

header("Location: TelaLogin.php");
exit;
?>