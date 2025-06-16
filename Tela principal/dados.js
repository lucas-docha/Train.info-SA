window.onload = function () {
// trens ativos

    // dados
    const trans = 262;
    const carga = 68;

    // mostrar no html
    document.getElementById("transporte").innerText = `${trans}`
    document.getElementById("carga").innerText = `${carga}`

    //grafico
    const Ativos = document.getElementById('trens-ativos');

    new Chart(Ativos, {
        type: 'pie',
        data: {
            labels: ['Transporte', 'Carga'],
            datasets: [{
                data: [trans, carga],
                borderColor: '#2e3356',
                borderWidth: 1,
                backgroundColor: [
                    '#6ce5e8',
                    '#41b8d5'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

// status

    // dados
    const funcionando = 318;
    const manutencao = 12;

    // html
    document.getElementById("funcionando").innerText = `${funcionando}`
    document.getElementById("manutencao").innerText = `${manutencao}`
    // grafico
    const Status = document.getElementById('status');

    new Chart(Status, {
        type: 'pie',
        data: {
            labels: ['Funcionando', 'Em manutenção'],
            datasets: [{
                data: [funcionando, manutencao],
                borderColor: '#2e3356',
                borderWidth: 1,
                backgroundColor: [
                    '#6ce5e8',
                    '#41b8d5'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
};

