<?php
/**
 * =====================================================
 * TELA EXPERIMENTAL
 * =====================================================
 * Tela de demonstração não funcional
 * Acessível apenas pelo dashboard no Menu Rápido
 * Serve para testes e protótipos de novas funcionalidades
 */

require_once '../verificar_sessao.php';
protegerPagina();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Experimental - Sistema de Gerenciamento de Trens</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        /* Estilos específicos da tela experimental */
        .experimental-banner {
            background: linear-gradient(135deg, #ffaa00 0%, #ff6600 100%);
            color: #1a1e34;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .funcionalidade-experimental {
            background-color: #2e3356;
            padding: 20px;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 5px solid #ffaa00;
        }

        .funcionalidade-experimental h3 {
            color: #ffaa00;
            margin-top: 0;
        }

        .demo-button {
            background-color: #ffaa00;
            color: #1a1e34;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin: 5px;
            transition: all 0.3s;
        }

        .demo-button:hover {
            background-color: #ff8800;
            transform: scale(1.05);
        }

        .demo-button:disabled {
            background-color: #666;
            color: #999;
            cursor: not-allowed;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .status-online {
            background-color: #44ff44;
        }

        .status-offline {
            background-color: #ff4444;
        }

        .mockup-chart {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            height: 200px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6ce5e8;
            font-size: 18px;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        
        <!-- Cabeçalho -->
        <div class="header-dashboard">
            <h1>🧪 Tela Experimental</h1>
            <a href="../dashboard.php" class="botao botao-secundario">← Voltar ao Dashboard</a>
        </div>

        <!-- Banner de Aviso -->
        <div class="experimental-banner">
            ⚠️ ATENÇÃO: Esta é uma tela experimental e não funcional! ⚠️
            <br>
            As funcionalidades aqui apresentadas são apenas demonstrações e protótipos.
        </div>

        <!-- =====================================================
             FUNCIONALIDADE 1: MONITORAMENTO EM TEMPO REAL
             ===================================================== -->
        <div class="card">
            <h2>📡 Monitoramento em Tempo Real (Protótipo)</h2>
            
            <div class="funcionalidade-experimental">
                <h3>Status dos Sensores</h3>
                <p>
                    <span class="status-indicator status-online"></span>
                    Sensor de Presença - Linha 1: <strong>ONLINE</strong>
                </p>
                <p>
                    <span class="status-indicator status-online"></span>
                    Sensor de Temperatura - Vagão 3: <strong>ONLINE</strong>
                </p>
                <p>
                    <span class="status-indicator status-offline"></span>
                    Sensor de Iluminação - Túnel Norte: <strong>OFFLINE</strong>
                </p>
                
                <button class="demo-button" onclick="alert('Funcionalidade em desenvolvimento!')">
                    🔄 Atualizar Status
                </button>
                <button class="demo-button" disabled>
                    📊 Ver Histórico
                </button>
            </div>
        </div>

        <!-- =====================================================
             FUNCIONALIDADE 2: GRÁFICOS E ANÁLISES
             ===================================================== -->
        <div class="card">
            <h2>📊 Análise de Dados (Mockup)</h2>
            
            <div class="funcionalidade-experimental">
                <h3>Gráfico de Temperatura - Últimas 24h</h3>
                <div class="mockup-chart">
                    📈 Gráfico em desenvolvimento...
                    <br>
                    (Aqui seria exibido um gráfico interativo)
                </div>
                
                <button class="demo-button" onclick="alert('Gráfico será implementado em versão futura!')">
                    📊 Gerar Gráfico
                </button>
                <button class="demo-button" disabled>
                    💾 Exportar Dados
                </button>
            </div>
        </div>

        <!-- =====================================================
             FUNCIONALIDADE 3: ALERTAS INTELIGENTES
             ===================================================== -->
        <div class="card">
            <h2>🔔 Sistema de Alertas Inteligentes (Protótipo)</h2>
            
            <div class="funcionalidade-experimental">
                <h3>Configuração de Alertas</h3>
                <p>Configure alertas automáticos baseados em condições dos sensores:</p>
                
                <div style="margin: 15px 0;">
                    <label style="display: block; margin: 10px 0;">
                        <input type="checkbox" disabled>
                        Alertar quando temperatura > 30°C
                    </label>
                    <label style="display: block; margin: 10px 0;">
                        <input type="checkbox" disabled>
                        Alertar quando umidade < 40%
                    </label>
                    <label style="display: block; margin: 10px 0;">
                        <input type="checkbox" disabled>
                        Alertar quando sensor ficar offline
                    </label>
                </div>
                
                <button class="demo-button" disabled>
                    💾 Salvar Configurações
                </button>
                <button class="demo-button" onclick="alert('Sistema de notificações em desenvolvimento!')">
                    📧 Testar Notificação
                </button>
            </div>
        </div>

        <!-- =====================================================
             FUNCIONALIDADE 4: INTEGRAÇÃO COM IOT
             ===================================================== -->
        <div class="card">
            <h2>🌐 Integração IoT (Conceito)</h2>
            
            <div class="funcionalidade-experimental">
                <h3>Conexão com Dispositivos IoT</h3>
                <p>Conecte sensores físicos ao sistema via API REST ou MQTT:</p>
                
                <div style="background-color: #1a1e34; padding: 15px; border-radius: 8px; margin: 15px 0; font-family: monospace;">
                    <p style="color: #6ce5e8; margin: 5px 0;">
                        POST /api/sensores/leitura
                    </p>
                    <p style="color: #44ff44; margin: 5px 0;">
                        {
                    </p>
                    <p style="color: white; margin: 5px 0; padding-left: 20px;">
                        "tipo": "temperatura",
                    </p>
                    <p style="color: white; margin: 5px 0; padding-left: 20px;">
                        "valor": 25.5,
                    </p>
                    <p style="color: white; margin: 5px 0; padding-left: 20px;">
                        "timestamp": "2024-11-04T14:30:00"
                    </p>
                    <p style="color: #44ff44; margin: 5px 0;">
                        }
                    </p>
                </div>
                
                <button class="demo-button" disabled>
                    🔌 Conectar Dispositivo
                </button>
                <button class="demo-button" onclick="alert('Documentação da API em desenvolvimento!')">
                    📖 Ver Documentação
                </button>
            </div>
        </div>

        <!-- =====================================================
             FUNCIONALIDADE 5: PREVISÃO E MACHINE LEARNING
             ===================================================== -->
        <div class="card">
            <h2>🤖 Inteligência Artificial (Futuro)</h2>
            
            <div class="funcionalidade-experimental">
                <h3>Previsão de Manutenções</h3>
                <p>Utilize machine learning para prever necessidades de manutenção:</p>
                
                <div class="mockup-chart">
                    🤖 Modelo de IA em treinamento...
                    <br>
                    Precisão atual: 0% (não implementado)
                </div>
                
                <button class="demo-button" disabled>
                    🎯 Treinar Modelo
                </button>
                <button class="demo-button" disabled>
                    🔮 Fazer Previsão
                </button>
            </div>
        </div>

        <!-- =====================================================
             INFORMAÇÕES ADICIONAIS
             ===================================================== -->
        <div class="card">
            <h2>ℹ️ Sobre Esta Tela</h2>
            <p>
                Esta tela experimental foi criada para demonstrar possíveis funcionalidades futuras 
                do sistema de gerenciamento de trens. Nenhuma das funcionalidades aqui apresentadas 
                está implementada de forma funcional.
            </p>
            <p style="margin-top: 15px;">
                <strong>Objetivo:</strong> Servir como referência visual para desenvolvimento futuro 
                e testes de interface.
            </p>
            <p style="margin-top: 15px;">
                <strong>Status:</strong> <span class="badge badge-pendente">EM DESENVOLVIMENTO</span>
            </p>
        </div>

        <!-- Rodapé -->
        <div class="rodape">
            <p>© 2025 Sistema de Gerenciamento de Trens</p>
            <p>Versão Experimental - Não Funcional</p>
        </div>

    </div>

    <script>
        /**
         * Script de demonstração
         * Simula algumas interações (não funcionais)
         */
        
        // Mostra mensagem ao carregar a página
        window.addEventListener('load', function() {
            console.log('🧪 Tela Experimental carregada!');
            console.log('Esta é uma demonstração não funcional.');
        });

        // Simula atualização de status (visual apenas)
        setInterval(function() {
            const indicators = document.querySelectorAll('.status-indicator');
            indicators.forEach(function(indicator) {
                // Apenas animação visual, sem funcionalidade real
            });
        }, 2000);
    </script>
</body>
</html>
