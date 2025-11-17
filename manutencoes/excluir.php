<?php
require_once '../verificar_sessao.php';
require_once '../config.php';
protegerPagina();

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM manutencoes WHERE id_manutencao = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['sucesso'] = "Manutenção excluída com sucesso!";
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir manutenção.";
    }
}

header("Location: listar.php");
exit;
?>
