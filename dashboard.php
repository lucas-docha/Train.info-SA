<?php
/**
 * =====================================================
 * DASHBOARD - PAINEL PRINCIPAL DO SISTEMA
 * =====================================================
 * Ponto central de acesso a todas as funcionalidades
 */

// Inclui verificação de sessão
require_once 'verificar_sessao.php';

// Protege a página (requer login)
protegerPagina();

// Inclui configuração do banco
require_once 'config.php';

// Recupera dados do usuário logado
$usuario = dadosUsuario();
$nomeExibicao = nomeExibicao();
$tipoUsuario = tipoUsuarioExibicao();
$ehAdmin = ehAdmin();

// =====================================================
// BUSCA ESTATÍSTICAS DO SISTEMA
// =====================================================
try {
    // Conta total de sensores
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sensores");
    $totalSensores = $stmt->fetch()['total'];
    
    // Conta manutenções pendentes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM manutencoes WHERE status_manutencao = 'pendente'");
    $manutencoesPendentes = $stmt->fetch()['total'];
    
    // Conta total de rotas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM rotas");
    $totalRotas = $stmt->fetch()['total'];
    
    // Conta total de usuários (apenas admin pode ver)
    if ($ehAdmin) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $totalUsuarios = $stmt->fetch()['total'];
    }
    
} catch(PDOException $e) {
    // Em caso de erro, define valores padrão
    $totalSensores = 0;
    $manutencoesPendentes = 0;
    $totalRotas = 0;
    $totalUsuarios = 0;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gerenciamento de Trens</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <div class="container">
        
        <!-- =====================================================
             CABEÇALHO DO DASHBOARD
             ===================================================== -->
        <div class="header-dashboard">
            <h1 class="welcome-msg">
                Olá, <?= sanitizar($nomeExibicao) ?>! 
                <span class="badge <?= $ehAdmin ? 'badge-admin' : 'badge-usuario' ?>">
                    <?= $tipoUsuario ?>
                </span>
            </h1>
            <a href="logout.php" class="botao botao-perigo">Sair</a>
        </div>

        <!-- =====================================================
             ESTATÍSTICAS RÁPIDAS
             ===================================================== -->
        <div class="cards-container">
            
            <!-- Card: Sensores -->
            <div class="card">
                <h2>📡 Sensores</h2>
                <p><span class="label">Total de Leituras:</span> <?= $totalSensores ?></p>
                <div style="margin-top: 15px;">
                    <a href="sensores/listar.php" class="botao botao-primario" style="font-size: 14px;">
                        Ver Sensores
                    </a>
                </div>
            </div>

            <!-- Card: Manutenções -->
            <div class="card">
                <h2>🔧 Manutenções</h2>
                <p><span class="label">Pendentes:</span> <?= $manutencoesPendentes ?></p>
                <div style="margin-top: 15px;">
                    <a href="manutencoes/listar.php" class="botao botao-primario" style="font-size: 14px;">
                        Ver Manutenções
                    </a>
                </div>
            </div>

            <!-- Card: Rotas -->
            <div class="card">
                <h2>🚆 Rotas</h2>
                <p><span class="label">Total de Rotas:</span> <?= $totalRotas ?></p>
                <div style="margin-top: 15px;">
                    <a href="rotas/listar.php" class="botao botao-primario" style="font-size: 14px;">
                        Ver Rotas
                    </a>
                </div>
            </div>

            <!-- Card: Usuários (apenas admin) -->
            <?php if ($ehAdmin): ?>
            <div class="card">
                <h2>👥 Usuários</h2>
                <p><span class="label">Total de Usuários:</span> <?= $totalUsuarios ?></p>
                <div style="margin-top: 15px;">
                    <a href="usuarios/listar.php" class="botao botao-primario" style="font-size: 14px;">
                        Gerenciar Usuários
                    </a>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <!-- =====================================================
             MENU RÁPIDO
             ===================================================== -->
        <div class="card">
            <h2>⚡ Menu Rápido</h2>
            <div class="menu-rapido">
                
                <!-- Links para todos os usuários -->
                <a href="sensores/cadastrar.php" class="btn-menu">➕ Novo Sensor</a>
                <a href="manutencoes/cadastrar.php" class="btn-menu">➕ Nova Manutenção</a>
                <a href="rotas/cadastrar.php" class="btn-menu">➕ Nova Rota</a>
                <a href="relatorios/visualizar.php" class="btn-menu">📊 Relatórios</a>
                
                <!-- Link para tela experimental -->
                <a href="experimental/tela_teste.php" class="btn-menu" style="background-color: #ffaa00; color: #1a1e34;">
                    🧪 Tela Experimental
                </a>
                
                <!-- Links apenas para admin -->
                <?php if ($ehAdmin): ?>
                <a href="usuarios/cadastrar.php" class="btn-menu" style="background-color: #ff4444;">
                    👤 Cadastrar Usuário
                </a>
                <?php endif; ?>
                
            </div>
        </div>

        <!-- =====================================================
             INFORMAÇÕES DO USUÁRIO
             ===================================================== -->
        <div class="card">
            <h2>👤 Minhas Informações</h2>
            <p><span class="label">Nome:</span> <?= sanitizar($usuario['nome']) ?></p>
            <p><span class="label">Email:</span> <?= sanitizar($usuario['email']) ?></p>
            <p><span class="label">Tipo de Acesso:</span> 
                <span class="badge <?= $ehAdmin ? 'badge-admin' : 'badge-usuario' ?>">
                    <?= $tipoUsuario ?>
                </span>
            </p>
        </div>

        <!-- =====================================================
             RODAPÉ
             ===================================================== -->
        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>

    </div>
</body>
</html>
