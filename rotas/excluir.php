<?php
require_once '../verificar_sessao.php';
require_once '../config.php';
protegerPagina();

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM rotas WHERE id_rota = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['sucesso'] = "Rota excluída com sucesso!";
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir rota.";
    }
}

header("Location: listar.php");
exit;
?>
