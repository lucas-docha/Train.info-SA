<?php
/**
 * =====================================================
 * EXCLUSÃO DE SENSORES
 * =====================================================
 * Remove registros de sensores do banco de dados
 */

require_once '../verificar_sessao.php';
require_once '../config.php';

protegerPagina();

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        // Exclui o sensor
        $stmt = $pdo->prepare("DELETE FROM sensores WHERE id_sensor = :id");
        $stmt->execute(['id' => $id]);
        
        // Redireciona com mensagem de sucesso
        $_SESSION['sucesso'] = "Sensor excluído com sucesso!";
        
    } catch(PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir sensor.";
    }
}

header("Location: listar.php");
exit;
?>
