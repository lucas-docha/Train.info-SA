<?php
/**
 * =====================================================
 * TELA DE LOGIN
 * =====================================================
 * Interface para autenticação de usuários
 */

// Inicia sessão para exibir mensagens
session_start();

// Recupera mensagens da sessão
$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';

// Limpa as mensagens após recuperar
unset($_SESSION['erro']);
unset($_SESSION['sucesso']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gerenciamento de Trens</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <!-- Container centralizado do login -->
    <div class="login-container">
        
        <!-- Título da página -->
        <div class="titulo-pagina">
            <h1>Login</h1>
            <p style="color: #6ce5e8; margin-top: 0.5rem;">Sistema de Gerenciamento de Trens</p>
        </div>

        <!-- Mensagem de sucesso (ex: após cadastro) -->
        <?php if ($sucesso): ?>
            <div class="mensagem mensagem-sucesso">
                <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>

        <!-- Mensagem de erro (ex: credenciais inválidas) -->
        <?php if ($erro): ?>
            <div class="mensagem mensagem-erro">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de login -->
        <form action="login.php" method="post" id="formLogin">
            
            <!-- Campo de email -->
            <div class="campo-label">
                <label for="email">Email</label>
            </div>
            <div class="campo-input">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="seu.email@exemplo.com" 
                    required
                    autocomplete="email"
                >
            </div>

            <!-- Campo de senha -->
            <div class="campo-label">
                <label for="password">Senha</label>
            </div>
            <div class="campo-input">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Digite sua senha" 
                    required
                    autocomplete="current-password"
                >
            </div>

            <!-- Botão de entrar -->
            <button type="submit" class="botao botao-primario botao-completo">
                Entrar
            </button>

        </form>

        <!-- Informações de teste -->
        <div style="margin: 2rem 3rem; padding: 15px; background-color: #2e3356; border-radius: 8px; font-size: 12px;">
            <p style="color: #6ce5e8; font-weight: bold; margin-bottom: 10px;">🔑 Credenciais de Teste:</p>
            <p style="margin: 5px 0;"><strong>Admin:</strong> admin@sistema.com / admin123</p>
            <p style="margin: 5px 0;"><strong>Usuário:</strong> usuario@sistema.com / usuario123</p>
        </div>

    </div>

    <!-- Rodapé -->
    <div class="rodape">
        <p>© 2025 Sistema de Gerenciamento de Trens</p>
    </div>

    <!-- JavaScript -->
    <script>
        /**
         * Remove mensagens automaticamente após 5 segundos
         */
        setTimeout(function() {
            const mensagens = document.querySelectorAll('.mensagem');
            mensagens.forEach(function(msg) {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);

        /**
         * Validação básica do formulário
         */
        document.getElementById('formLogin').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('password').value;

            // Verifica se email contém @ e .
            if (!email.includes('@') || !email.includes('.')) {
                alert('Por favor, insira um email válido!');
                e.preventDefault();
                return false;
            }

            // Verifica se senha não está vazia
            if (senha.length === 0) {
                alert('Por favor, insira sua senha!');
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>
