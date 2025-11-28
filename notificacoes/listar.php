<?php
/**
 * =====================================================
 * LISTAGEM DE NOTIFICAÇÕES E ALERTAS
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

// Busca alertas de sensores (últimas 10 leituras críticas)
try {
    // Alertas de temperatura alta (> 40°C)
    $sqlAlertas = "SELECT 'Temperatura Alta' as tipo_alerta, 
                   CONCAT('Sensor #', id_sensor, ' - ', temperatura, '°C') as mensagem,
                   timestamp_leitura as data_alerta
                   FROM sensores 
                   WHERE tipo_sensor = 'umidade_temperatura' 
                   AND temperatura > 40
                   ORDER BY timestamp_leitura DESC 
                   LIMIT 5";
    $stmtAlertas = $pdo->query($sqlAlertas);
    $alertasSensores = $stmtAlertas->fetchAll();
    
    // Alertas de umidade baixa (< 30%)
    $sqlUmidade = "SELECT 'Umidade Baixa' as tipo_alerta,
                   CONCAT('Sensor #', id_sensor, ' - ', umidade, '%') as mensagem,
                   timestamp_leitura as data_alerta
                   FROM sensores 
                   WHERE tipo_sensor = 'umidade_temperatura' 
                   AND umidade < 30
                   ORDER BY timestamp_leitura DESC 
                   LIMIT 5";
    $stmtUmidade = $pdo->query($sqlUmidade);
    $alertasUmidade = $stmtUmidade->fetchAll();
    
    // Combina alertas
    $alertas = array_merge($alertasSensores, $alertasUmidade);
    
} catch(PDOException $e) {
    $alertas = [];
}

// Busca notificações
try {
    $sql = "SELECT * FROM notificacoes ORDER BY 
            CASE gravidade 
                WHEN 'critica' THEN 1 
                WHEN 'alta' THEN 2 
                WHEN 'media' THEN 3 
                WHEN 'baixa' THEN 4 
            END, criado_em DESC";
    $stmt = $pdo->query($sql);
    $notificacoes = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar notificações.";
    $notificacoes = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações e Alertas - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        .alerta-card {
            background-color: #2e3356;
            border-left: 4px solid #ff4444;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .alerta-tipo {
            color: #ff4444;
            font-weight: 600;
            font-size: 14px;
        }
        .alerta-mensagem {
            color: white;
            margin: 5px 0;
        }
        .alerta-data {
            color: #6ce5e8;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container" style="padding-top: 120px;">
        
        <div class="header-dashboard">
            <h1>Notificações e Alertas</h1>
            <div style="display: flex; gap: 10px;">
                <?php if ($ehAdmin): ?>
                <a href="cadastrar.php" class="botao botao-primario">Nova Notificação</a>
                <?php endif; ?>
                <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
            </div>
        </div>

        <!-- Seção de Alertas de Sensores -->
        <div class="card">
            <h2>Alertas de Sensores</h2>
            
            <?php if (empty($alertas)): ?>
                <p style="text-align: center; color: #44ff44; padding: 20px;">
                    ✓ Nenhum alerta no momento. Todos os sensores operando normalmente.
                </p>
            <?php else: ?>
                <?php foreach($alertas as $alerta): ?>
                <div class="alerta-card">
                    <div class="alerta-tipo"><?= sanitizar($alerta['tipo_alerta']) ?></div>
                    <div class="alerta-mensagem"><?= sanitizar($alerta['mensagem']) ?></div>
                    <div class="alerta-data"><?= formatarTimestamp($alerta['data_alerta']) ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Seção de Notificações -->
        <div class="card">
            <h2>Notificações do Sistema</h2>
            
            <?php if ($sucesso): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <?php if (empty($notificacoes)): ?>
                <p style="text-align: center; color: #6ce5e8; padding: 20px;">
                    Nenhuma notificação cadastrada.
                </p>
            <?php else: ?>
                <div class="tabela-container">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Gravidade</th>
                                <th>Assunto</th>
                                <th>Descrição</th>
                                <th>Data</th>
                                <?php if ($ehAdmin): ?>
                                <th>Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($notificacoes as $notif): ?>
                            <tr>
                                <td><?= $notif['id_notificacao'] ?></td>
                                <td><?= sanitizar($notif['titulo_notificacao']) ?></td>
                                <td>
                                    <?php
                                    $badges = [
                                        'critica' => 'badge-cancelada',
                                        'alta' => 'badge-pendente',
                                        'media' => 'badge-andamento',
                                        'baixa' => 'badge-concluida'
                                    ];
                                    $nomes = [
                                        'critica' => 'Crítica',
                                        'alta' => 'Alta',
                                        'media' => 'Média',
                                        'baixa' => 'Baixa'
                                    ];
                                    $classe = $badges[$notif['gravidade']] ?? '';
                                    ?>
                                    <span class="badge <?= $classe ?>">
                                        <?= $nomes[$notif['gravidade']] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $assuntos = [
                                        'trens' => 'Trens',
                                        'sensores' => 'Sensores',
                                        'manutencao' => 'Manutenção',
                                        'rotas' => 'Rotas',
                                        'usuarios' => 'Usuários'
                                    ];
                                    echo $assuntos[$notif['assunto']] ?? ucfirst($notif['assunto']);
                                    ?>
                                </td>
                                <td><?= sanitizar(substr($notif['descricao_notificacao'], 0, 50)) ?><?= strlen($notif['descricao_notificacao']) > 50 ? '...' : '' ?></td>
                                <td><?= formatarTimestamp($notif['criado_em']) ?></td>
                                <?php if ($ehAdmin): ?>
                                <td>
                                    <div class="tabela-acoes">
                                        <a href="editar.php?id=<?= $notif['id_notificacao'] ?>" 
                                           class="btn-tabela btn-editar">Editar</a>
                                        <a href="excluir.php?id=<?= $notif['id_notificacao'] ?>" 
                                           class="btn-tabela btn-excluir"
                                           onclick="return confirm('Excluir esta notificação?')">Excluir</a>
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