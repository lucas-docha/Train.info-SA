<?php
/**
 * =====================================================
 * CADASTRO DE USUÁRIOS (APENAS ADMIN)
 * =====================================================
 * Apenas administradores podem cadastrar novos usuários
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPaginaAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_usuario'] ?? '';
    $email = $_POST['email_usuario'] ?? '';
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf_usuario'] ?? '');
    $senha = $_POST['senha_usuario'] ?? '';
    $tipo = $_POST['tipo_usuario'] ?? 'usuario';
    
    $erros = [];
    
    // Validações
    if (strlen($nome) < 3) $erros[] = "Nome deve ter pelo menos 3 caracteres";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "Email inválido";
    if (strlen($cpf) != 11) $erros[] = "CPF deve ter 11 dígitos";
    if (strlen($senha) < 6) $erros[] = "Senha deve ter pelo menos 6 caracteres";
    
    // Verifica duplicados
    if (empty($erros)) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE email_usuario = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()['total'] > 0) $erros[] = "Email já cadastrado";
            
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE cpf_usuario = :cpf");
            $stmt->execute(['cpf' => $cpf]);
            if ($stmt->fetch()['total'] > 0) $erros[] = "CPF já cadastrado";
            
        } catch(PDOException $e) {
            $erros[] = "Erro ao verificar dados";
        }
    }
    
    // Cadastra se não houver erros
    if (empty($erros)) {
        try {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios (nome_usuario, email_usuario, cpf_usuario, senha_usuario, tipo_usuario) 
                    VALUES (:nome, :email, :cpf, :senha, :tipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'cpf' => $cpf,
                'senha' => $senha_hash,
                'tipo' => $tipo
            ]);
            
            $sucesso = "Usuário cadastrado com sucesso!";
            
        } catch(PDOException $e) {
            $erros[] = "Erro ao cadastrar usuário";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <div class="header-dashboard">
            <h1>➕ Cadastrar Usuário</h1>
            <a href="listar.php" class="botao botao-secundario">← Voltar</a>
        </div>

        <div class="card">
            
            <?php if (isset($sucesso)): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if (!empty($erros)): ?>
                <div class="mensagem mensagem-erro">
                    <?php foreach($erros as $erro): ?>
                        <?= $erro ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                
                <div class="campo-label">
                    <label for="nome_usuario">Nome Completo *</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="nome_usuario" id="nome_usuario" 
                           placeholder="Nome completo do usuário" required>
                </div>

                <div class="campo-label">
                    <label for="email_usuario">Email *</label>
                </div>
                <div class="campo-input">
                    <input type="email" name="email_usuario" id="email_usuario" 
                           placeholder="email@exemplo.com" required>
                </div>

                <div class="campo-label">
                    <label for="cpf_usuario">CPF *</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="cpf_usuario" id="cpf_usuario" 
                           placeholder="000.000.000-00" maxlength="14" required>
                </div>

                <div class="campo-label">
                    <label for="senha_usuario">Senha * (mínimo 6 caracteres)</label>
                </div>
                <div class="campo-input">
                    <input type="password" name="senha_usuario" id="senha_usuario" 
                           placeholder="Senha de acesso" minlength="6" required>
                </div>

                <div class="campo-label">
                    <label for="tipo_usuario">Tipo de Usuário *</label>
                </div>
                <div class="campo-input">
                    <select name="tipo_usuario" id="tipo_usuario" required>
                        <option value="usuario">Usuário Comum</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="botao botao-primario botao-completo">
                    Cadastrar Usuário
                </button>

            </form>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>

    </div>

    <script>
        // Máscara de CPF
        document.getElementById('cpf_usuario').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
