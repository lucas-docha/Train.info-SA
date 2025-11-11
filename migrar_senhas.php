<?php
/**
 * =====================================================
 * SCRIPT DE MIGRAÇÃO DE SENHAS
 * =====================================================
 * Este script converte todas as senhas em texto plano
 * para senhas hasheadas com bcrypt
 * 
 * ATENÇÃO: Execute este script apenas UMA VEZ
 * Preferencialmente em ambiente de manutenção
 */

// Inclui configuração do banco
require_once 'config.php';

echo "==========================================\n";
echo "SCRIPT DE MIGRAÇÃO DE SENHAS\n";
echo "==========================================\n\n";

try {
    // =====================================================
    // BUSCA TODOS OS USUÁRIOS
    // =====================================================
    $sql = "SELECT id_usuario, nome_usuario, email_usuario, senha_usuario FROM usuarios";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll();
    
    $total_usuarios = count($usuarios);
    $senhas_migradas = 0;
    $senhas_ja_hasheadas = 0;
    
    echo "Total de usuários encontrados: $total_usuarios\n\n";
    
    // =====================================================
    // PROCESSA CADA USUÁRIO
    // =====================================================
    foreach ($usuarios as $usuario) {
        $id = $usuario['id_usuario'];
        $nome = $usuario['nome_usuario'];
        $email = $usuario['email_usuario'];
        $senha_atual = $usuario['senha_usuario'];
        
        // Verifica se a senha já está hasheada
        $info_senha = password_get_info($senha_atual);
        
        if ($info_senha['algo'] === null) {
            // =====================================================
            // SENHA EM TEXTO PLANO - PRECISA MIGRAR
            // =====================================================
            echo "Migrando: $nome ($email)\n";
            echo "  Senha atual (texto plano): $senha_atual\n";
            
            // Gera o hash da senha
            $senha_hasheada = password_hash($senha_atual, PASSWORD_DEFAULT);
            
            echo "  Novo hash: " . substr($senha_hasheada, 0, 30) . "...\n";
            
            // Atualiza no banco de dados
            $update_sql = "UPDATE usuarios SET senha_usuario = :senha WHERE id_usuario = :id";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([
                'senha' => $senha_hasheada,
                'id' => $id
            ]);
            
            echo "  ✓ Senha migrada com sucesso!\n\n";
            $senhas_migradas++;
            
        } else {
            // =====================================================
            // SENHA JÁ ESTÁ HASHEADA - PULA
            // =====================================================
            echo "Pulando: $nome ($email) - Senha já hasheada\n\n";
            $senhas_ja_hasheadas++;
        }
    }
    
    // =====================================================
    // RELATÓRIO FINAL
    // =====================================================
    echo "==========================================\n";
    echo "MIGRAÇÃO CONCLUÍDA\n";
    echo "==========================================\n";
    echo "Total de usuários: $total_usuarios\n";
    echo "Senhas migradas: $senhas_migradas\n";
    echo "Senhas já hasheadas: $senhas_ja_hasheadas\n";
    echo "==========================================\n\n";
    
    if ($senhas_migradas > 0) {
        echo "✓ Migração bem-sucedida!\n";
        echo "  Todas as senhas em texto plano foram convertidas para hash.\n";
        echo "  Os usuários podem fazer login normalmente com as mesmas senhas.\n\n";
    } else {
        echo "✓ Nenhuma migração necessária!\n";
        echo "  Todas as senhas já estavam hasheadas.\n\n";
    }
    
    echo "PRÓXIMOS PASSOS:\n";
    echo "1. Teste o login com os usuários migrados\n";
    echo "2. Após confirmar que tudo funciona, você pode remover\n";
    echo "   o código de compatibilidade com texto plano do login.php\n";
    echo "3. Delete este script de migração por segurança\n\n";
    
} catch(PDOException $e) {
    // =====================================================
    // ERRO NO BANCO DE DADOS
    // =====================================================
    echo "ERRO: Falha na migração\n";
    echo "Detalhes: " . $e->getMessage() . "\n";
    echo "\nNenhuma alteração foi realizada no banco de dados.\n";
    exit(1);
}

/**
 * =====================================================
 * INSTRUÇÕES DE USO
 * =====================================================
 * 
 * COMO EXECUTAR:
 * 
 * 1. Via linha de comando (recomendado):
 *    $ php migrar_senhas.php
 * 
 * 2. Via navegador (menos seguro):
 *    http://localhost/seu_projeto/migrar_senhas.php
 * 
 * IMPORTANTE:
 * 
 * 1. Faça backup do banco de dados antes de executar
 * 
 * 2. Execute em ambiente de desenvolvimento primeiro
 * 
 * 3. Este script pode ser executado múltiplas vezes
 *    (ele pula senhas já hasheadas)
 * 
 * 4. Após a migração, DELETE este arquivo por segurança
 * 
 * 5. Os usuários continuarão usando as mesmas senhas
 *    (apenas o formato de armazenamento muda)
 * 
 * SEGURANÇA:
 * 
 * 1. Este script mostra as senhas em texto plano no console
 *    apenas para fins de debug. Remova essas linhas em produção.
 * 
 * 2. Não deixe este script acessível via web em produção
 * 
 * 3. Execute com privilégios adequados
 */
?>
