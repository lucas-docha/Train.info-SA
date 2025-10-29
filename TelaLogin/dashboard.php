<?php
/**
 * DASHBOARD SIMPLIFICADO
 * Busca dados da tabela ADMIN
 */

require_once 'verificar_sessao.php';
protegerPagina();

$usuario = dadosUsuario();
$nomeExibicao = nomeExibicao();

// Busca informações do admin
require_once 'config.php';

try {
    $sql = "SELECT * FROM admin WHERE id_admin = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => dadosUsuario('id')]);
    $dadosCompletos = $stmt->fetch();
} catch(PDOException $e) {
    $dadosCompletos = null;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($nomeExibicao) ?></title>
    <link rel="stylesheet" href="TelaLogin.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header-dashboard {
            background-color: #131630;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1);
        }
        
        .welcome-msg {
            color: white;
            font-size: 24px;
        }
        
        .btn-logout {
            background-color: #ff4444;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-logout:hover {
            background-color: #cc0000;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .card {
            background-color: #131630;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1);
        }
        
        .card h2 {
            color: #41b8d5;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .card p {
            color: white;
            margin: 10px 0;
            font-size: 14px;
        }
        
        .card .label {
            color: #6ce5e8;
            font-weight: bold;
        }
        
        .menu-rapido {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .btn-menu {
            background-color: #41b8d5;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            max-width: 9rem;
        }
        
        .btn-menu:hover {
            background-color: #3694b9;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- HEADER -->
        <div class="header-dashboard">
            <h1 class="welcome-msg">
                Olá, <?= htmlspecialchars($nomeExibicao) ?>! (Admin)
            </h1>
            <a href="logout.php" class="btn-logout">Sair</a>
        </div>

        <!-- CARDS -->
        <div class="cards-container">
            
            <!-- INFORMAÇÕES PESSOAIS -->
            <div class="card">
                <h2>Minhas Informações</h2>
                <p><span class="label">Nome:</span> <?= htmlspecialchars($dadosCompletos['nome_admin'] ?? 'Não informado') ?></p>
                <p><span class="label">Email:</span> <?= htmlspecialchars($dadosCompletos['email_admin'] ?? 'Não informado') ?></p>
                <p><span class="label">CPF:</span> 
                    <?php 
                    $cpf = $dadosCompletos['cpf_admin'] ?? '';
                    if ($cpf) {
                        echo substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
                    } else {
                        echo 'Não informado';
                    }
                    ?>
                </p>
            </div>

            <!-- MENU RÁPIDO -->
            <div class="card">
                <h2>Menu Rápido</h2>
                <div class="menu-rapido">
                    <a href="../TelaPrincipal/TelaPrincipal.php" class="btn-menu">Página Principal</a>
                    <a href="TelaRegistroAdmin.php" class="btn-menu">Cadastrar Admin</a>
                    <a href="TelaRegistroUser.php" class="btn-menu">Cadastrar User</a>
                </div>
            </div>

        </div>

        <!-- RODAPÉ -->
        <div style="text-align: center; margin-top: 50px; color: #6ce5e8; font-size: 12px;">
            <p>© 2025 Tran.info</p>
        </div>
    </div>
</body>
</html>