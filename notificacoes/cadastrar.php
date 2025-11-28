<?php
/**
 * =====================================================
 * CADASTRO DE NOTIFICAÇÕES
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPaginaAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo_notificacao'] ?? '';
    $gravidade = $_POST['gravidade'] ?? '';
    $descricao = $_POST['descricao_notificacao'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    
    if (empty($titulo) || empty($gravidade) || empty($descricao) || empty($assunto)) {
        $erro = "Preencha todos os campos obrigatórios!";
    } else {
        try {
            $sql = "INSERT INTO notificacoes (titulo_notificacao, gravidade, descricao_notificacao, assunto) 
                    VALUES (:titulo, :gravidade, :descricao, :assunto)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'titulo' => $titulo,
                'gravidade' => $gravidade,
                'descricao' => $descricao,
                'assunto' => $assunto
            ]);
            
            $sucesso = "Notificação cadastrada com sucesso!";
            
        } catch(PDOException $e) {
            $erro = "Erro ao cadastrar notificação.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Notificação - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        
        <div class="header-dashboard">
            <h1>Cadastrar Notificação</h1>
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
                           placeholder="Ex: Manutenção programada para o trem #5" required>
                </div>

                <div class="campo-label">
                    <label for="gravidade">Grau de Gravidade *</label>
                </div>
                <div class="campo-input">
                    <select name="gravidade" id="gravidade" required>
                        <option value="">Selecione...</option>
                        <option value="critica">🔴 Crítica</option>
                        <option value="alta">🟠 Alta</option>
                        <option value="media">🟡 Média</option>
                        <option value="baixa">🟢 Baixa</option>
                    </select>
                </div>

                <div class="campo-label">
                    <label for="assunto">Assunto *</label>
                </div>
                <div class="campo-input">
                    <select name="assunto" id="assunto" required>
                        <option value="">Selecione...</option>
                        <option value="trens">🚂 Trens</option>
                        <option value="sensores">📡 Sensores</option>
                        <option value="manutencao">🔧 Manutenção</option>
                        <option value="rotas">🗺️ Rotas</option>
                        <option value="usuarios">👤 Usuários</option>
                    </select>
                </div>

                <div class="campo-label">
                    <label for="descricao_notificacao">Descrição da Notificação *</label>
                </div>
                <div class="campo-input">
                    <textarea name="descricao_notificacao" id="descricao_notificacao" 
                              placeholder="Descreva os detalhes da notificação..." required></textarea>
                </div>
                <br>
                <button type="submit" class="botao botao-primario botao-completo">
                    Cadastrar Notificação
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