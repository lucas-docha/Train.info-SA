<?php
/**
 * =====================================================
 * EXCLUSÃO DE TRENS
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPaginaAdmin();

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM trens WHERE id_trem = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['sucesso'] = "Trem excluído com sucesso!";
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir trem.";
    }
}

header("Location: listar.php");
exit;
?>