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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <!-- Container centralizado do login -->
    <div class="login-container" style="padding-top: 3rem; padding-bottom: 6rem;">
        
        <!-- Título da página -->
        <div class="titulo-pagina">
            <h1>Login</h1>
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
            <br>
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
    </div>

    <!-- Rodapé -->
    <div class="rodape">
        <p>© 2025 Sistema de Gerenciamento de Trens</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
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
