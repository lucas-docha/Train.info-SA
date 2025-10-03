<?php
session_start(); 

// Recupera mensagens da sessão
$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';

// Limpa as mensagens após exibir
unset($_SESSION['erro']);
unset($_SESSION['sucesso']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./TelaLogin.css">
    <title>Tela de Login</title>
    <style>
        /* Estilos para mensagens de feedback */
        .msg-erro {
            background-color: #ff4444;
            color: white;
            padding: 10px;
            margin: 10px 3rem;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        
        .msg-sucesso {
            background-color: #44ff44;
            color: #1a1e34;
            padding: 10px;
            margin: 10px 3rem;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        
        .criar-conta {
            text-align: center;
            margin-top: 20px;
            color: white;
            font-size: 14px;
        }
        
        .criar-conta a {
            color: #6ce5e8;
            text-decoration: none;
        }
        
        .criar-conta a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <section id="login">
        <div class="container">
            <div class="login">
                <h1>Login</h1>
            </div>

            <!-- Mostra mensagem de sucesso (após cadastro) -->
            <?php if ($sucesso): ?>
                <div class="msg-sucesso">
                    <?= htmlspecialchars($sucesso) ?>
                </div>
            <?php endif; ?>

            <!-- Mostra mensagem de erro se houver -->
            <?php if ($erro): ?>
                <div class="msg-erro">
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <!-- FORMULÁRIO DE LOGIN -->
            <form action="login.php" method="post">
                <div class="info">
                    <!-- CAMPO EMAIL -->
                    <div class="email">
                        <label for="email">Email</label>
                    </div>
                    <div class="caixa">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               placeholder="Insira o seu email aqui!" 
                               required>
                    </div>

                    <!-- CAMPO SENHA -->
                    <div class="senha">
                        <label for="password">Senha</label>
                    </div>
                    <div class="caixa">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Insira sua senha aqui!" 
                               required>
                    </div>
                </div>

                <!-- CHECKBOX LEMBRAR E ESQUECI SENHA -->
                <div class="lembrar">
                    <label>
                        <input type="checkbox" name="lembrar">
                        Lembre de mim
                    </label>
                    <div class="esqueci">
                        <!-- type="button" evita que submeta o form -->
                        <button type="button" onclick="EsqueciSenha()">
                            <p>Esqueceu a senha?</p>
                        </button>
                    </div>
                </div>

                <!-- BOTÃO DE ENTRAR -->
                <div class="botão" style="padding-top: 2rem; padding-left: 3rem; padding-right: 2.5rem;">
                    <!-- type="submit" envia o formulário -->
                    <button class="botao-entrar" type="submit">Entrar</button>
                </div>
            </form>
            <!-- Fim do formulário -->

            <!-- LINK PARA CRIAR CONTA -->
            <div class="criar-conta">
                Não tem uma conta? <a href="TelaRegistro.php">Cadastre-se aqui</a>
            </div>

            <!-- DIVISOR "OU ENTRE COM" -->
            <div>
                <div class="flex">
                    <hr style="width: 25%; margin-top: 3rem; color: #bec2d0;">
                    <p style="color: white;">Ou entre com</p>
                    <hr style="width: 25%; margin-top: 3rem; color:#bec2d0;">
                </div>
            </div>

            <!-- BOTÕES DE LOGIN SOCIAL -->
            <div class="logar">
                <!-- FACEBOOK -->
                <div class="facebook flex">
                    <button type="button" onclick="facebooklogin()">
                        <img src="../imgens/facebook-icon.ico" alt="Facebook">
                        <p style="background-color: #2e3356; color: white;">Facebook</p>
                    </button>
                </div>
                
                <!-- GOOGLE -->
                <div class="google flex">
                    <button type="button" onclick="googlelogin()">
                        <img src="../imgens/google-icon.ico" alt="Google">
                        <p style="background-color: #2e3356; color: white;">Google</p>
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- JavaScript -->
    <script src="TelaLogin.js"></script>
    <script>
        /**
         * Remove mensagens após 5 segundos
         * Melhora a experiência do usuário
         */
        setTimeout(function() {
            const msgs = document.querySelectorAll('.msg-erro, .msg-sucesso');
            msgs.forEach(function(msg) {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
    </script>
</body>

</html>