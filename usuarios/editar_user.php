<?php
require_once '../verificar_sessao.php';
require_once '../config.php';
protegerPaginaAdmin();

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
    $stmt->execute(['id' => $id]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        header("Location: listar.php");
        exit;
    }
} catch(PDOException $e) {
    $erro = "Erro ao buscar usuário.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_usuario'] ?? '';
    $email = $_POST['email_usuario'] ?? '';
    $tipo = $_POST['tipo_usuario'] ?? 'usuario';
    $status = $_POST['status_usuario'] ?? 'ativo';
    $nova_senha = $_POST['nova_senha'] ?? '';
    
    try {
        if (!empty($nova_senha)) {
            // Atualiza com nova senha
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nome_usuario = :nome, email_usuario = :email, 
                    tipo_usuario = :tipo, status_usuario = :status, senha_usuario = :senha WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'tipo' => $tipo,
                'status' => $status,
                'senha' => $senha_hash,
                'id' => $id
            ]);
        } else {
            // Atualiza sem alterar senha
            $sql = "UPDATE usuarios SET nome_usuario = :nome, email_usuario = :email, 
                    tipo_usuario = :tipo, status_usuario = :status WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'tipo' => $tipo,
                'status' => $status,
                'id' => $id
            ]);
        }
        
        $sucesso = "Usuário atualizado!";
        
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch();
        
    } catch(PDOException $e) {
        $erro = "Erro ao atualizar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="header-dashboard">
            <h1>Editar Usuário #<?= $id ?></h1>
            <a href="listar.php" class="botao botao-secundario">← Voltar</a>
        </div>

        <div class="card">
            <?php if (isset($sucesso)): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if (isset($erro)): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="campo-label">
                    <label for="nome_usuario">Nome Completo</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="nome_usuario" value="<?= sanitizar($usuario['nome_usuario']) ?>" required>
                </div>

                <div class="campo-label">
                    <label for="email_usuario">Email</label>
                </div>
                <div class="campo-input">
                    <input type="email" name="email_usuario" value="<?= sanitizar($usuario['email_usuario']) ?>" required>
                </div>

                <div class="campo-label">
                    <label>CPF (não editável)</label>
                </div>
                <div class="campo-input">
                    <input type="text" value="<?= formatarCPF($usuario['cpf_usuario']) ?>" disabled>
                </div>

                <div class="campo-label">
                    <label for="tipo_usuario">Tipo de Usuário</label>
                </div>
                <div class="campo-input">
	                    <select name="tipo_usuario" required>
	                        <option value="usuario" <?= $usuario['tipo_usuario'] == 'usuario' ? 'selected' : '' ?>>Usuário Comum</option>
	                        <option value="admin" <?= $usuario['tipo_usuario'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
	                    </select>
	                </div>

	                <div class="campo-label">
	                    <label for="status_usuario">Status do Usuário</label>
	                </div>
	                <div class="campo-input">
	                    <select name="status_usuario" required>
	                        <option value="ativo" <?= $usuario['status_usuario'] == 'ativo' ? 'selected' : '' ?>>Ativo</option>
	                        <option value="inativo" <?= $usuario['status_usuario'] == 'inativo' ? 'selected' : '' ?>>Não Ativo</option>
	                    </select>
	                </div>

                <div class="campo-label">
                    <label for="nova_senha">Nova Senha (deixe em branco para não alterar)</label>
                </div>
                <div class="campo-input">
                    <input type="password" name="nova_senha" placeholder="Digite apenas se quiser alterar">
                </div>
                <br>
                <button type="submit" class="botao botao-primario botao-completo">Atualizar</button>
            </form>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
