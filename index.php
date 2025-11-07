<?php
/**
 * =====================================================
 * PÁGINA INICIAL DO SISTEMA
 * =====================================================
 * Redireciona para login ou dashboard dependendo da sessão
 */

session_start();

// Verifica se usuário está logado
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    // Redireciona para dashboard
    header("Location: dashboard.php");
} else {
    // Redireciona para login
    header("Location: TelaLogin.php");
}

exit;
?>
