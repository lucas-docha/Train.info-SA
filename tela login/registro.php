<?php
/**
 * ARQUIVO DE PROCESSAMENTO DE CADASTRO SIMPLIFICADO
 * Adaptado para funcionar com a estrutura atual do banco
 */

session_start();
require_once 'config.php';

// ================== RECEBE DADOS DO FORMULÁRIO ==================
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$cep = $_POST['cep'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirma_senha = $_POST['confirma_senha'] ?? '';

// ================== VALIDAÇÕES ==================
$erros = [];

// Valida nome
if (strlen(trim($nome)) < 3) {
    $erros[] = "Nome deve ter pelo menos 3 caracteres";
}

// Valida email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "Email inválido";
}

// Valida CPF
$cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);
if (strlen($cpf_limpo) != 11) {
    $erros[] = "CPF deve ter 11 dígitos";
}

// Valida telefone (opcional)
if (!empty($telefone)) {
    $tel_limpo = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($tel_limpo) < 10 || strlen($tel_limpo) > 11) {
        $erros[] = "Telefone inválido";
    }
} else {
    $tel_limpo = null;
}

// Valida CEP (opcional)
if (!empty($cep)) {
    $cep_limpo = preg_replace('/[^0-9]/', '', $cep);
    if (strlen($cep_limpo) != 8) {
        $erros[] = "CEP deve ter 8 dígitos";
    }
} else {
    $cep_limpo = null;
}

// Valida senha
if (strlen($senha) < 6) {
    $erros[] = "Senha deve ter pelo menos 6 caracteres";
}

// Confirma senhas
if ($senha !== $confirma_senha) {
    $erros[] = "As senhas não conferem";
}

// ================== VERIFICA DUPLICADOS ==================
if (empty($erros)) {
    try {
        // Verifica email
        $sql = "SELECT COUNT(*) as total FROM usuario WHERE email_usuario = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $resultado = $stmt->fetch();
        
        if ($resultado['total'] > 0) {
            $erros[] = "Este email já está cadastrado";
        }
        
        // Verifica CPF
        $sql = "SELECT COUNT(*) as total FROM usuario WHERE cpf_usuario = :cpf";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['cpf' => $cpf_limpo]);
        $resultado = $stmt->fetch();
        
        if ($resultado['total'] > 0) {
            $erros[] = "Este CPF já está cadastrado";
        }
        
    } catch(PDOException $e) {
        $erros[] = "Erro ao verificar dados: " . $e->getMessage();
    }
}

// ================== SE HOUVER ERROS, VOLTA ==================
if (!empty($erros)) {
    $_SESSION['erros_cadastro'] = $erros;
    $_SESSION['dados_form'] = $_POST;
    header("Location: TelaRegistro.php");
    exit;
}

// ================== CADASTRA USUÁRIO ==================
try {
    // Criptografa senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Insere no banco
    $sql = "INSERT INTO usuario (
                nome_usuario, 
                email_usuario, 
                senha_usuario, 
                cpf_usuario, 
                numerotele_usuario, 
                cep_usuario
            ) VALUES (
                :nome, 
                :email, 
                :senha, 
                :cpf, 
                :telefone, 
                :cep
            )";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        'nome' => trim($nome),
        'email' => trim($email),
        'senha' => $senha_hash,
        'cpf' => $cpf_limpo,
        'telefone' => $tel_limpo,
        'cep' => $cep_limpo
    ]);
    
    // Sucesso
    $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login para continuar.";
    unset($_SESSION['dados_form']);
    unset($_SESSION['erros_cadastro']);
    
    header("Location: TelaLogin.php");
    exit;
    
} catch(PDOException $e) {
    $_SESSION['erros_cadastro'] = ["Erro ao cadastrar: " . $e->getMessage()];
    header("Location: TelaRegistro.php");
    exit;
}
?>