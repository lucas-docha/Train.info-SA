<?php
/**
 * =====================================================
 * PROCESSAMENTO DE LOGIN
 * =====================================================
 * Este arquivo processa o formulário de login
 * Valida credenciais e cria sessão do usuário
 */

// Inicia sessão
session_start();

// Inclui configuração do banco
require_once 'config.php';

// =====================================================
// RECEBE DADOS DO FORMULÁRIO
// =====================================================
$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

// =====================================================
// VALIDAÇÕES BÁSICAS
// =====================================================

// Verifica se campos foram preenchidos
if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = "Por favor, preencha todos os campos!";
    header("Location: TelaLogin.php"); 
    exit;
}

// Valida formato do email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "Email inválido!";
    header("Location: TelaLogin.php");
    exit;
}

try {
    // =====================================================
    // BUSCA USUÁRIO NO BANCO
    // =====================================================
    // Busca na tabela unificada de usuários
    $sql = "SELECT id_usuario, nome_usuario, email_usuario, senha_usuario, tipo_usuario 
            FROM usuarios 
            WHERE email_usuario = :email 
            LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();
    
    // =====================================================
    // VERIFICA SE USUÁRIO EXISTE E SENHA ESTÁ CORRETA
    // =====================================================
    if ($usuario && password_verify($senha, $usuario['senha_usuario'])) {
        
        // =====================================================
        // LOGIN BEM-SUCEDIDO
        // =====================================================
        
        // Regenera ID da sessão por segurança
        // Previne ataques de fixação de sessão
        session_regenerate_id(true);
        
        // Salva dados do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome_usuario'];
        $_SESSION['usuario_email'] = $usuario['email_usuario'];
        $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
        $_SESSION['logado'] = true;
        $_SESSION['ultima_atividade'] = time();
        $_SESSION['tempo_criacao'] = time();
        
        // Redireciona para o dashboard
        header("Location: dashboard.php");
        exit;
        
    } else {
        // =====================================================
        // LOGIN FALHOU
        // =====================================================
        // Mensagem genérica por segurança
        // Não revela se o problema é email ou senha
        $_SESSION['erro'] = "Email ou senha incorretos!";
        header("Location: TelaLogin.php");
        exit;
    }
    
} catch(PDOException $e) {
    // =====================================================
    // ERRO NO BANCO DE DADOS
    // =====================================================
    // Em produção, registre o erro em log
    // Não mostre detalhes ao usuário
    error_log("Erro no login: " . $e->getMessage());
    
    $_SESSION['erro'] = "Erro no sistema. Tente novamente mais tarde.";
    header("Location: TelaLogin.php");
    exit;
}

/**
 * =====================================================
 * NOTAS DE SEGURANÇA
 * =====================================================
 * 
 * 1. Senhas são verificadas com password_verify()
 *    Nunca compare senhas em texto puro
 * 
 * 2. Mensagens de erro são genéricas para não revelar
 *    se o email existe no sistema
 * 
 * 3. session_regenerate_id() previne fixação de sessão
 * 
 * 4. Prepared statements previnem SQL Injection
 * 
 * 5. Em produção, implemente:
 *    - Limite de tentativas de login
 *    - CAPTCHA após X tentativas falhas
 *    - Log de tentativas de login
 *    - Autenticação de dois fatores (2FA)
 */
?>
