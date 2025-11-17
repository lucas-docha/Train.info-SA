<?php
/**
 * =====================================================
 * CADASTRO DE SENSORES
 * =====================================================
 * Permite adicionar novos registros de sensores
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

// Protege a página
protegerPagina();

// =====================================================
// PROCESSAMENTO DO FORMULÁRIO
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $tipo_sensor = $_POST['tipo_sensor'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    
    // Validação básica
    if (empty($tipo_sensor)) {
        $erro = "Selecione o tipo de sensor!";
    } else {
        
        try {
            // Prepara SQL baseado no tipo de sensor
            if ($tipo_sensor == 'presenca') {
                $presenca = isset($_POST['presenca_detectada']) ? 1 : 0;
                $sql = "INSERT INTO sensores (tipo_sensor, presenca_detectada, descricao) 
                        VALUES (:tipo, :presenca, :descricao)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'tipo' => $tipo_sensor,
                    'presenca' => $presenca,
                    'descricao' => $descricao
                ]);
                
            } elseif ($tipo_sensor == 'umidade_temperatura') {
                $temperatura = $_POST['temperatura'] ?? 0;
                $umidade = $_POST['umidade'] ?? 0;
                $sql = "INSERT INTO sensores (tipo_sensor, temperatura, umidade, descricao) 
                        VALUES (:tipo, :temp, :umid, :descricao)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'tipo' => $tipo_sensor,
                    'temp' => $temperatura,
                    'umid' => $umidade,
                    'descricao' => $descricao
                ]);
                
            } elseif ($tipo_sensor == 'iluminacao') {
                $nivel = $_POST['nivel_iluminacao'] ?? 0;
                $sql = "INSERT INTO sensores (tipo_sensor, nivel_iluminacao, descricao) 
                        VALUES (:tipo, :nivel, :descricao)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'tipo' => $tipo_sensor,
                    'nivel' => $nivel,
                    'descricao' => $descricao
                ]);
            }
            
            $sucesso = "Sensor cadastrado com sucesso!";
            
        } catch(PDOException $e) {
            $erro = "Erro ao cadastrar sensor: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Sensor - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <!-- Cabeçalho -->
        <div class="header-dashboard">
            <h1>Cadastrar Sensor</h1>
            <a href="listar.php" class="botao botao-secundario">← Voltar</a>
        </div>

        <!-- Formulário -->
        <div class="card">
            
            <?php if (isset($sucesso)): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if (isset($erro)): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <form method="POST" id="formSensor">
                
                <!-- Tipo de sensor -->
                <div class="campo-label">
                    <label for="tipo_sensor">Tipo de Sensor *</label>
                </div>
                <div class="campo-input">
                    <select name="tipo_sensor" id="tipo_sensor" required onchange="mostrarCampos()">
                        <option value="">Selecione...</option>
                        <option value="presenca">Presença (Ultrassônico)</option>
                        <option value="umidade_temperatura">Umidade e Temperatura (DHT11)</option>
                        <option value="iluminacao">Iluminação (LDR)</option>
                    </select>
                </div>

                <!-- Campos específicos para cada tipo -->
                
                <!-- Presença -->
                <div id="campos_presenca" style="display: none;">
                    <div class="campo-label">
                        <label>
                            <input type="checkbox" name="presenca_detectada" value="1">
                            Objeto detectado
                        </label>
                    </div>
                </div>

                <!-- Umidade e Temperatura -->
                <div id="campos_umidade_temp" style="display: none;">
                    <div class="campo-label">
                        <label for="temperatura">Temperatura (°C) *</label>
                    </div>
                    <div class="campo-input">
                        <input type="number" step="0.01" name="temperatura" id="temperatura" 
                               placeholder="Ex: 25.5">
                    </div>

                    <div class="campo-label">
                        <label for="umidade">Umidade (%) *</label>
                    </div>
                    <div class="campo-input">
                        <input type="number" step="0.01" name="umidade" id="umidade" 
                               placeholder="Ex: 65.3">
                    </div>
                </div>

                <!-- Iluminação -->
                <div id="campos_iluminacao" style="display: none;">
                    <div class="campo-label">
                        <label for="nivel_iluminacao">Nível de Iluminação (0-255) *</label>
                    </div>
                    <div class="campo-input">
                        <input type="number" min="0" max="255" name="nivel_iluminacao" 
                               id="nivel_iluminacao" placeholder="Ex: 180">
                    </div>
                </div>

                <!-- Descrição (comum a todos) -->
                <div class="campo-label">
                    <label for="descricao">Descrição</label>
                </div>
                <div class="campo-input">
                    <textarea name="descricao" id="descricao" 
                              placeholder="Informações adicionais sobre esta leitura"></textarea>
                </div>
                <br>
                <!-- Botão -->
                <button type="submit" class="botao botao-primario botao-completo">
                    Cadastrar Sensor
                </button>

            </form>
        </div>

        <!-- Rodapé -->
        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>

    </div>

    <script>
        /**
         * Mostra campos específicos baseado no tipo de sensor selecionado
         */
        function mostrarCampos() {
            const tipo = document.getElementById('tipo_sensor').value;
            
            // Esconde todos os campos específicos
            document.getElementById('campos_presenca').style.display = 'none';
            document.getElementById('campos_umidade_temp').style.display = 'none';
            document.getElementById('campos_iluminacao').style.display = 'none';
            
            // Mostra apenas os campos do tipo selecionado
            if (tipo === 'presenca') {
                document.getElementById('campos_presenca').style.display = 'block';
            } else if (tipo === 'umidade_temperatura') {
                document.getElementById('campos_umidade_temp').style.display = 'block';
            } else if (tipo === 'iluminacao') {
                document.getElementById('campos_iluminacao').style.display = 'block';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
