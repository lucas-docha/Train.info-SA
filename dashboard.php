<?php
/**
 * =====================================================
 * DASHBOARD COM MAPA INTERATIVO - PAINEL PRINCIPAL
 * =====================================================
 */

require_once 'verificar_sessao.php';
protegerPagina();
require_once 'config.php';

$usuario = dadosUsuario();
$nomeExibicao = nomeExibicao();
$tipoUsuario = tipoUsuarioExibicao();
$ehAdmin = ehAdmin();

// =====================================================
// BUSCA ESTATÍSTICAS DO SISTEMA
// =====================================================
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sensores");
    $totalSensores = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM manutencoes WHERE status_manutencao = 'pendente'");
    $manutencoesPendentes = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM rotas");
    $totalRotas = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM trens");
    $totalTrens = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM trens WHERE status_trem = 'operante'");
    $trensOperantes = $stmt->fetch()['total'];

    if ($ehAdmin) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $totalUsuarios = $stmt->fetch()['total'];
    }

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM notificacoes");
    $totalNotificacoes = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM notificacoes WHERE gravidade = 'critica'");
    $notificacoesCriticas = $stmt->fetch()['total'];

} catch (PDOException $e) {
    $totalSensores = 0;
    $manutencoesPendentes = 0;
    $totalRotas = 0;
    $totalTrens = 0;
    $trensOperantes = 0;
    $totalUsuarios = 0;
    $totalNotificacoes = 0;
    $notificacoesCriticas = 0;
}

// Conectar ao banco 'trem' para buscar rotas e estações
try {
    $pdoTrem = new PDO("mysql:host=localhost;dbname=trem;charset=utf8mb4", "root", "root");
    $pdoTrem->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar total de estações e rotas ferroviárias
    $stmt = $pdoTrem->query("SELECT COUNT(*) as total FROM estacoes");
    $totalEstacoes = $stmt->fetch()['total'];
    
    $stmt = $pdoTrem->query("SELECT COUNT(*) as total FROM rotas");
    $totalRotasFerroviarias = $stmt->fetch()['total'];
} catch (PDOException $e) {
    $totalEstacoes = 0;
    $totalRotasFerroviarias = 0;
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        #map {
            height: 500px;
            width: 100%;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1);
        }
        
        .map-container {
            background-color: #131630;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1);
        }
        
        .map-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .station-marker-custom {
            background-color: #e74c3c;
            border: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        
        .leaflet-popup-content-wrapper {
            background-color: #131630;
            color: white;
        }
        
        .leaflet-popup-content h3 {
            color: #41b8d5;
            margin-bottom: 10px;
        }
        
        .leaflet-popup-tip {
            background-color: #131630;
        }
        
        .route-info {
            background-color: rgba(65, 184, 213, 0.1);
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            border-left: 3px solid #41b8d5;
        }
        
        .legend {
            background-color: rgba(19, 22, 48, 0.9);
            padding: 15px;
            border-radius: 8px;
            color: white;
            position: absolute;
            bottom: 30px;
            right: 10px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }
        
        .legend-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 50%;
        }
        
        .mode-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .mode-view {
            background-color: #3498db;
        }
        
        .mode-edit {
            background-color: #e74c3c;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark fixed-top p-2">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold flex" href="#">
                <div><p style="font-size: larger; margin: 0;">Train</p><p>.info</p></div>
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
                            <a class="nav-link" href="notificacoes/listar.php">
                                Notificações
                                <?php if ($totalNotificacoes > 0): ?>
                                    <span class="badge bg-danger ms-1"><?= $totalNotificacoes ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <br>
                        <?php if ($ehAdmin): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="usuarios/listar.php">Usuários</a>
                        </li>
                         <br>
                        <?php endif; ?>
                       
                        <li class="nav-item">
                            <a class="nav-link" href="relatorios/visualizar.php">Relatórios</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container" style="padding-top: 120px;">
        <!-- CABEÇALHO DO DASHBOARD -->
        <div class="header-dashboard">
            <h1 class="welcome-msg">
                Olá, <?= htmlspecialchars($nomeExibicao) ?>!
            </h1>
            <a href="logout.php" class="botao botao-perigo">Sair</a>
        </div>

        <!-- ESTATÍSTICAS RÁPIDAS -->
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

            <!-- Card: Notificações -->
            <div class="card">
                <h2>Notificações</h2>
                <p><span class="label">Total:</span> <?= $totalNotificacoes ?></p>
                <?php if ($notificacoesCriticas > 0): ?>
                    <p>
                        <span class="label">Críticas:</span> 
                        <span style="color: #ff4444; font-weight: bold;"><?= $notificacoesCriticas ?></span>
                    </p>
                <?php else: ?>
                    <p><span class="label">Críticas:</span> <span style="color: #44ff44;">0</span></p>
                <?php endif; ?>
                <a href="notificacoes/listar.php" class="btn-menu" style="margin-top: 10px; display: inline-block;">
                    Ver Notificações
                </a>
            </div>

            <!-- Card: Usuários (apenas admin) -->
            <?php if ($ehAdmin): ?>
                <div class="card">
                    <h2>Usuários</h2>
                    <p><span class="label">Total de Usuários:</span> <?= $totalUsuarios ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- MAPA INTERATIVO -->
        <div class="map-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="margin: 0;">
                    <i class="fas fa-map-marked-alt"></i> Mapa de Rotas Ferroviárias
                </h2>
            </div>
            
            <div class="route-info">
                <p style="margin: 0;">
                    <span class="label">Estações Cadastradas:</span> <?= $totalEstacoes ?>
                    <span class="label" style="margin-left: 20px;">Rotas Ferroviárias:</span> <?= $totalRotasFerroviarias ?>
                </p>
            </div>
            <br>
            <?php if ($ehAdmin): ?>
            <div class="map-controls">
                <button id="btn-add-station" class="botao botao-primario">
                    <i class="fas fa-plus-circle"></i> Adicionar Estação
                </button>
            </div>
            <?php endif; ?>
            
            <div id="map"></div>
        </div>

        <!-- Modal para adicionar/editar estação -->
        <div id="station-modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 2000; justify-content: center; align-items: center;">
            <div style="background-color: #131630; padding: 25px; border-radius: 10px; width: 450px; max-width: 90%; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #2e3356;">
                    <h3 id="modal-title" style="margin: 0; color: #41b8d5;">
                        <i class="fas fa-train"></i> Adicionar Estação
                    </h3>
                    <span class="close" style="font-size: 24px; cursor: pointer; color: white;">&times;</span>
                </div>
                <form id="station-form">
                    <input type="hidden" id="station-id">
                    <div class="campo-label">Nome da Estação:</div>
                    <div class="campo-input">
                        <input type="text" id="station-name" required>
                    </div>
                    
                    <div class="campo-label">Endereço:</div>
                    <div class="campo-input">
                        <input type="text" id="station-address">
                    </div>
                    
                    <div class="campo-label">Latitude:</div>
                    <div class="campo-input">
                        <input type="text" id="station-lat" required>
                    </div>
                    
                    <div class="campo-label">Longitude:</div>
                    <div class="campo-input">
                        <input type="text" id="station-lng" required>
                    </div>
                    
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="botao botao-sucesso">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                        <button type="button" class="botao botao-perigo" id="btn-delete-station" style="display: none;">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- INFORMAÇÕES DO USUÁRIO -->
        <div class="card">
            <h2>Minhas Informações</h2>
            <p><span class="label">Nome:</span> <?= htmlspecialchars($usuario['nome']) ?></p>
            <p><span class="label">Email:</span> <?= htmlspecialchars($usuario['email']) ?></p>
            <p><span class="label">Tipo de Acesso:</span>
                <span class="badge <?= $ehAdmin ? 'badge-admin' : 'badge-usuario' ?>">
                    <?= $tipoUsuario ?>
                </span>
            </p>
        </div>

        <!-- RODAPÉ -->
        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="js/mapa.js"></script>
</body>
</html>