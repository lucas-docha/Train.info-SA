/**
 * ARQUIVO JAVASCRIPT DA TELA DE LOGIN
 * Funções auxiliares para login social e navegação
 */

/**
 * Abre página de login do Google em nova aba
 * NOTA: Login social real requer OAuth2 configurado
 */
function googlelogin() {
    window.open("https://accounts.google.com/v3/signin/identifier?continue=https%3A%2F%2Fwww.google.com%3Fhl%3Dpt-PT&ec=GAlA8wE&hl=pt-PT&flowName=GlifWebSignIn&flowEntry=AddSession&dsh=S1678227823%3A1749223930685292", "_blank");
}

/**
 * Abre página de login do Facebook em nova aba
 * NOTA: Login social real requer Facebook SDK configurado
 */
function facebooklogin() {
    window.open("https://pt-br.facebook.com/login/device-based/regular/login/", "_blank");
}

function EsqueciSenha() {
    window.location.href = "../TelaEsqueci.html";
}

/**
 * VALIDAÇÕES ADICIONAIS DO FORMULÁRIO
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Pega o formulário se existir
    const formLogin = document.querySelector('form[action="login.php"]');
    
    if (formLogin) {
        // Adiciona validação antes de enviar
        formLogin.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('password').value;
            
            // Validação básica de email
            if (!email.includes('@') || !email.includes('.')) {
                alert('Por favor, insira um email válido!');
                e.preventDefault();
                return false;
            }
            
            // Verifica senha vazia
            if (senha.length === 0) {
                alert('Por favor, insira sua senha!');
                e.preventDefault();
                return false;
            }
            
            // Se tudo ok, permite envio
            // O PHP fará a validação completa
        });
    }
    
    /**
     * MOSTRAR/OCULTAR SENHA
     * Adiciona botão para mostrar senha se não existir
     */
    const inputSenha = document.getElementById('password');
    if (inputSenha && !document.getElementById('toggle-password')) {
        // Cria botão de mostrar/ocultar
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.id = 'toggle-password';
        toggleBtn.innerHTML = '👁️';
        toggleBtn.style.cssText = `
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
        `;
        
        // Ajusta container da senha para position relative
        const caixaSenha = inputSenha.parentElement;
        caixaSenha.style.position = 'relative';
        
        // Adiciona botão ao container
        caixaSenha.appendChild(toggleBtn);
        
        // Função de toggle
        toggleBtn.addEventListener('click', function() {
            if (inputSenha.type === 'password') {
                inputSenha.type = 'text';
                this.innerHTML = '🙈';
            } else {
                inputSenha.type = 'password';
                this.innerHTML = '👁️';
            }
        });
    }
    
    /**
     * ANIMAÇÃO DE LOADING
     * Mostra indicador ao submeter formulário
     */
    if (formLogin) {
        formLogin.addEventListener('submit', function() {
            const btnEntrar = document.querySelector('.botao-entrar');
            if (btnEntrar) {
                // Salva texto original
                const textoOriginal = btnEntrar.innerHTML;
                
                // Mostra loading
                btnEntrar.innerHTML = 'Verificando... ⏳';
                btnEntrar.disabled = true;
                
                // Restaura após timeout (caso dê erro)
                setTimeout(function() {
                    btnEntrar.innerHTML = textoOriginal;
                    btnEntrar.disabled = false;
                }, 5000);
            }
        });
    }
    
    /**
     * CAPS LOCK WARNING
     * Avisa se Caps Lock estiver ativado no campo de senha
     */
    if (inputSenha) {
        inputSenha.addEventListener('keyup', function(e) {
            const capsWarning = document.getElementById('caps-warning');
            
            if (e.getModifierState && e.getModifierState('CapsLock')) {
                if (!capsWarning) {
                    const warning = document.createElement('div');
                    warning.id = 'caps-warning';
                    warning.style.cssText = `
                        color: #ffaa00;
                        font-size: 12px;
                        margin: 5px 0;
                    `;
                    warning.innerHTML = '⚠️ Caps Lock está ativado';
                    inputSenha.parentElement.appendChild(warning);
                }
            } else {
                if (capsWarning) {
                    capsWarning.remove();
                }
            }
        });
    }
    
    /**
     * AUTO-COMPLETE EMAIL
     * Se o usuário já logou antes, sugere o email
     */
    const inputEmail = document.getElementById('email');
    if (inputEmail && localStorage.getItem('ultimo_email')) {
        // Sugere último email usado
        inputEmail.placeholder = localStorage.getItem('ultimo_email');
    }
    
    // Salva email ao fazer login (não a senha!)
    if (formLogin) {
        formLogin.addEventListener('submit', function() {
            const email = document.getElementById('email').value;
            if (email) {
                localStorage.setItem('ultimo_email', email);
            }
        });
    }
});

/**
 * DETECTA INATIVIDADE
 * Útil para auto-logout por segurança
 */
let tempoInatividade = 0;

// Reseta contador ao detectar atividade
document.addEventListener('mousemove', resetarInatividade);
document.addEventListener('keypress', resetarInatividade);

function resetarInatividade() {
    tempoInatividade = 0;
}

// Verifica inatividade a cada minuto
setInterval(function() {
    tempoInatividade++;
    
    // Após 15 minutos de inatividade na tela de login
    if (tempoInatividade > 15) {
        // Recarrega a página para limpar dados sensíveis
        if (confirm('Você está inativo há muito tempo. Deseja continuar?')) {
            tempoInatividade = 0;
        } else {
            window.location.reload();
        }
    }
}, 60000); // Checa a cada 1 minuto

/**
 * MELHORIAS DE ACESSIBILIDADE
 */
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona labels ARIA para leitores de tela
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        if (!input.getAttribute('aria-label')) {
            const label = input.previousElementSibling;
            if (label && label.tagName === 'LABEL') {
                input.setAttribute('aria-label', label.textContent);
            }
        }
    });
});

/**
 * NOTAS DO DESENVOLVEDOR:
 * - Este arquivo complementa o sistema PHP de login
 * - Validações críticas devem ser feitas no servidor (PHP)
 * - JavaScript é apenas para melhorar UX, não para segurança
 * - Login social precisa de configuração OAuth2 apropriada
 */