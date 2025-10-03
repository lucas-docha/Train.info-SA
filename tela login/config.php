<?php
/**
 * ARQUIVO DE CONFIGURAÇÃO E CONEXÃO COM O BANCO
 * Usa PDO (PHP Data Objects) para maior segurança e compatibilidade
 * PDO permite prepared statements que previnem SQL Injection
 */

// Configurações do banco de dados
$host     = "localhost";       // Servidor do banco (XAMPP usa localhost)
$usuario  = "root";            // Usuário padrão do MySQL no XAMPP
$senha    = "root";                // Senha vazia é padrão no XAMPP (mude se necessário)
$banco    = "banco_SA";        // Nome do banco definido no seu SQL

try {
    // Cria conexão PDO com MySQL
    // charset=utf8mb4 suporta emojis e caracteres especiais
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            // Lança exceções em caso de erro (facilita debug)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            
            // Retorna arrays associativos por padrão
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            
            // Desabilita emulação de prepared statements (mais seguro)
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
} catch(PDOException $e) {
    // Se falhar a conexão, mostra erro (em produção, registre em log)
    die("Erro na conexão com o banco: " . $e->getMessage());
}

// Define timezone brasileiro (importante para timestamps)
date_default_timezone_set("America/Sao_Paulo");

/**
 * NOTA: Em produção, NUNCA mostre mensagens de erro detalhadas
 * Use um sistema de logs e mostre apenas "Erro no sistema" ao usuário
 */
?>