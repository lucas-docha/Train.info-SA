<?php

session_start();

require_once 'verificar_sessao.php';
protegerPagina();



// Recupera erros e dados anteriores (se houver)
$erros = $_SESSION['erros_cadastro'] ?? [];
$dados_form = $_SESSION['dados_form'] ?? [];

// Limpa as mensagens após exibir
unset($_SESSION['erros_cadastro']);
unset($_SESSION['dados_form']);

/**
 * Função helper para preencher campos com dados anteriores
 * Evita que o usuário perca tudo que digitou em caso de erro
 */
function valorCampo($campo)
{
    global $dados_form;
    return htmlspecialchars($dados_form[$campo] ?? '');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./TelaLogin.css">
    <title>Cadastro de Usuário</title>
    <style>
        /* Estilos específicos para a tela de registro */
        .erro-msg {
            background-color: #ff4444;
            color: white;
            padding: 10px;
            margin: 10px 3rem;
            border-radius: 5px;
            font-size: 14px;
        }

        .campos-duplos {
            display: flex;
            gap: 1rem;
        }

        .campo-metade {
            flex: 1;
        }

        .voltar-login {
            text-align: center;
            margin-top: 20px;
            color: #6ce5e8;
            font-size: 14px;
        }

        .voltar-login a {
            color: #6ce5e8;
            text-decoration: none;
        }

        .voltar-login a:hover {
            text-decoration: underline;
        }

        /* Estilos do Pop-up */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .popup-overlay.ativo {
            display: flex;
        }

        .popup-conteudo {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 90%;
            color: white;
            text-align: center;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .popup-conteudo h2 {
            margin-top: 0;
            color: #6ce5e8;
            font-size: 24px;
        }

        .popup-conteudo input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border: 2px solid #6ce5e8;
            border-radius: 8px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            box-sizing: border-box;
        }

        .popup-conteudo input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .popup-conteudo input[type="password"]:focus {
            outline: none;
            border-color: #41b8bb;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .popup-botoes {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .popup-botoes button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-confirmar {
            background-color: #6ce5e8;
            color: #1e3c72;
        }

        .btn-confirmar:hover {
            background-color: #41b8bb;
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background-color: #ff4444;
            color: white;
        }

        .btn-cancelar:hover {
            background-color: #cc0000;
            transform: translateY(-2px);
        }

        .popup-erro {
            color: #ff4444;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }

        h1{
            margin-bottom: 0;
        }

        h3{
            margin-top: 0;
            color:rgb(207, 207, 207);
        }

        .container{
            margin-bottom: 4rem;
        }

    </style>
</head>

<body>
    <section id="login">
        <div class="container">
            <div class="login">
                <h1>Cadastro</h1>
                <h3>Usuário</h3>
            </div>

            <!-- Exibe erros se houver -->
            <?php if (!empty($erros)): ?>
                <div class="erro-msg">
                    <?php foreach ($erros as $erro): ?>
                        <?= htmlspecialchars($erro) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulário de cadastro -->
            <form action="registro.php" method="post" id="formCadastro">

                <!-- NOME COMPLETO -->
                <div class="email">
                    <label for="nome">Nome Completo *</label>
                </div>
                <div class="caixa">
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo"
                        value="<?= valorCampo('nome') ?>" required>
                </div>

                <!-- EMAIL -->
                <div class="senha">
                    <label for="email">Email *</label>
                </div>
                <div class="caixa">
                    <input type="email" id="email" name="email" placeholder="seu.email@exemplo.com"
                        value="<?= valorCampo('email') ?>" required>
                </div>

                <!-- CPF -->
                <div class="senha">
                    <label for="cpf">CPF *</label>
                </div>
                <div class="caixa">
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14"
                        value="<?= valorCampo('cpf') ?>" required>
                </div>

                <!-- SENHA -->
                <div class="senha">
                    <label for="senha">Senha * (mínimo 6 caracteres)</label>
                </div>
                <div class="caixa">
                    <input type="password" id="senha" name="senha" placeholder="Digite uma senha segura" minlength="6" required>
                </div>

                <!-- CONFIRMAR SENHA -->
                <div class="senha">
                    <label for="confirma_senha">Confirme a Senha *</label>
                </div>
                <div class="caixa">
                    <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Digite a senha novamente"
                        minlength="6" required>
                </div>

                <!-- BOTÃO CADASTRAR -->
                <div style="padding-top: 2rem; padding-left: 3rem; padding-right: 2.5rem;">
                    <button class="botao-entrar" type="button" onclick="abrirPopup()">Cadastrar</button>
                </div>

            </form>
        </div>
    </section>

    <!-- Pop-up de Confirmação -->
    <div class="popup-overlay" id="popupConfirmacao">
        <div class="popup-conteudo">
            <h2>🔒 Confirme sua Senha</h2>
            <p>Por segurança, digite sua senha novamente para confirmar o cadastro:</p>
            <input type="password" id="senhaConfirmacaoPopup" placeholder="Digite sua senha">
            <div class="popup-erro" id="popupErro">A senha não confere!</div>
            <div class="popup-botoes">
                <button class="btn-cancelar" onclick="fecharPopup()">Cancelar</button>
                <button class="btn-confirmar" onclick="confirmarCadastro()">Confirmar</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        /**
         * MÁSCARAS PARA OS CAMPOS
         */

        // Máscara de CPF
        document.getElementById('cpf').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });

        /**
         * VALIDAÇÃO DE CONFIRMAÇÃO DE SENHA
         */
        document.getElementById('confirma_senha').addEventListener('input', function (e) {
            const senha = document.getElementById('senha').value;
            const confirmaSenha = e.target.value;

            if (senha !== confirmaSenha && confirmaSenha.length > 0) {
                e.target.setCustomValidity('As senhas não conferem!');
                e.target.style.borderColor = '#ff4444';
            } else {
                e.target.setCustomValidity('');
                e.target.style.borderColor = 'white';
            }
        });

        /**
         * FUNÇÕES DO POP-UP
         */
        function abrirPopup() {
            // Valida o formulário antes de abrir o pop-up
            const form = document.getElementById('formCadastro');
            
            // Verifica se todos os campos obrigatórios estão preenchidos
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Verifica se as senhas conferem
            const senha = document.getElementById('senha').value;
            const confirmaSenha = document.getElementById('confirma_senha').value;

            if (senha !== confirmaSenha) {
                alert('As senhas não conferem!');
                return;
            }

            // Abre o pop-up
            document.getElementById('popupConfirmacao').classList.add('ativo');
            document.getElementById('senhaConfirmacaoPopup').focus();
        }

        function fecharPopup() {
            document.getElementById('popupConfirmacao').classList.remove('ativo');
            document.getElementById('senhaConfirmacaoPopup').value = '';
            document.getElementById('popupErro').style.display = 'none';
        }

        function confirmarCadastro() {
            const senhaOriginal = document.getElementById('senha').value;
            const senhaConfirmacao = document.getElementById('senhaConfirmacaoPopup').value;

            if (senhaConfirmacao === '') {
                document.getElementById('popupErro').textContent = 'Por favor, digite sua senha!';
                document.getElementById('popupErro').style.display = 'block';
                return;
            }

            if (senhaOriginal !== senhaConfirmacao) {
                document.getElementById('popupErro').textContent = 'A senha não confere!';
                document.getElementById('popupErro').style.display = 'block';
                document.getElementById('senhaConfirmacaoPopup').style.borderColor = '#ff4444';
                return;
            }

            // Senha confirmada, submete o formulário
            document.getElementById('formCadastro').submit();
        }

        // Permite confirmar pressionando Enter no pop-up
        document.getElementById('senhaConfirmacaoPopup').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                confirmarCadastro();
            }
        });

        // Fecha o pop-up clicando fora dele
        document.getElementById('popupConfirmacao').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharPopup();
            }
        });
    </script>
</body>

</html>