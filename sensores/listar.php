<?php
/**
 * =====================================================
 * LISTAGEM DE SENSORES
 * =====================================================
 * Exibe todos os registros de sensores cadastrados
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

// Protege a página
protegerPagina();

// =====================================================
// BUSCA SENSORES NO BANCO
// =====================================================
try {
    $sql = "SELECT * FROM sensores ORDER BY timestamp_leitura DESC";
    $stmt = $pdo->query($sql);
    $sensores = $stmt->fetchAll();
} catch(PDOException $e) {
    $erro = "Erro ao buscar sensores: " . $e->getMessage();
    $sensores = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensores - Sistema de Gerenciamento de Trens</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <!-- Cabeçalho -->
        <div class="header-dashboard">
            <h1>📡 Sensores</h1>
            <div style="display: flex; gap: 10px;">
                <a href="cadastrar.php" class="botao botao-sucesso">➕ Novo Sensor</a>
                <a href="../dashboard.php" class="botao botao-secundario">← Voltar</a>
            </div>
        </div>

        <!-- Tabela de sensores -->
        <div class="card">
            <h2>Leituras dos Sensores</h2>
            
            <?php if (isset($erro)): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <?php if (empty($sensores)): ?>
                <p style="text-align: center; color: #6ce5e8; padding: 20px;">
                    Nenhum sensor cadastrado ainda.
                </p>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($sensores as $sensor): ?>
                            <tr>
                                <td><?= $sensor['id_sensor'] ?></td>
                                <td>
                                    <?php
                                    // Exibe tipo do sensor formatado
                                    $tipos = [
                                        'presenca' => '👁️ Presença',
                                        'umidade_temperatura' => '🌡️ Umidade/Temp',
                                        'iluminacao' => '💡 Iluminação'
                                    ];
                                    echo $tipos[$sensor['tipo_sensor']] ?? $sensor['tipo_sensor'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Exibe dados específicos de cada tipo
                                    if ($sensor['tipo_sensor'] == 'presenca') {
                                        echo $sensor['presenca_detectada'] ? 
                                            '<span style="color: #44ff44;">✓ Detectado</span>' : 
                                            '<span style="color: #ff4444;">✗ Não detectado</span>';
                                    } elseif ($sensor['tipo_sensor'] == 'umidade_temperatura') {
                                        echo "🌡️ " . $sensor['temperatura'] . "°C<br>";
                                        echo "💧 " . $sensor['umidade'] . "%";
                                    } elseif ($sensor['tipo_sensor'] == 'iluminacao') {
                                        echo "💡 " . $sensor['nivel_iluminacao'] . "/255";
                                    }
                                    ?>
                                </td>
                                <td><?= sanitizar($sensor['descricao'] ?? '-') ?></td>
                                <td><?= formatarTimestamp($sensor['timestamp_leitura']) ?></td>
                                <td>
                                    <div class="tabela-acoes">
                                        <a href="editar.php?id=<?= $sensor['id_sensor'] ?>" 
                                           class="btn-tabela btn-editar">
                                            ✏️ Editar
                                        </a>
                                        <a href="excluir.php?id=<?= $sensor['id_sensor'] ?>" 
                                           class="btn-tabela btn-excluir"
                                           onclick="return confirm('Tem certeza que deseja excluir este sensor?')">
                                            🗑️ Excluir
                                        </a>
                                    </div>
                                </td>
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
        </div>

    </div>
</body>
</html>
