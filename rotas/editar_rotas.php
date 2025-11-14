<?php
require_once '../verificar_sessao.php';
require_once '../config.php';
protegerPagina();

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM rotas WHERE id_rota = :id");
    $stmt->execute(['id' => $id]);
    $rota = $stmt->fetch();
    
    if (!$rota) {
        header("Location: listar.php");
        exit;
    }
} catch(PDOException $e) {
    $erro = "Erro ao buscar rota.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saida = $_POST['local_saida'] ?? '';
    $destino = $_POST['local_destino'] ?? '';
    $hora_saida = $_POST['horario_saida'] ?? '';
    $hora_chegada = $_POST['horario_chegada'] ?? '';
    
    try {
        $sql = "UPDATE rotas SET local_saida = :saida, local_destino = :destino, 
                horario_saida = :hora_saida, horario_chegada = :hora_chegada WHERE id_rota = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'saida' => $saida,
            'destino' => $destino,
            'hora_saida' => $hora_saida,
            'hora_chegada' => $hora_chegada,
            'id' => $id
        ]);
        
        $sucesso = "Rota atualizada!";
        
        $stmt = $pdo->prepare("SELECT * FROM rotas WHERE id_rota = :id");
        $stmt->execute(['id' => $id]);
        $rota = $stmt->fetch();
        
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
    <title>Editar Rota</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="header-dashboard">
            <h1>Editar Rota #<?= $id ?></h1>
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
                    <label for="local_saida">Local de Saída</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="local_saida" value="<?= sanitizar($rota['local_saida']) ?>" required>
                </div>

                <div class="campo-label">
                    <label for="local_destino">Local de Destino</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="local_destino" value="<?= sanitizar($rota['local_destino']) ?>" required>
                </div>

                <div class="campo-label">
                    <label for="horario_saida">Horário de Saída</label>
                </div>
                <div class="campo-input">
                    <input type="time" name="horario_saida" value="<?= $rota['horario_saida'] ?>" required>
                </div>

                <div class="campo-label">
                    <label for="horario_chegada">Horário de Chegada</label>
                </div>
                <div class="campo-input">
                    <input type="time" name="horario_chegada" value="<?= $rota['horario_chegada'] ?>" required>
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
