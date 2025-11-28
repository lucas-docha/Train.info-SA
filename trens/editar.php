<?php
/**
 * =====================================================
 * EDIÇÃO DE TRENS
 * =====================================================
 * Permite editar apenas o status do trem
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPaginaAdmin();

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM trens WHERE id_trem = :id");
    $stmt->execute(['id' => $id]);
    $trem = $stmt->fetch();
    
    if (!$trem) {
        header("Location: listar.php");
        exit;
    }
} catch(PDOException $e) {
    $erro = "Erro ao buscar trem.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status_trem'] ?? '';
    
    if (empty($status)) {
        $erro = "Selecione o status!";
    } else {
        try {
            $sql = "UPDATE trens SET status_trem = :status WHERE id_trem = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'status' => $status,
                'id' => $id
            ]);
            
            $sucesso = "Status do trem atualizado!";
            
            $stmt = $pdo->prepare("SELECT * FROM trens WHERE id_trem = :id");
            $stmt->execute(['id' => $id]);
            $trem = $stmt->fetch();
            
        } catch(PDOException $e) {
            $erro = "Erro ao atualizar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trem - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>
    <div class="container">
        <div class="header-dashboard">
            <h1>Editar Trem #<?= $id ?></h1>
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
                    <label>Tipo de Trem (não editável)</label>
                </div>
                <div class="campo-input">
                    <input type="text" value="<?= ucfirst($trem['tipo_trem']) ?>" disabled>
                </div>

                <?php if ($trem['tipo_trem'] == 'carga' && $trem['carga_trem']): ?>
                <div class="campo-label">
                    <label>Tipo de Carga (não editável)</label>
                </div>
                <div class="campo-input">
                    <input type="text" value="<?= sanitizar($trem['carga_trem']) ?>" disabled>
                </div>
                <?php endif; ?>

                <div class="campo-label">
                    <label for="status_trem">Status do Trem *</label>
                </div>
                <div class="campo-input">
                    <select name="status_trem" id="status_trem" required>
                        <option value="operante" <?= $trem['status_trem'] == 'operante' ? 'selected' : '' ?>>
                            Operante
                        </option>
                        <option value="em_manutencao" <?= $trem['status_trem'] == 'em_manutencao' ? 'selected' : '' ?>>
                            Em Manutenção
                        </option>
                        <option value="fora_de_servico" <?= $trem['status_trem'] == 'fora_de_servico' ? 'selected' : '' ?>>
                            Fora de Serviço
                        </option>
                    </select>
                </div>

                <div class="campo-label">
                    <label>Data de Cadastro</label>
                </div>
                <div class="campo-input">
                    <input type="text" value="<?= formatarTimestamp($trem['criado_em']) ?>" disabled>
                </div>

                <button type="submit" class="botao botao-primario botao-completo">
                    Atualizar Status
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