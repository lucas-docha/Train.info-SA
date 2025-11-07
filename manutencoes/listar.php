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
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <div class="header-dashboard">
            <h1>🔧 Manutenções</h1>
            <div style="display: flex; gap: 10px;">
                <a href="cadastrar.php" class="botao botao-sucesso">➕ Nova Manutenção</a>
                <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
            </div>
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
                                <th>Ações</th>
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
                                    <div class="tabela-acoes">
                                        <a href="editar.php?id=<?= $man['id_manutencao'] ?>" 
                                           class="btn-tabela btn-editar">✏️ Editar</a>
                                        <a href="excluir.php?id=<?= $man['id_manutencao'] ?>" 
                                           class="btn-tabela btn-excluir"
                                           onclick="return confirm('Excluir esta manutenção?')">🗑️ Excluir</a>
                                    </div>
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
</body>
</html>
