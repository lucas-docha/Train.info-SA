<?php
/**
 * =====================================================
 * CADASTRO DE MANUTENÇÕES
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPagina();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status_manutencao'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_termino = $_POST['data_termino'] ?? null;
    $comentario = $_POST['comentario'] ?? '';
    
    if (empty($status) || empty($data_inicio)) {
        $erro = "Preencha os campos obrigatórios!";
    } else {
        try {
            $sql = "INSERT INTO manutencoes (status_manutencao, data_inicio, data_termino, comentario) 
                    VALUES (:status, :inicio, :termino, :comentario)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'status' => $status,
                'inicio' => $data_inicio,
                'termino' => $data_termino ?: null,
                'comentario' => $comentario
            ]);
            
            $sucesso = "Manutenção cadastrada com sucesso!";
            
        } catch(PDOException $e) {
            $erro = "Erro ao cadastrar manutenção.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Manutenção - Sistema de Gerenciamento de Trens</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <div class="header-dashboard">
            <h1>➕ Cadastrar Manutenção</h1>
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
                    <label for="status_manutencao">Status *</label>
                </div>
                <div class="campo-input">
                    <select name="status_manutencao" id="status_manutencao" required>
                        <option value="">Selecione...</option>
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluida">Concluída</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="campo-label">
                    <label for="data_inicio">Data de Início *</label>
                </div>
                <div class="campo-input">
                    <input type="date" name="data_inicio" id="data_inicio" required>
                </div>

                <div class="campo-label">
                    <label for="data_termino">Data de Término</label>
                </div>
                <div class="campo-input">
                    <input type="date" name="data_termino" id="data_termino">
                </div>
                
                <div class="campo-label">
                    <label for="comentario">Comentário</label>
                </div>
                <div class="campo-input">
                    <textarea name="comentario" id="comentario" 
                              placeholder="Descreva os detalhes da manutenção"></textarea>
                </div>
                <br>
                <button type="submit" class="botao botao-primario botao-completo">
                    Cadastrar Manutenção
                </button>

            </form>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>

    </div>
</body>
</html>
