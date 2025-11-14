<?php
/**
 * =====================================================
 * TELA DE RELATÓRIOS CONSOLIDADOS
 * =====================================================
 * Exibe todas as informações do sistema em um único relatório
 * Inclui: sensores, manutenções e rotas
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPagina();

// =====================================================
// BUSCA DADOS DE SENSORES
// =====================================================
try {
    $sql = "SELECT * FROM sensores ORDER BY timestamp_leitura DESC LIMIT 10";
    $stmt = $pdo->query($sql);
    $sensores = $stmt->fetchAll();
} catch(PDOException $e) {
    $sensores = [];
}

// =====================================================
// BUSCA DADOS DE MANUTENÇÕES
// =====================================================
try {
    $sql = "SELECT * FROM manutencoes ORDER BY criado_em DESC LIMIT 10";
    $stmt = $pdo->query($sql);
    $manutencoes = $stmt->fetchAll();
} catch(PDOException $e) {
    $manutencoes = [];
}

// =====================================================
// BUSCA DADOS DE ROTAS
// =====================================================
try {
    $sql = "SELECT * FROM rotas ORDER BY criado_em DESC LIMIT 10";
    $stmt = $pdo->query($sql);
    $rotas = $stmt->fetchAll();
} catch(PDOException $e) {
    $rotas = [];
}

// =====================================================
// ESTATÍSTICAS GERAIS
// =====================================================
try {
    // Total de sensores
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sensores");
    $totalSensores = $stmt->fetch()['total'];
    
    // Manutenções por status
    $stmt = $pdo->query("SELECT status_manutencao, COUNT(*) as total FROM manutencoes GROUP BY status_manutencao");
    $statusManutencoes = $stmt->fetchAll();
    
    // Total de rotas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM rotas");
    $totalRotas = $stmt->fetch()['total'];
    
} catch(PDOException $e) {
    $totalSensores = 0;
    $statusManutencoes = [];
    $totalRotas = 0;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema de Gerenciamento de Trens</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        /* Estilos específicos para impressão */
        @media print {
            .header-dashboard, .botao, .rodape {
                display: none;
            }
            .card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        
        <!-- Cabeçalho -->
        <div class="header-dashboard">
            <h1>Relatórios Consolidados</h1>
            <div style="display: flex; gap: 10px;">
                <button onclick="window.print()" class="botao botao-primario">🖨️ Imprimir</button>
                <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
            </div>
        </div>

        <!-- =====================================================
             ESTATÍSTICAS GERAIS
             ===================================================== -->
        <div class="card">
            <h2>Estatísticas Gerais</h2>
            <div class="cards-container">
                
                <div style="background-color: #2e3356; padding: 15px; border-radius: 8px;">
                    <h3 style="color: #6ce5e8; margin: 0;">Total de Sensores</h3>
                    <p style="font-size: 32px; margin: 10px 0; color: #41b8d5;"><?= $totalSensores ?></p>
                </div>

                <div style="background-color: #2e3356; padding: 15px; border-radius: 8px;">
                    <h3 style="color: #6ce5e8; margin: 0;">Total de Rotas</h3>
                    <p style="font-size: 32px; margin: 10px 0; color: #41b8d5;"><?= $totalRotas ?></p>
                </div>

                <div style="background-color: #2e3356; padding: 15px; border-radius: 8px;">
                    <h3 style="color: #6ce5e8; margin: 0;">Manutenções</h3>
                    <?php foreach($statusManutencoes as $status): ?>
                        <p style="margin: 5px 0;">
                            <span class="badge badge-<?= $status['status_manutencao'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $status['status_manutencao'])) ?>: <?= $status['total'] ?>
                            </span>
                        </p>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <!-- =====================================================
             RELATÓRIO DE SENSORES
             ===================================================== -->
        <div class="card">
            <h2>Últimas Leituras de Sensores</h2>
            
            <?php if (empty($sensores)): ?>
                <p style="text-align: center; color: #6ce5e8;">Nenhum sensor registrado.</p>
            <?php else: ?>
                <div class="tabela-container">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Dados</th>
                                <th>Descrição</th>
                                <th>Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($sensores as $sensor): ?>
                            <tr>
                                <td><?= $sensor['id_sensor'] ?></td>
                                <td>
                                    <?php
                                    $tipos = [
                                        'presenca' => 'Presença',
                                        'umidade_temperatura' => 'Umidade/Temp',
                                        'iluminacao' => 'Iluminação'
                                    ];
                                    echo $tipos[$sensor['tipo_sensor']] ?? $sensor['tipo_sensor'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($sensor['tipo_sensor'] == 'presenca') {
                                        echo $sensor['presenca_detectada'] ? 
                                            '<span style="color: #44ff44;">✓ Detectado</span>' : 
                                            '<span style="color: #ff4444;">✗ Não detectado</span>';
                                    } elseif ($sensor['tipo_sensor'] == 'umidade_temperatura') {
                                        echo "" . $sensor['temperatura'] . "°C | ";
                                        echo "" . $sensor['umidade'] . "%";
                                    } elseif ($sensor['tipo_sensor'] == 'iluminacao') {
                                        echo "" . $sensor['nivel_iluminacao'] . "/255";
                                    }
                                    ?>
                                </td>
                                <td><?= sanitizar($sensor['descricao'] ?? '-') ?></td>
                                <td><?= formatarTimestamp($sensor['timestamp_leitura']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- =====================================================
             RELATÓRIO DE MANUTENÇÕES
             ===================================================== -->
        <div class="card">
            <h2>🔧 Últimas Manutenções</h2>
            
            <?php if (empty($manutencoes)): ?>
                <p style="text-align: center; color: #6ce5e8;">Nenhuma manutenção registrada.</p>
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
                                <td><?= sanitizar(substr($man['comentario'], 0, 80)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- =====================================================
             RELATÓRIO DE ROTAS
             ===================================================== -->
        <div class="card">
            <h2>Rotas Cadastradas</h2>
            
            <?php if (empty($rotas)): ?>
                <p style="text-align: center; color: #6ce5e8;">Nenhuma rota registrada.</p>
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
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Rodapé -->
        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
            <p>Relatório gerado em: <?= date('d/m/Y H:i:s') ?></p>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
