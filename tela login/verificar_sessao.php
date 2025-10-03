<?php
/**
 * VERIFICAÇÃO DE SESSÃO SIMPLIFICADO
 * Sem dependências de tabelas extras no banco
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaLogado() {
    return isset($_SESSION['logado']) && 
           $_SESSION['logado'] === true && 
           isset($_SESSION['usuario_id']);
}

function dadosUsuario($campo = null) {
    if (!estaLogado()) {
        return null;
    }
    
    if ($campo) {
        return $_SESSION['usuario_' . $campo] ?? null;
    }
    
    return [
        'id' => $_SESSION['usuario_id'] ?? null,
        'nome' => $_SESSION['usuario_nome'] ?? null,
        'email' => $_SESSION['usuario_email'] ?? null
    ];
}

function protegerPagina($redirecionarPara = 'TelaLogin.php') {
    if (!estaLogado()) {
        $_SESSION['erro'] = "Você precisa fazer login para acessar esta página!";
        header("Location: $redirecionarPara");
        exit;
    }
    
    verificarTimeoutSessao();
}

function verificarTimeoutSessao() {
    $tempoLimite = 1800; // 30 minutos
    
    if (isset($_SESSION['ultima_atividade'])) {
        $tempoInativo = time() - $_SESSION['ultima_atividade'];
        
        if ($tempoInativo > $tempoLimite) {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['erro'] = "Sua sessão expirou por inatividade!";
            header("Location: TelaLogin.php");
            exit;
        }
    }
    
    $_SESSION['ultima_atividade'] = time();
}

function nomeExibicao() {
    $nome = dadosUsuario('nome');
    if ($nome) {
        $partes = explode(' ', $nome);
        return $partes[0];
    }
    return 'Usuário';
}

if (estaLogado()) {
    if (!isset($_SESSION['tempo_criacao'])) {
        $_SESSION['tempo_criacao'] = time();
    } else if (time() - $_SESSION['tempo_criacao'] > 300) {
        session_regenerate_id(true);
        $_SESSION['tempo_criacao'] = time();
    }
}
?>