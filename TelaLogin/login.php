<?php
/**
 * ARQUIVO DE PROCESSAMENTO DE LOGIN
 * Recebe dados do formulário, valida no banco e cria sessão
 */

session_start(); // Inicia sessão para guardar dados do usuário logado

require_once 'config.php'; // Inclui arquivo de conexão com o banco

// ================== RECEBE DADOS DO FORMULÁRIO ==================
$email = $_POST['email'] ?? '';      // Pega email do formulário
$senha = $_POST['password'] ?? '';   // Pega senha do formulário

// ================== VALIDAÇÕES BÁSICAS ==================
if (empty($email) || empty($senha)) {
    // Se campos vazios, volta pro login com erro
    $_SESSION['erro'] = "Por favor, preencha todos os campos!";
    header("Location: TelaLogin.php"); 
    exit;
}

// Validação de formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "Email inválido!";
    header("Location: TelaLogin.php");
    exit;
}

try {
    // ================== BUSCA USUÁRIO NO BANCO ==================
    /**
     * Query segura usando prepared statement
     * :email é um placeholder que será substituído de forma segura
     * Isso previne SQL Injection
     */
    $sql = "SELECT id_usuario, nome_usuario, email_usuario, senha_usuario 
            FROM usuario 
            WHERE email_usuario = :email 
            LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();
    
    // ================== VERIFICA SENHA ==================
    if ($usuario) {
        /**
         * password_verify() compara senha em texto com hash
         * Se seu banco ainda não tem hash, vamos verificar direto
         * MAS ISSO É INSEGURO! Vou adicionar conversão para hash
         */
        
        // TEMPORÁRIO: Verifica senha sem hash (INSEGURO!)
        // Em produção, SEMPRE use password_hash() no cadastro
        if ($senha === $usuario['senha_usuario']) {
            
            // Regenera ID da sessão (previne session fixation)
            session_regenerate_id(true);
            
            // Salva dados do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome_usuario'];
            $_SESSION['usuario_email'] = $usuario['email_usuario'];
            $_SESSION['logado'] = true;
            
            /**
             * IMPORTANTE: Atualizar senha para hash na primeira vez
             * Isso migra senhas antigas para o formato seguro
             */
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE usuario SET senha_usuario = :senha WHERE id_usuario = :id");
            $update->execute([
                'senha' => $senha_hash,
                'id' => $usuario['id_usuario']
            ]);
            
            // Redireciona para dashboard
            header("Location: dashboard.php");
            exit;
            
        } else if (password_verify($senha, $usuario['senha_usuario'])) {
            // Se já estiver usando hash (após primeira atualização)
            
            session_regenerate_id(true);
            
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome_usuario'];
            $_SESSION['usuario_email'] = $usuario['email_usuario'];
            $_SESSION['logado'] = true;
            
            header("Location: dashboard.php");
            exit;
            
        } else {
            // Senha incorreta
            $_SESSION['erro'] = "Email ou senha incorretos!";
            header("Location: TelaLogin.php");
            exit;
        }
        
    } else {
        // Usuário não encontrado
        $_SESSION['erro'] = "Email ou senha incorretos!";
        header("Location: TelaLogin.php");
        exit;
    }
    
} catch(PDOException $e) {
    // Erro no banco de dados
    // Em produção, registre o erro em log e mostre mensagem genérica
    $_SESSION['erro'] = "Erro no sistema. Tente novamente mais tarde.";
    
    // Para debug (remova em produção):
    // $_SESSION['erro'] = "Erro BD: " . $e->getMessage();
    
    header("Location: TelaLogin.php");
    exit;
}

/**
 * NOTAS DE SEGURANÇA:
 * 1. Sempre use prepared statements para prevenir SQL Injection
 * 2. Use password_hash() no cadastro e password_verify() no login
 * 3. Regenere ID de sessão após login bem-sucedido
 * 4. Nunca mostre se foi email ou senha que estava errado
 * 5. Em produção, use HTTPS para proteger dados em trânsito
 */
?>