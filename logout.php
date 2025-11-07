<?php
/**
 * =====================================================
 * LOGOUT - ENCERRA SESSÃO DO USUÁRIO
 * =====================================================
 * Este arquivo encerra a sessão e redireciona para login
 */

// Inicia sessão
session_start();

// =====================================================
// LIMPA TODOS OS DADOS DA SESSÃO
// =====================================================
// Remove todas as variáveis de sessão
session_unset();

// Destrói a sessão completamente
session_destroy();

// =====================================================
// REDIRECIONA PARA LOGIN
// =====================================================
// Redireciona para a tela de login
header("Location: TelaLogin.php");
exit;

/**
 * =====================================================
 * NOTAS
 * =====================================================
 * 
 * 1. session_unset() remove todas as variáveis da sessão
 * 
 * 2. session_destroy() destrói a sessão no servidor
 * 
 * 3. O redirecionamento é imediato com exit
 * 
 * 4. Em produção, considere:
 *    - Registrar logout em log de auditoria
 *    - Invalidar tokens de autenticação
 *    - Limpar cookies relacionados
 */
?>
