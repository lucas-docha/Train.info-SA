<?php
/**
 * =====================================================
 * LISTAGEM DE MANUTENÇÕES
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPagina();

// Recupera mensagens
$sucesso = $_SESSION['sucesso'] ?? '';
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['sucesso'], $_SESSION['erro']);

// Recupera dados do usuário logado
$usuario = dadosUsuario();
$nomeExibicao = nomeExibicao();
$tipoUsuario = tipoUsuarioExibicao();
$ehAdmin = ehAdmin();


// Busca manutenções
try {
    $sql = "SELECT * FROM manutencoes ORDER BY criado_em DESC";
    $stmt = $pdo->query($sql);
    $manutencoes = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar manutenções.";
    $manutencoes = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manutenções - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container" style="padding-top: 120px;">
        
        <div class="header-dashboard">
            <h1>Manutenções</h1>
            <?php if ($ehAdmin): ?>
                <div style="display: flex; gap: 10px;">
                    <a href="cadastrar.php" class="botao botao-primario">Nova Manutenção</a>
                    <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Registro de Manutenções</h2>
            
            <?php if ($sucesso): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <?php if (empty($manutencoes)): ?>
                <p style="text-align: center; color: #6ce5e8; padding: 20px;">
                    Nenhuma manutenção cadastrada.
                </p>
            <?php else: ?>
                <div class="tabela-container">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Data Início</th>
                                <th>Data Término</th>
                                <th>Comentário</th>
                                <?php if ($ehAdmin): ?>
                                <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($manutencoes as $man): ?>
                            <tr>
                                <td><?= $man['id_manutencao'] ?></td>
                                <td>
                                    <?php
                                    $badges = [
                                        'pendente' => 'badge-pendente',
                                        'em_andamento' => 'badge-andamento',
                                        'concluida' => 'badge-concluida',
                                        'cancelada' => 'badge-cancelada'
                                    ];
                                    $classe = $badges[$man['status_manutencao']] ?? '';
                                    ?>
                                    <span class="badge <?= $classe ?>">
                                        <?= ucfirst(str_replace('_', ' ', $man['status_manutencao'])) ?>
                                    </span>
                                </td>
                                <td><?= formatarData($man['data_inicio']) ?></td>
                                <td><?= formatarData($man['data_termino']) ?></td>
                                <td><?= sanitizar(substr($man['comentario'], 0, 50)) ?><?= strlen($man['comentario']) > 50 ? '...' : '' ?></td>
                                <td>
                                    <?php if ($ehAdmin): ?>
                                    <div class="tabela-acoes">
                                        <a href="editar.php?id=<?= $man['id_manutencao'] ?>" 
                                           class="btn-tabela btn-editar">Editar</a>
                                        <a href="excluir.php?id=<?= $man['id_manutencao'] ?>" 
                                           class="btn-tabela btn-excluir"
                                           onclick="return confirm('Excluir esta manutenção?')">Excluir</a>
                                    </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
