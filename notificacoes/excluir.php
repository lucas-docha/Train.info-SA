<?php
/**
 * =====================================================
 * EXCLUSÃO DE NOTIFICAÇÕES
 * =====================================================
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPaginaAdmin();

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM notificacoes WHERE id_notificacao = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['sucesso'] = "Notificação excluída com sucesso!";
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir notificação.";
    }
}

header("Location: listar.php");
exit;
?>