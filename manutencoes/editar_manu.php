<?php
require_once '../verificar_sessao.php';
require_once '../config.php';
protegerPagina();

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM manutencoes WHERE id_manutencao = :id");
    $stmt->execute(['id' => $id]);
    $manutencao = $stmt->fetch();
    
    if (!$manutencao) {
        header("Location: listar.php");
        exit;
    }
} catch(PDOException $e) {
    $erro = "Erro ao buscar manutenção.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status_manutencao'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_termino = $_POST['data_termino'] ?? null;
    $comentario = $_POST['comentario'] ?? '';
    
    try {
        $sql = "UPDATE manutencoes SET status_manutencao = :status, data_inicio = :inicio, 
                data_termino = :termino, comentario = :comentario WHERE id_manutencao = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'status' => $status,
            'inicio' => $data_inicio,
            'termino' => $data_termino ?: null,
            'comentario' => $comentario,
            'id' => $id
        ]);
        
        $sucesso = "Manutenção atualizada!";
        
        $stmt = $pdo->prepare("SELECT * FROM manutencoes WHERE id_manutencao = :id");
        $stmt->execute(['id' => $id]);
        $manutencao = $stmt->fetch();
        
    } catch(PDOException $e) {
        $erro = "Erro ao atualizar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Manutenção</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="header-dashboard">
            <h1>Editar Manutenção #<?= $id ?></h1>
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
                    <label for="status_manutencao">Status</label>
                </div>
                <div class="campo-input">
                    <select name="status_manutencao" id="status_manutencao" required>
                        <option value="pendente" <?= $manutencao['status_manutencao'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                        <option value="em_andamento" <?= $manutencao['status_manutencao'] == 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                        <option value="concluida" <?= $manutencao['status_manutencao'] == 'concluida' ? 'selected' : '' ?>>Concluída</option>
                        <option value="cancelada" <?= $manutencao['status_manutencao'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                </div>

                <div class="campo-label">
                    <label for="data_inicio">Data de Início</label>
                </div>
                <div class="campo-input">
                    <input type="date" name="data_inicio" value="<?= $manutencao['data_inicio'] ?>" required>
                </div>

                <div class="campo-label">
                    <label for="data_termino">Data de Término</label>
                </div>
                <div class="campo-input">
                    <input type="date" name="data_termino" value="<?= $manutencao['data_termino'] ?>">
                </div>

                <div class="campo-label">
                    <label for="comentario">Comentário</label>
                </div>
                <div class="campo-input">
                    <textarea name="comentario"><?= sanitizar($manutencao['comentario']) ?></textarea>
                </div>
                <br>
                <button type="submit" class="botao botao-primario botao-completo">Atualizar</button>
            </form>
        </div>

        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
