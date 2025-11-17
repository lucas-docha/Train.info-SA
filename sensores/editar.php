<?php
/**
 * =====================================================
 * EDIÇÃO DE SENSORES
 * =====================================================
 * Permite editar registros de sensores existentes
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPagina();

$id = $_GET['id'] ?? 0;

// Busca sensor
try {
    $stmt = $pdo->prepare("SELECT * FROM sensores WHERE id_sensor = :id");
    $stmt->execute(['id' => $id]);
    $sensor = $stmt->fetch();
    
    if (!$sensor) {
        header("Location: listar.php");
        exit;
    }
} catch(PDOException $e) {
    $erro = "Erro ao buscar sensor.";
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'] ?? '';
    
    try {
        $tipo = $sensor['tipo_sensor'];
        
        if ($tipo == 'presenca') {
            $presenca = isset($_POST['presenca_detectada']) ? 1 : 0;
            $sql = "UPDATE sensores SET presenca_detectada = :presenca, descricao = :descricao 
                    WHERE id_sensor = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['presenca' => $presenca, 'descricao' => $descricao, 'id' => $id]);
            
        } elseif ($tipo == 'umidade_temperatura') {
            $temp = $_POST['temperatura'] ?? 0;
            $umid = $_POST['umidade'] ?? 0;
            $sql = "UPDATE sensores SET temperatura = :temp, umidade = :umid, descricao = :descricao 
                    WHERE id_sensor = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['temp' => $temp, 'umid' => $umid, 'descricao' => $descricao, 'id' => $id]);
            
        } elseif ($tipo == 'iluminacao') {
            $nivel = $_POST['nivel_iluminacao'] ?? 0;
            $sql = "UPDATE sensores SET nivel_iluminacao = :nivel, descricao = :descricao 
                    WHERE id_sensor = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nivel' => $nivel, 'descricao' => $descricao, 'id' => $id]);
        }
        
        $sucesso = "Sensor atualizado com sucesso!";
        
        // Recarrega dados
        $stmt = $pdo->prepare("SELECT * FROM sensores WHERE id_sensor = :id");
        $stmt->execute(['id' => $id]);
        $sensor = $stmt->fetch();
        
    } catch(PDOException $e) {
        $erro = "Erro ao atualizar sensor.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sensor - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        <div class="header-dashboard">
            <h1>Editar Sensor #<?= $id ?></h1>
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
                    <label>Tipo de Sensor</label>
                </div>
                <div class="campo-input">
                    <input type="text" value="<?= sanitizar($sensor['tipo_sensor']) ?>" disabled>
                </div>

                <?php if ($sensor['tipo_sensor'] == 'presenca'): ?>
                    <div class="campo-label">
                        <label>
                            <input type="checkbox" name="presenca_detectada" value="1" 
                                   <?= $sensor['presenca_detectada'] ? 'checked' : '' ?>>
                            Objeto detectado
                        </label>
                    </div>
                <?php endif; ?>

                <?php if ($sensor['tipo_sensor'] == 'umidade_temperatura'): ?>
                    <div class="campo-label">
                        <label for="temperatura">Temperatura (°C)</label>
                    </div>
                    <div class="campo-input">
                        <input type="number" step="0.01" name="temperatura" id="temperatura" 
                               value="<?= $sensor['temperatura'] ?>">
                    </div>

                    <div class="campo-label">
                        <label for="umidade">Umidade (%)</label>
                    </div>
                    <div class="campo-input">
                        <input type="number" step="0.01" name="umidade" id="umidade" 
                               value="<?= $sensor['umidade'] ?>">
                    </div>
                <?php endif; ?>

                <?php if ($sensor['tipo_sensor'] == 'iluminacao'): ?>
                    <div class="campo-label">
                        <label for="nivel_iluminacao">Nível de Iluminação (0-255)</label>
                    </div>
                    <div class="campo-input">
                        <input type="number" min="0" max="255" name="nivel_iluminacao" 
                               value="<?= $sensor['nivel_iluminacao'] ?>">
                    </div>
                <?php endif; ?>

                <div class="campo-label">
                    <label for="descricao">Descrição</label>
                </div>
                <div class="campo-input">
                    <textarea name="descricao" id="descricao"><?= sanitizar($sensor['descricao']) ?></textarea>
                </div>
                <br>
                <button type="submit" class="botao botao-primario botao-completo">
                    Atualizar Sensor
                </button>

            </form>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
