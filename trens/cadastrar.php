<?php
/**
 * =====================================================
 * CADASTRO DE TRENS
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPaginaAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo_trem'] ?? '';
    $carga = $_POST['carga_trem'] ?? null;
    $status = $_POST['status_trem'] ?? '';
    
    if (empty($tipo) || empty($status)) {
        $erro = "Preencha os campos obrigatórios!";
    } else {
        // Se for trem de transporte, a carga deve ser NULL
        if ($tipo === 'transporte') {
            $carga = null;
        }
        
        // Se for trem de carga mas não informou a carga
        if ($tipo === 'carga' && empty($carga)) {
            $erro = "Informe o tipo de carga para trens de carga!";
        } else {
            try {
                $sql = "INSERT INTO trens (tipo_trem, carga_trem, status_trem) 
                        VALUES (:tipo, :carga, :status)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'tipo' => $tipo,
                    'carga' => $carga,
                    'status' => $status
                ]);
                
                $sucesso = "Trem cadastrado com sucesso!";
                
            } catch(PDOException $e) {
                $erro = "Erro ao cadastrar trem.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Trem - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <div class="header-dashboard">
            <h1>Cadastrar Trem</h1>
            <a href="listar.php" class="botao botao-secundario">← Voltar</a>
        </div>

        <div class="card">
            
            <?php if (isset($sucesso)): ?>
                <div class="mensagem mensagem-sucesso"><?= $sucesso ?></div>
            <?php endif; ?>
            
            <?php if (isset($erro)): ?>
                <div class="mensagem mensagem-erro"><?= $erro ?></div>
            <?php endif; ?>

            <form method="POST" id="formTrem">
                
                <div class="campo-label">
                    <label for="tipo_trem">Tipo de Trem *</label>
                </div>
                <div class="campo-input">
                    <select name="tipo_trem" id="tipo_trem" required onchange="mostrarCampoCarga()">
                        <option value="">Selecione...</option>
                        <option value="transporte">Transporte</option>
                        <option value="carga">Carga</option>
                    </select>
                </div>

                <div id="campo_carga" style="display: none;">
                    <div class="campo-label">
                        <label for="carga_trem">Tipo de Carga *</label>
                    </div>
                    <div class="campo-input">
                        <input type="text" name="carga_trem" id="carga_trem" 
                               placeholder="Ex: Minério de ferro, Grãos, Combustível">
                    </div>
                </div>

                <div class="campo-label">
                    <label for="status_trem">Status do Trem *</label>
                </div>
                <div class="campo-input">
                    <select name="status_trem" id="status_trem" required>
                        <option value="">Selecione...</option>
                        <option value="operante">Operante</option>
                        <option value="em_manutencao">Em Manutenção</option>
                        <option value="fora_de_servico">Fora de Serviço</option>
                    </select>
                </div>

                <button type="submit" class="botao botao-primario botao-completo">
                    Cadastrar Trem
                </button>

            </form>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>

    </div>

    <script>
        /**
         * Mostra/esconde campo de carga baseado no tipo de trem
         */
        function mostrarCampoCarga() {
            const tipo = document.getElementById('tipo_trem').value;
            const campoCarga = document.getElementById('campo_carga');
            const inputCarga = document.getElementById('carga_trem');
            
            if (tipo === 'carga') {
                campoCarga.style.display = 'block';
                inputCarga.required = true;
            } else {
                campoCarga.style.display = 'none';
                inputCarga.required = false;
                inputCarga.value = '';
            }
        }

        // Validação adicional no submit
        document.getElementById('formTrem').addEventListener('submit', function(e) {
            const tipo = document.getElementById('tipo_trem').value;
            const carga = document.getElementById('carga_trem').value;
            
            if (tipo === 'carga' && !carga.trim()) {
                e.preventDefault();
                alert('Por favor, informe o tipo de carga para trens de carga!');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>