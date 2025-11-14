<?php
require_once '../verificar_sessao.php';
require_once '../config.php';
protegerPagina();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saida = $_POST['local_saida'] ?? '';
    $destino = $_POST['local_destino'] ?? '';
    $hora_saida = $_POST['horario_saida'] ?? '';
    $hora_chegada = $_POST['horario_chegada'] ?? '';
    
    if (empty($saida) || empty($destino) || empty($hora_saida) || empty($hora_chegada)) {
        $erro = "Preencha todos os campos!";
    } else {
        try {
            $sql = "INSERT INTO rotas (local_saida, local_destino, horario_saida, horario_chegada) 
                    VALUES (:saida, :destino, :hora_saida, :hora_chegada)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'saida' => $saida,
                'destino' => $destino,
                'hora_saida' => $hora_saida,
                'hora_chegada' => $hora_chegada
            ]);
            
            $sucesso = "Rota cadastrada com sucesso!";
            
        } catch(PDOException $e) {
            $erro = "Erro ao cadastrar rota.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Rota</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        
        <div class="header-dashboard">
            <h1>Cadastrar Rota</h1>
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
                    <label for="local_saida">Local de Saída *</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="local_saida" id="local_saida" 
                           placeholder="Ex: Estação Central" required>
                </div>

                <div class="campo-label">
                    <label for="local_destino">Local de Destino *</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="local_destino" id="local_destino" 
                           placeholder="Ex: Estação Norte" required>
                </div>

                <div class="campo-label">
                    <label for="horario_saida">Horário de Saída *</label>
                </div>
                <div class="campo-input">
                    <input type="time" name="horario_saida" id="horario_saida" required>
                </div>

                <div class="campo-label">
                    <label for="horario_chegada">Horário de Chegada *</label>
                </div>
                <div class="campo-input">
                    <input type="time" name="horario_chegada" id="horario_chegada" required>
                </div>

                <button type="submit" class="botao botao-primario botao-completo">
                    Cadastrar Rota
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
