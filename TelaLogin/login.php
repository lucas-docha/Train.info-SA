<?php
/**
 * ARQUIVO DE PROCESSAMENTO DE LOGIN
 * Busca na tabela ADMIN
 */

session_start();
require_once 'config.php';

// ================== RECEBE DADOS DO FORMULÁRIO ==================
$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

// ================== VALIDAÇÕES BÁSICAS ==================
if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = "Por favor, preencha todos os campos!";
    header("Location: TelaLogin.php"); 
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "Email inválido!";
    header("Location: TelaLogin.php");
    exit;
}

try {
    // ================== BUSCA ADMIN NO BANCO ==================
    $sql = "SELECT id_admin, nome_admin, email_admin, senha_admin 
            FROM admin 
            WHERE email_admin = :email 
            LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch();
    
    // ================== VERIFICA SENHA ==================
    if ($admin) {
        
        // Verifica senha sem hash (TEMPORÁRIO - para migração)
        if ($senha === $admin['senha_admin']) {
            
            session_regenerate_id(true);
            
            // Salva dados do admin na sessão
            $_SESSION['usuario_id'] = $admin['id_admin'];
            $_SESSION['usuario_nome'] = $admin['nome_admin'];
            $_SESSION['usuario_email'] = $admin['email_admin'];
            $_SESSION['logado'] = true;
            
            // Atualizar senha para hash
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE admin SET senha_admin = :senha WHERE id_admin = :id");
            $update->execute([
                'senha' => $senha_hash,
                'id' => $admin['id_admin']
            ]);
            
            header("Location: dashboard.php");
            exit;
            
        } else if (password_verify($senha, $admin['senha_admin'])) {
            // Se já estiver usando hash
            
            session_regenerate_id(true);
            
            $_SESSION['usuario_id'] = $admin['id_admin'];
            $_SESSION['usuario_nome'] = $admin['nome_admin'];
            $_SESSION['usuario_email'] = $admin['email_admin'];
            $_SESSION['logado'] = true;
            
            header("Location: dashboard.php");
            exit;
            
        } else {
            $_SESSION['erro'] = "Email ou senha incorretos!";
            header("Location: TelaLogin.php");
            exit;
        }
        
    } else {
        $_SESSION['erro'] = "Email ou senha incorretos!";
        header("Location: TelaLogin.php");
        exit;
    }
    
} catch(PDOException $e) {
    $_SESSION['erro'] = "Erro no sistema. Tente novamente mais tarde.";
    header("Location: TelaLogin.php");
    exit;
}
?>