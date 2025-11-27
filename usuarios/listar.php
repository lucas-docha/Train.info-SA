<?php
/**
 * =====================================================
 * LISTAGEM DE USUÁRIOS (APENAS ADMIN)
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

// Protege para apenas administradores
protegerPaginaAdmin();

$sucesso = $_SESSION['sucesso'] ?? '';
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['sucesso'], $_SESSION['erro']);

try {
    $sql = "SELECT * FROM usuarios ORDER BY data_cadastro DESC";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar usuários.";
    $usuarios = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container" style="padding-top: 120px;">
        
        <div class="header-dashboard">
            <h1>Usuários</h1>
            <div style="display: flex; gap: 10px;">
                <a href="cadastrar.php" class="botao botao-primario">Novo Usuário</a>
                <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
            </div>
        </div>

        <div class="card">
            <h2>Usuários Cadastrados</h2>
            
            <?php if ($sucesso): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <div class="tabela-container">
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Tipo</th>
                            <th>Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $user): ?>
                        <tr>
                            <td><?= $user['id_usuario'] ?></td>
                            <td><?= sanitizar($user['nome_usuario']) ?></td>
                            <td><?= sanitizar($user['email_usuario']) ?></td>
                            <td><?= formatarCPF($user['cpf_usuario']) ?></td>
                            <td>
                                <span class="badge <?= $user['tipo_usuario'] == 'admin' ? 'badge-admin' : 'badge-usuario' ?>">
                                    <?= ucfirst($user['tipo_usuario']) ?>
                                </span>
                            </td>
                            <td><?= formatarTimestamp($user['data_cadastro']) ?></td>
                            <td>
                                <div class="tabela-acoes">
                                    <a href="editar.php?id=<?= $user['id_usuario'] ?>" 
                                       class="btn-tabela btn-editar">Editar</a>
                                    <a href="excluir.php?id=<?= $user['id_usuario'] ?>" 
                                       class="btn-tabela btn-excluir"
                                       onclick="return confirm('Excluir este usuário?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
