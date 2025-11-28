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

    // Conta total de trens
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM trens");
    $totalTrens = $stmt->fetch()['total'];

    // Conta trens operantes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM trens WHERE status_trem = 'operante'");
    $trensOperantes = $stmt->fetch()['total'];

    // Conta total de usuários (apenas admin pode ver)
    if ($ehAdmin) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $totalUsuarios = $stmt->fetch()['total'];
    }

} catch (PDOException $e) {
    // Em caso de erro, define valores padrão
    $totalSensores = 0;
    $manutencoesPendentes = 0;
    $totalRotas = 0;
    $totalTrens = 0;
    $trensOperantes = 0;
    $totalUsuarios = 0;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gerenciamento de Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark fixed-top p-2">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold flex" href="#">
                <div ><p style="font-size: larger; margin: 0;">Train</p><p>.info</p></div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
                aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h1 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <br>
                        <li class="nav-item">
                            <a class="nav-link" href="trens/listar.php">Trens</a>
                        </li>
                        <br>
                        <li class="nav-item">
                            <a class="nav-link" href="sensores/listar.php">Sensores</a>
                        </li>
                        <br>
                        <li class="nav-item">
                            <a class="nav-link" href="manutencoes/listar.php">Manutenções</a>
                        </li>
                        <br>
                        <li class="nav-item">
                            <a class="nav-link" href="rotas/listar.php">Rotas</a>
                        </li>
                        <br>
                        <li class="nav-item">
                            <a class="nav-link" href="usuarios/listar.php">Usuários</a>
                        </li>
                        <br>
                        <li class="nav-item">
                            <a class="nav-link" href="relatorios/visualizar.php">Relatórios</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container" style="padding-top: 120px;">

        <!-- =====================================================
             CABEÇALHO DO DASHBOARD
             ===================================================== -->
        <div class="header-dashboard">
            <h1 class="welcome-msg">
                Olá, <?= sanitizar($nomeExibicao) ?>!
            </h1>
            <a href="logout.php" class="botao botao-perigo">Sair</a>
        </div>

        <!-- =====================================================
             ESTATÍSTICAS RÁPIDAS
             ===================================================== -->
        <div class="cards-container">

            <!-- Card: Trens -->
            <div class="card">
                <h2>Trens</h2>
                <p><span class="label">Total de Trens:</span> <?= $totalTrens ?></p>
                <p><span class="label">Operantes:</span> <?= $trensOperantes ?></p>
            </div>

            <!-- Card: Sensores -->
            <div class="card">
                <h2>Sensores</h2>
                <p><span class="label">Total de Leituras:</span> <?= $totalSensores ?></p>
            </div>

            <!-- Card: Manutenções -->
            <div class="card">
                <h2>Manutenções</h2>
                <p><span class="label">Pendentes:</span> <?= $manutencoesPendentes ?></p>
            </div>

            <!-- Card: Rotas -->
            <div class="card">
                <h2>Rotas</h2>
                <p><span class="label">Total de Rotas:</span> <?= $totalRotas ?></p>
            </div>

            <!-- Card: Usuários (apenas admin) -->
            <?php if ($ehAdmin): ?>
                <div class="card">
                    <h2>Usuários</h2>
                    <p><span class="label">Total de Usuários:</span> <?= $totalUsuarios ?></p>
                </div>
            <?php endif; ?>

        </div>

        <!-- =====================================================
             INFORMAÇÕES DO USUÁRIO
             ===================================================== -->
        <div class="card">
            <h2>Minhas Informações</h2>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>