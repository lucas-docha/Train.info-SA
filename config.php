<?php
/**
 * =====================================================
 * ARQUIVO DE CONFIGURAÇÃO DO SISTEMA
 * =====================================================
 * Este arquivo estabelece a conexão com o banco de dados
 * e define configurações globais do sistema
 * 
 * IMPORTANTE: Em produção, mova as credenciais para
 * variáveis de ambiente (.env) por segurança
 */

// =====================================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// =====================================================
// Configurações padrão do XAMPP
$host     = "localhost";       // Servidor do banco (XAMPP usa localhost)
$usuario  = "root";            // Usuário padrão do MySQL no XAMPP
$senha    = "root";           
$banco    = "banco_SA";        // Nome do banco definido no SQL

try {
    /**
     * Cria conexão PDO com MySQL
     * PDO (PHP Data Objects) é mais seguro que mysqli
     * Permite prepared statements que previnem SQL Injection
     */
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
    /**
     * Se falhar a conexão, exibe erro
     * Em produção, registre em log e mostre mensagem genérica
     */
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// =====================================================
// CONFIGURAÇÕES GLOBAIS
// =====================================================

// Define timezone brasileiro (importante para timestamps)
date_default_timezone_set("America/Sao_Paulo");

// Define o charset para UTF-8 (suporta acentuação)
header('Content-Type: text/html; charset=utf-8');

// =====================================================
// CONSTANTES DO SISTEMA
// =====================================================

// Tempo de expiração da sessão (em segundos)
define('TEMPO_SESSAO', 1800); // 30 minutos

// Tipos de usuário permitidos
define('TIPO_ADMIN', 'admin');
define('TIPO_USUARIO', 'usuario');

// Tipos de sensores
define('SENSOR_PRESENCA', 'presenca');
define('SENSOR_UMIDADE_TEMP', 'umidade_temperatura');
define('SENSOR_ILUMINACAO', 'iluminacao');

// Status de manutenção
define('STATUS_PENDENTE', 'pendente');
define('STATUS_EM_ANDAMENTO', 'em_andamento');
define('STATUS_CONCLUIDA', 'concluida');
define('STATUS_CANCELADA', 'cancelada');

/**
 * =====================================================
 * FUNÇÕES AUXILIARES GLOBAIS
 * =====================================================
 */

/**
 * Sanitiza string para prevenir XSS
 * Usa htmlspecialchars para converter caracteres especiais
 * 
 * @param string $texto Texto a ser sanitizado
 * @return string Texto sanitizado
 */
function sanitizar($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/**
 * Formata CPF para exibição
 * Converte 12345678901 em 123.456.789-01
 * 
 * @param string $cpf CPF sem formatação
 * @return string CPF formatado
 */
function formatarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) {
        return $cpf;
    }
    return substr($cpf, 0, 3) . '.' . 
           substr($cpf, 3, 3) . '.' . 
           substr($cpf, 6, 3) . '-' . 
           substr($cpf, 9, 2);
}

/**
 * Calcula duração entre dois horários
 * Retorna diferença formatada (ex: "1h 30min")
 * 
 * @param string $inicio Horário inicial (HH:MM:SS)
 * @param string $fim Horário final (HH:MM:SS)
 * @return string Duração formatada
 */
function calcularDuracao($inicio, $fim) {
    $dt_inicio = new DateTime($inicio);
    $dt_fim = new DateTime($fim);
    $intervalo = $dt_inicio->diff($dt_fim);
    
    $horas = $intervalo->h;
    $minutos = $intervalo->i;
    
    if ($horas > 0) {
        return $horas . "h " . $minutos . "min";
    }
    return $minutos . "min";
}

/**
 * Formata data brasileira
 * Converte 2024-11-04 em 04/11/2024
 * 
 * @param string $data Data no formato Y-m-d
 * @return string Data formatada
 */
function formatarData($data) {
    if (empty($data)) return '-';
    $dt = new DateTime($data);
    return $dt->format('d/m/Y');
}

/**
 * Formata timestamp completo
 * Converte 2024-11-04 14:30:00 em 04/11/2024 14:30
 * 
 * @param string $timestamp Timestamp do banco
 * @return string Timestamp formatado
 */
function formatarTimestamp($timestamp) {
    if (empty($timestamp)) return '-';
    $dt = new DateTime($timestamp);
    return $dt->format('d/m/Y H:i');
}

/**
 * =====================================================
 * NOTAS DO DESENVOLVEDOR
 * =====================================================
 * 
 * 1. Este arquivo deve ser incluído em todas as páginas PHP
 *    usando: require_once 'config.php';
 * 
 * 2. A conexão PDO ($pdo) fica disponível globalmente
 * 
 * 3. Sempre use prepared statements para queries:
 *    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
 *    $stmt->execute(['id' => $id]);
 * 
 * 4. Em produção, NUNCA exiba erros detalhados ao usuário
 *    Use um sistema de logs (error_log)
 * 
 * 5. Mantenha este arquivo fora do diretório público se possível
 */
?>
