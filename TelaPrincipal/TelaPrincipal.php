<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="telaprincipal.css">

    <title>Tela</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-dark" style="background-color: #131630;">
            <div class="container-fluid">
                <div class="flex w-100">
                    <!-- Formulário de busca -->
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search"
                            style="border-radius: 2rem; background-color: transparent; color: white;" />
                        <button class="btn" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="20px"
                                height="auto" fill="white" class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg></button>
                    </form>
                    <div class="navbarPC">
                        <ul class="navbar-nav flex-row gap-4 d-none d-lg-flex">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Tela principal/TelaPrincipal.html">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../TelaRotas/TelaRotas.html">Rotas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../Tela monitoramento/telamonitoramento.html">Monitoramento</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link text-white" href="../TelaRelatório/relatorio.html">Relatórios</a>
                            </li>
                            <br>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../tela login/TelaLogin.html">Entrar</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Botão do menu (hambúrguer) -->
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Menu lateral (offcanvas) -->
                <div class="offcanvas offcanvas-end text-light w-40" tabindex="-1" id="offcanvasDarkNavbar"
                    aria-labelledby="offcanvasDarkNavbarLabel"
                    style="background-color: #131630; --bs-offcanvas-width: 80%;">
                    <div class="offcanvas-header flex">
                        <img src="../imgens/train-logo.ico" width="70px">
                        <a class="nav-link active" aria-current="page" href="#">
                            <h1>train<br>.info</h1>
                        </a>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

                            <li class="nav-item align-items-center">
                                <a class="nav-link flex col-5" href="../TelaLogin/dashboard.php"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="25" height="auto" fill="currentColor" class="bi bi-house-fill"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
                                        <path
                                            d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z" />
                                    </svg>
                                    <p class="col-8 align-items-center ">Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item align-items-center">
                                <a class="nav-link flex col-5" href="../TelaRotas/TelaRotas.html"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="25" height="auto" fill="currentColor" class="bi bi-geo-alt-fill"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                                    </svg>
                                    <p class="col-8 align-items-center">Rotas</p>
                                </a>
                            </li>
                            <li class="nav-item align-items-center">
                                <a class="nav-link flex col-5" href="../Tela monitoramento/telamonitoramento.html"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="25" height="auto" fill="currentColor"
                                        class="bi bi-search" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                    </svg>
                                    <p class="col-8 align-items-center">Monitoramento</p>
                                </a>
                            </li>

                            </li>
                            <li class="nav-item align-items-center">
                                <a class="nav-link flex col-5" href="../TelaRelatório/relatorio.html"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="25" height="auto" fill="currentColor" class="bi bi-clipboard2-data-fill"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5" />
                                        <path
                                            d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5M10 7a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zm-6 4a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm4-3a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1" />
                                    </svg>
                                    <p class="align-items-center col-8">Relatórios</p>
                                </a>
                            </li>
                            <br>
                            <li class="nav-item">
                                <a class="nav-link" href="../tela login/TelaLogin.html">
                                    <h2>Entrar</h2>
                                </a>
                            </li>
                        </ul>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

    </header>

    <main>
        <section id="dashboard">
            <div class="conteiner">
                <div class="conteudo">
                    <h3 class="p-1">Visão Geral</h3>


                </div>
                <div class="conteudo flex-wrap p-4">
                    <div class=" flex-wrap">
                        <!-- informações trens ativos -->
                        <div class="ativos">
                            <p><strong>Trens ativos</strong></p>
                            <p>Transporte:</p>
                            <div class="flex">

                                <p id="transporte"></p>
                            </div>

                            <p>Carga:</p>
                            <div class="flex">
                                <p id="carga"></p>
                            </div>
                        </div>
                        <div class="grafico">
                            <canvas id="trens-ativos"></canvas>
                        </div>
                    </div>

                    <!-- informações status -->
                    <div class=" flex-wrap">
                        <div class="status">
                            <p><strong>Status</strong></p>
                            <p>Funcionando:</p>
                            <div class="flex">
                                <p id="funcionando"></p>
                            </div>

                            <a href="#monitoramento" style="text-decoration: none;">
                                <p>Em manutenção:</p>
                            </a>
                            <div class="flex">
                                <p id="manutencao"></p>
                            </div>

                        </div>
                        <div class="grafico">
                            <canvas id="status"></canvas>
                        </div>
                    </div>
                </div>

                <!--tabela de horarios-->
                <div class="conteudo horario">
                    <h3>Horários</h3>
                    <div class="flex justify-content-around">

                        <table class="tabela-horarios table table-striped-columns metade">
                            <thead>
                                <tr>
                                    <th scope="col">n° trem</th>
                                    <th scope="col">saída</th>
                                    <th scope="col">chegada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex flex-col align-items-start">
                            <p><strong>Trens em atraso</strong></p>
                            <p>[id do trem]</p>
                            <p>[id do trem]</p>
                            <p>[id do trem]</p>
                            <p>[id do trem]</p>




                        </div>
                    </div>
                </div>
                <div class="conteudo">
                    <h3>Alertas</h3>
                    <div class="alertas flex align-items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="auto" fill="white"
                            class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                        </svg>

                        <p>Trilho fora de curso - Linha 0063</p>
                    </div>
                </div>
            </div>




        </section>
    </main>

    <footer>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- <-- Agora primeiro -->
    <script src="/Tela principal/dados.js"></script> <!-- <-- Agora depois -->
    <script src="TelaPrincipal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
        </script>
</body>

</html>