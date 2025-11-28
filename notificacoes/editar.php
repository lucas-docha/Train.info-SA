<?php
/**
 * =====================================================
 * EDIÇÃO DE NOTIFICAÇÕES
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPaginaAdmin();

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM notificacoes WHERE id_notificacao = :id");
    $stmt->execute(['id' => $id]);
    $notificacao = $stmt->fetch();
    
    if (!$notificacao) {
        header("Location: listar.php");
        exit;
    }
} catch(PDOException $e) {
    $erro = "Erro ao buscar notificação.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo_notificacao'] ?? '';
    $gravidade = $_POST['gravidade'] ?? '';
    $descricao = $_POST['descricao_notificacao'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    
    if (empty($titulo) || empty($gravidade) || empty($descricao) || empty($assunto)) {
        $erro = "Preencha todos os campos obrigatórios!";
    } else {
        try {
            $sql = "UPDATE notificacoes SET titulo_notificacao = :titulo, gravidade = :gravidade, 
                    descricao_notificacao = :descricao, assunto = :assunto WHERE id_notificacao = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'titulo' => $titulo,
                'gravidade' => $gravidade,
                'descricao' => $descricao,
                'assunto' => $assunto,
                'id' => $id
            ]);
            
            $sucesso = "Notificação atualizada com sucesso!";
            
            $stmt = $pdo->prepare("SELECT * FROM notificacoes WHERE id_notificacao = :id");
            $stmt->execute(['id' => $id]);
            $notificacao = $stmt->fetch();
            
        } catch(PDOException $e) {
            $erro = "Erro ao atualizar notificação.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notificação - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        <div class="header-dashboard">
            <h1>Editar Notificação #<?= $id ?></h1>
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
                    <label for="titulo_notificacao">Título da Notificação *</label>
                </div>
                <div class="campo-input">
                    <input type="text" name="titulo_notificacao" id="titulo_notificacao" 
                           value="<?= sanitizar($notificacao['titulo_notificacao']) ?>" required>
                </div>

                <div class="campo-label">
                    <label for="gravidade">Grau de Gravidade *</label>
                </div>
                <div class="campo-input">
                    <select name="gravidade" id="gravidade" required>
                        <option value="critica" <?= $notificacao['gravidade'] == 'critica' ? 'selected' : '' ?>>🔴 Crítica</option>
                        <option value="alta" <?= $notificacao['gravidade'] == 'alta' ? 'selected' : '' ?>>🟠 Alta</option>
                        <option value="media" <?= $notificacao['gravidade'] == 'media' ? 'selected' : '' ?>>🟡 Média</option>
                        <option value="baixa" <?= $notificacao['gravidade'] == 'baixa' ? 'selected' : '' ?>>🟢 Baixa</option>
                    </select>
                </div>

                <div class="campo-label">
                    <label for="assunto">Assunto *</label>
                </div>
                <div class="campo-input">
                    <select name="assunto" id="assunto" required>
                        <option value="trens" <?= $notificacao['assunto'] == 'trens' ? 'selected' : '' ?>>🚂 Trens</option>
                        <option value="sensores" <?= $notificacao['assunto'] == 'sensores' ? 'selected' : '' ?>>📡 Sensores</option>
                        <option value="manutencao" <?= $notificacao['assunto'] == 'manutencao' ? 'selected' : '' ?>>🔧 Manutenção</option>
                        <option value="rotas" <?= $notificacao['assunto'] == 'rotas' ? 'selected' : '' ?>>🗺️ Rotas</option>
                        <option value="usuarios" <?= $notificacao['assunto'] == 'usuarios' ? 'selected' : '' ?>>👤 Usuários</option>
                    </select>
                </div>

                <div class="campo-label">
                    <label for="descricao_notificacao">Descrição da Notificação *</label>
                </div>
                <div class="campo-input">
                    <textarea name="descricao_notificacao" id="descricao_notificacao" required><?= sanitizar($notificacao['descricao_notificacao']) ?></textarea>
                </div>

                <div class="campo-label">
                    <label>Data de Criação</label>
                </div>
                <div class="campo-input">
                    <input type="text" value="<?= formatarTimestamp($notificacao['criado_em']) ?>" disabled>
                </div>
                <br>
                <button type="submit" class="botao botao-primario botao-completo">
                    Atualizar Notificação
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