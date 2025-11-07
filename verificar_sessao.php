<?php
/**
 * =====================================================
 * SISTEMA DE VERIFICAÇÃO DE SESSÃO E AUTENTICAÇÃO
 * =====================================================
 * Este arquivo gerencia sessões de usuários e controla
 * o acesso às páginas do sistema
 * 
 * Funções disponíveis:
 * - estaLogado(): Verifica se usuário está autenticado
 * - dadosUsuario(): Retorna dados do usuário logado
 * - protegerPagina(): Impede acesso não autorizado
 * - ehAdmin(): Verifica se usuário é administrador
 * - protegerPaginaAdmin(): Permite acesso apenas a admins
 */

// =====================================================
// INICIALIZAÇÃO DA SESSÃO
// =====================================================
// Inicia sessão apenas se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * =====================================================
 * FUNÇÃO: estaLogado
 * =====================================================
 * Verifica se o usuário está autenticado no sistema
 * 
 * @return bool True se logado, False caso contrário
 */
function estaLogado() {
    return isset($_SESSION['logado']) && 
           $_SESSION['logado'] === true && 
           isset($_SESSION['usuario_id']);
}

/**
 * =====================================================
 * FUNÇÃO: dadosUsuario
 * =====================================================
 * Retorna dados do usuário logado
 * 
 * @param string|null $campo Campo específico ou null para todos
 * @return mixed Dados do usuário ou null se não logado
 * 
 * Exemplos:
 * - dadosUsuario('id') retorna apenas o ID
 * - dadosUsuario('nome') retorna apenas o nome
 * - dadosUsuario() retorna array com todos os dados
 */
function dadosUsuario($campo = null) {
    if (!estaLogado()) {
        return null;
    }
    
    // Se solicitou campo específico
    if ($campo) {
        return $_SESSION['usuario_' . $campo] ?? null;
    }
    
    // Retorna todos os dados
    return [
        'id' => $_SESSION['usuario_id'] ?? null,
        'nome' => $_SESSION['usuario_nome'] ?? null,
        'email' => $_SESSION['usuario_email'] ?? null,
        'tipo' => $_SESSION['usuario_tipo'] ?? null
    ];
}

/**
 * =====================================================
 * FUNÇÃO: ehAdmin
 * =====================================================
 * Verifica se o usuário logado é administrador
 * 
 * @return bool True se for admin, False caso contrário
 */
function ehAdmin() {
    if (!estaLogado()) {
        return false;
    }
    return isset($_SESSION['usuario_tipo']) && 
           $_SESSION['usuario_tipo'] === 'admin';
}

/**
 * =====================================================
 * FUNÇÃO: protegerPagina
 * =====================================================
 * Protege página contra acesso não autenticado
 * Redireciona para login se usuário não estiver logado
 * 
 * @param string $redirecionarPara URL de redirecionamento (padrão: TelaLogin.php)
 */
function protegerPagina($redirecionarPara = 'TelaLogin.php') {
    if (!estaLogado()) {
        $_SESSION['erro'] = "Você precisa fazer login para acessar esta página!";
        header("Location: $redirecionarPara");
        exit;
    }
    
    // Verifica timeout da sessão
    verificarTimeoutSessao();
}

/**
 * =====================================================
 * FUNÇÃO: protegerPaginaAdmin
 * =====================================================
 * Protege página para acesso APENAS de administradores
 * Redireciona usuários comuns para o dashboard
 * 
 * @param string $redirecionarPara URL de redirecionamento (padrão: dashboard.php)
 */
function protegerPaginaAdmin($redirecionarPara = 'dashboard.php') {
    // Primeiro verifica se está logado
    protegerPagina();
    
    // Depois verifica se é admin
    if (!ehAdmin()) {
        $_SESSION['erro'] = "Acesso negado! Apenas administradores podem acessar esta página.";
        header("Location: $redirecionarPara");
        exit;
    }
}

/**
 * =====================================================
 * FUNÇÃO: verificarTimeoutSessao
 * =====================================================
 * Verifica se a sessão expirou por inatividade
 * Encerra sessão se tempo limite foi excedido
 * 
 * Tempo limite padrão: 30 minutos (1800 segundos)
 */
function verificarTimeoutSessao() {
    $tempoLimite = 1800; // 30 minutos em segundos
    
    if (isset($_SESSION['ultima_atividade'])) {
        $tempoInativo = time() - $_SESSION['ultima_atividade'];
        
        // Se passou do tempo limite, encerra sessão
        if ($tempoInativo > $tempoLimite) {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['erro'] = "Sua sessão expirou por inatividade! Faça login novamente.";
            header("Location: TelaLogin.php");
            exit;
        }
    }
    
    // Atualiza timestamp da última atividade
    $_SESSION['ultima_atividade'] = time();
}

/**
 * =====================================================
 * FUNÇÃO: nomeExibicao
 * =====================================================
 * Retorna apenas o primeiro nome do usuário
 * Útil para exibir "Olá, João" ao invés de "Olá, João Silva Santos"
 * 
 * @return string Primeiro nome ou "Usuário" se não logado
 */
function nomeExibicao() {
    $nome = dadosUsuario('nome');
    if ($nome) {
        $partes = explode(' ', $nome);
        return $partes[0];
    }
    return 'Usuário';
}

/**
 * =====================================================
 * FUNÇÃO: tipoUsuarioExibicao
 * =====================================================
 * Retorna tipo de usuário formatado para exibição
 * 
 * @return string "Administrador" ou "Usuário"
 */
function tipoUsuarioExibicao() {
    if (ehAdmin()) {
        return 'Administrador';
    }
    return 'Usuário';
}

/**
 * =====================================================
 * REGENERAÇÃO PERIÓDICA DO ID DE SESSÃO
 * =====================================================
 * Previne ataques de fixação de sessão
 * Regenera ID a cada 5 minutos
 */
if (estaLogado()) {
    if (!isset($_SESSION['tempo_criacao'])) {
        $_SESSION['tempo_criacao'] = time();
    } else if (time() - $_SESSION['tempo_criacao'] > 300) {
        // Regenera ID após 5 minutos
        session_regenerate_id(true);
        $_SESSION['tempo_criacao'] = time();
    }
}

/**
 * =====================================================
 * NOTAS DE SEGURANÇA
 * =====================================================
 * 
 * 1. Sempre use protegerPagina() no início de páginas restritas
 * 
 * 2. Use protegerPaginaAdmin() em páginas exclusivas de admin
 * 
 * 3. Nunca confie apenas em JavaScript para segurança
 *    Sempre valide no servidor (PHP)
 * 
 * 4. A sessão expira após 30 minutos de inatividade
 * 
 * 5. O ID de sessão é regenerado periodicamente para
 *    prevenir ataques de fixação de sessão
 * 
 * 6. Exemplo de uso:
 *    require_once 'verificar_sessao.php';
 *    protegerPagina(); // Página requer login
 *    // ou
 *    protegerPaginaAdmin(); // Página requer admin
 */
?>
