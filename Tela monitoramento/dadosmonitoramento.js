// dados
const funcionando = 318;
const manutencao = 12;

// html
document.getElementById("funcionando").innerText = ${ funcionando }
document.getElementById("manutencao").innerText = ${ manutencao }

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