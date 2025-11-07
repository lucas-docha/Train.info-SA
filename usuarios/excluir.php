<?php
require_once '../verificar_sessao.php';
require_once '../config.php';
protegerPaginaAdmin();

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['sucesso'] = "Usuário excluído com sucesso!";
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir usuário.";
    }
}

header("Location: listar.php");
exit;
?>
