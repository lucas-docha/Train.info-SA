<?php
/**
 * =====================================================
 * LISTAGEM DE TRENS
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

// Busca trens
try {
    $sql = "SELECT * FROM trens ORDER BY criado_em DESC";
    $stmt = $pdo->query($sql);
    $trens = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar trens.";
    $trens = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trens - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container" style="padding-top: 120px;">
        
        <div class="header-dashboard">
            <h1>Trens</h1>
            <?php if ($ehAdmin): ?>
                <div style="display: flex; gap: 10px;">
                    <a href="cadastrar.php" class="botao botao-primario">Novo Trem</a>
                    <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
                </div>
            <?php else: ?>
                <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Frota de Trens</h2>
            
            <?php if ($sucesso): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <?php if (empty($trens)): ?>
                <p style="text-align: center; color: #6ce5e8; padding: 20px;">
                    Nenhum trem cadastrado.
                </p>
            <?php else: ?>
                <div class="tabela-container">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Carga</th>
                                <th>Status</th>
                                <th>Data Cadastro</th>
                                <?php if ($ehAdmin): ?>
                                <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($trens as $trem): ?>
                            <tr>
                                <td><?= $trem['id_trem'] ?></td>
                                <td>
                                    <span class="badge <?= $trem['tipo_trem']?>">
                                        <?= ucfirst($trem['tipo_trem']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($trem['tipo_trem'] == 'carga' && $trem['carga_trem']): ?>
                                        <?= sanitizar($trem['carga_trem']) ?>
                                    <?php else: ?>
                                        <span style="color: #6ce5e8;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $badges = [
                                        'operante' => 'badge-concluida',
                                        'em_manutencao' => 'badge-pendente',
                                        'fora_de_servico' => 'badge-cancelada'
                                    ];
                                    $nomes = [
                                        'operante' => 'Operante',
                                        'em_manutencao' => 'Em Manutenção',
                                        'fora_de_servico' => 'Fora de Serviço'
                                    ];
                                    $classe = $badges[$trem['status_trem']] ?? '';
                                    ?>
                                    <span class="badge <?= $classe ?>">
                                        <?= $nomes[$trem['status_trem']] ?>
                                    </span>
                                </td>
                                <td><?= formatarTimestamp($trem['criado_em']) ?></td>
                                <?php if ($ehAdmin): ?>
                                <td>
                                    <div class="tabela-acoes">
                                        <a href="editar.php?id=<?= $trem['id_trem'] ?>" 
                                           class="btn-tabela btn-editar">Editar</a>
                                        <a href="excluir.php?id=<?= $trem['id_trem'] ?>" 
                                           class="btn-tabela btn-excluir"
                                           onclick="return confirm('Excluir este trem?')">Excluir</a>
                                    </div>
                                </td>
                                <?php endif; ?>
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