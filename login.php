<?php
/**
 * =====================================================
 * PROCESSAMENTO DE LOGIN - VERSÃO CORRIGIDA
 * =====================================================
 * Este arquivo processa o formulário de login
 * Valida credenciais e cria sessão do usuário
 * 
 * MODIFICAÇÃO: Aceita tanto senhas hasheadas quanto em texto plano
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
    
    // Variável para controlar se a senha está correta
    $senha_valida = false;
    
    if ($usuario) {
        // Verifica se a senha no banco está hasheada
        // password_get_info retorna informações sobre o hash
        // Se 'algo' for null, significa que não é um hash válido (texto plano)
        $info_senha = password_get_info($usuario['senha_usuario']);
        
        if ($info_senha['algo'] !== null) {
            // =====================================================
            // SENHA HASHEADA - USA password_verify()
            // =====================================================
            $senha_valida = password_verify($senha, $usuario['senha_usuario']);
            
            // Opcional: Atualiza o hash se necessário (rehashing)
            // Útil quando o algoritmo ou custo do hash muda
            if ($senha_valida && password_needs_rehash($usuario['senha_usuario'], PASSWORD_DEFAULT)) {
                $novo_hash = password_hash($senha, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE usuarios SET senha_usuario = :senha WHERE id_usuario = :id");
                $update_stmt->execute([
                    'senha' => $novo_hash,
                    'id' => $usuario['id_usuario']
                ]);
            }
            
        } else {
            // =====================================================
            // SENHA EM TEXTO PLANO - COMPARAÇÃO DIRETA
            // =====================================================
            // ATENÇÃO: Isto é temporário para compatibilidade
            // Em produção, todas as senhas devem estar hasheadas
            $senha_valida = ($senha === $usuario['senha_usuario']);
            
            // Opcional: Atualiza automaticamente para senha hasheada
            // Isso migra gradualmente as senhas antigas
            if ($senha_valida) {
                $novo_hash = password_hash($senha, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE usuarios SET senha_usuario = :senha WHERE id_usuario = :id");
                $update_stmt->execute([
                    'senha' => $novo_hash,
                    'id' => $usuario['id_usuario']
                ]);
            }
        }
    }
    
    // =====================================================
    // PROCESSA RESULTADO DA AUTENTICAÇÃO
    // =====================================================
    
    if ($usuario && $senha_valida) {
        
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
 * NOTAS DE SEGURANÇA E MODIFICAÇÕES
 * =====================================================
 * 
 * MODIFICAÇÕES REALIZADAS:
 * 
 * 1. Adicionada verificação com password_get_info() para detectar
 *    se a senha está hasheada ou em texto plano
 * 
 * 2. Se hasheada: usa password_verify() (comportamento original)
 * 
 * 3. Se texto plano: usa comparação direta === (compatibilidade)
 * 
 * 4. Atualização automática: quando um usuário com senha em texto
 *    plano faz login com sucesso, a senha é automaticamente
 *    convertida para hash (migração gradual)
 * 
 * SEGURANÇA:
 * 
 * 1. Esta solução é TEMPORÁRIA para compatibilidade
 * 
 * 2. Todas as senhas devem estar hasheadas em produção
 * 
 * 3. A atualização automática garante que, com o tempo,
 *    todas as senhas serão migradas para hash
 * 
 * 4. Prepared statements previnem SQL Injection
 * 
 * 5. session_regenerate_id() previne fixação de sessão
 * 
 * 6. Mensagens de erro genéricas não revelam informações
 * 
 * PRÓXIMOS PASSOS RECOMENDADOS:
 * 
 * 1. Execute um script de migração para hashear todas as senhas
 *    existentes no banco de dados
 * 
 * 2. Após a migração, remova o código de compatibilidade com
 *    texto plano (linhas 73-89)
 * 
 * 3. Implemente recursos adicionais de segurança:
 *    - Limite de tentativas de login
 *    - CAPTCHA após X tentativas falhas
 *    - Log de tentativas de login
 *    - Autenticação de dois fatores (2FA)
 */
?>
