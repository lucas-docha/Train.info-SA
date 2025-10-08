<?php
session_start();

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
function valorCampo($campo) {
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
        /* Reutiliza a maior parte do CSS da tela de login */
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
    </style>
</head>

<body>
    <section id="login">
        <div class="container">
            <div class="login">
                <h1>Cadastro</h1>
            </div>

            <!-- Exibe erros se houver -->
            <?php if (!empty($erros)): ?>
                <div class="erro-msg">
                    <?php foreach($erros as $erro): ?>
                         <?= htmlspecialchars($erro) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulário de cadastro -->
            <form action="registro.php" method="post">
                
                <!-- NOME COMPLETO -->
                <div class="email">
                    <label for="nome">Nome Completo *</label>
                </div>
                <div class="caixa">
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           placeholder="Digite seu nome completo" 
                           value="<?= valorCampo('nome') ?>"
                           required>
                </div>

                <!-- EMAIL -->
                <div class="senha">
                    <label for="email">Email *</label>
                </div>
                <div class="caixa">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           placeholder="seu.email@exemplo.com" 
                           value="<?= valorCampo('email') ?>"
                           required>
                </div>

                <!-- CPF -->
                <div class="senha">
                    <label for="cpf">CPF *</label>
                </div>
                <div class="caixa">
                    <input type="text" 
                           id="cpf" 
                           name="cpf" 
                           placeholder="000.000.000-00" 
                           maxlength="14"
                           value="<?= valorCampo('cpf') ?>"
                           required>
                </div>

                <!-- TELEFONE E CEP (lado a lado) -->
                <div class="campos-duplos" style="margin: 0 3rem;">
                    <div class="campo-metade">
                        <div style="color: white; margin-bottom: 0.5rem; margin-top: 1rem;">
                            <label for="telefone">Telefone</label>
                        </div>
                        <div style="display: flex; height: 40px;">
                            <input type="text" 
                                   id="telefone" 
                                   name="telefone" 
                                   placeholder="(00) 00000-0000"
                                   maxlength="15"
                                   value="<?= valorCampo('telefone') ?>"
                                   style="width: 100%; height: 100%; background-color: transparent; 
                                          border: 2px solid white; border-radius: 25px; 
                                          outline: none; color: white; padding: 5px 20px;">
                        </div>
                    </div>

                    <div class="campo-metade">
                        <div style="color: white; margin-bottom: 0.5rem; margin-top: 1rem;">
                            <label for="cep">CEP</label>
                        </div>
                        <div style="display: flex; height: 40px;">
                            <input type="text" 
                                   id="cep" 
                                   name="cep" 
                                   placeholder="00000-000"
                                   maxlength="9"
                                   value="<?= valorCampo('cep') ?>"
                                   style="width: 100%; height: 100%; background-color: transparent; 
                                          border: 2px solid white; border-radius: 25px; 
                                          outline: none; color: white; padding: 5px 20px;">
                        </div>
                    </div>
                </div>

                <!-- SENHA -->
                <div class="senha">
                    <label for="senha">Senha * (mínimo 6 caracteres)</label>
                </div>
                <div class="caixa">
                    <input type="password" 
                           id="senha" 
                           name="senha" 
                           placeholder="Digite uma senha segura" 
                           minlength="6"
                           required>
                </div>

                <!-- CONFIRMAR SENHA -->
                <div class="senha">
                    <label for="confirma_senha">Confirme a Senha *</label>
                </div>
                <div class="caixa">
                    <input type="password" 
                           id="confirma_senha" 
                           name="confirma_senha" 
                           placeholder="Digite a senha novamente" 
                           minlength="6"
                           required>
                </div>

                <!-- BOTÃO CADASTRAR -->
                <div style="padding-top: 2rem; padding-left: 3rem; padding-right: 2.5rem;">
                    <button class="botao-entrar" type="submit">Cadastrar</button>
                </div>
            </form>

            <!-- LINK PARA VOLTAR AO LOGIN -->
            <div class="voltar-login">
                Já tem uma conta? <a href="TelaLogin.php">Faça login</a>
            </div>

        </div>
    </section>

    <!-- JavaScript para máscaras e validações -->
    <script>
        /**
         * MÁSCARAS PARA OS CAMPOS
         * Formata automaticamente CPF, telefone e CEP enquanto o usuário digita
         */
        
        // Máscara de CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove não-números
            if (value.length <= 11) {
                // Formata: 000.000.000-00
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });

        // Máscara de Telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                // Formata: (00) 00000-0000 ou (00) 0000-0000
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
            }
            e.target.value = value;
        });

        // Máscara de CEP
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                // Formata: 00000-000
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });

        /**
         * VALIDAÇÃO DE CONFIRMAÇÃO DE SENHA
         * Verifica em tempo real se as senhas conferem
         */
        document.getElementById('confirma_senha').addEventListener('input', function(e) {
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
         * VALIDAÇÃO ANTES DE ENVIAR
         * Última verificação antes de submeter o formulário
         */
        document.querySelector('form').addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmaSenha = document.getElementById('confirma_senha').value;
            
            if (senha !== confirmaSenha) {
                e.preventDefault(); // Impede envio
                alert('As senhas não conferem!');
                return false;
            }
        });
    </script>
</body>
</html>