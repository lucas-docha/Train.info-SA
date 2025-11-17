<?php
/**
 * =====================================================
 * LISTAGEM DE ROTAS
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPagina();

$sucesso = $_SESSION['sucesso'] ?? '';
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['sucesso'], $_SESSION['erro']);

try {
    $sql = "SELECT * FROM rotas ORDER BY criado_em DESC";
    $stmt = $pdo->query($sql);
    $rotas = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar rotas.";
    $rotas = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <div class="header-dashboard">
            <h1>Rotas</h1>
            <div style="display: flex; gap: 10px;">
                <a href="cadastrar.php" class="botao botao-primario">Nova Rota</a>
                <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
            </div>
        </div>

        <div class="card">
            <h2>Rotas Cadastradas</h2>
            
            <?php if ($sucesso): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <?php if (empty($rotas)): ?>
                <p style="text-align: center; color: #6ce5e8; padding: 20px;">
                    Nenhuma rota cadastrada.
                </p>
            <?php else: ?>
                <div class="tabela-container">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Saída</th>
                                <th>Destino</th>
                                <th>Horário Saída</th>
                                <th>Horário Chegada</th>
                                <th>Duração</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rotas as $rota): ?>
                            <tr>
                                <td><?= $rota['id_rota'] ?></td>
                                <td><?= sanitizar($rota['local_saida']) ?></td>
                                <td><?= sanitizar($rota['local_destino']) ?></td>
                                <td><?= substr($rota['horario_saida'], 0, 5) ?></td>
                                <td><?= substr($rota['horario_chegada'], 0, 5) ?></td>
                                <td><?= calcularDuracao($rota['horario_saida'], $rota['horario_chegada']) ?></td>
                                <td>
                                    <div class="tabela-acoes">
                                        <a href="editar.php?id=<?= $rota['id_rota'] ?>" 
                                           class="btn-tabela btn-editar">Editar</a>
                                        <a href="excluir.php?id=<?= $rota['id_rota'] ?>" 
                                           class="btn-tabela btn-excluir"
                                           onclick="return confirm('Excluir esta rota?')">Excluir</a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
