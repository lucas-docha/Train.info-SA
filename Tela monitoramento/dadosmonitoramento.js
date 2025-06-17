// dados
const funcionando = 4055;
const manutencao = 758;

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

//dados tabela monitoramento
const trens = [
    { id: "0079", emInspecao: "0079", finalizado: "0079", status: "green" },
    { id: "0686", emInspecao: "0686", finalizado: "0686", status: "green" },
    { id: "1984", emInspecao: "1984", finalizado: "1984", status: "green" },
    { id: "2869", emInspecao: "2869", finalizado: "2869", status: "yellow" },
    { id: "3684", emInspecao: "3684", finalizado: "3684", status: "green" },
    { id: "0079", emInspecao: "0079", finalizado: "0079", status: "yellow" },
    { id: "0686", emInspecao: "0686", finalizado: "0686", status: "red" },
    { id: "1984", emInspecao: "1984", finalizado: "1984", status: "red" },
    { id: "2869", emInspecao: "2869", finalizado: "2869", status: "green" },
    { id: "3684", emInspecao: "3684", finalizado: "3684", status: "green" },

];

//funcao tabela
function rendTabela() {
    const tbody = document.getElementById('tabela-monitoramento');
    tbody.innerHTML = '';

    trens.forEach(trem => {
        const row = tbody.insertRow();

        //coluna1
        const cell1 = row.insertCell(0);
        cell1.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-train-front me-2" viewBox="0 0 16 16">
                <path d="M5.621 1.485c1.815-.454 2.943-.454 4.758 0 .784.196 1.743.673 2.527 1.119.688.39 1.094 1.148 1.094 1.979V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V4.583c0-.831.406-1.588 1.094-1.98.784-.445 1.744-.922 2.527-1.118m5-.97C8.647.02 7.353.02 5.38.515c-.924.23-1.982.766-2.78 1.22C1.566 2.322 1 3.432 1 4.582V13.5A2.5 2.5 0 0 0 3.5 16h9a2.5 2.5 0 0 0 2.5-2.5V4.583c0-1.15-.565-2.26-1.6-2.849-.797-.453-1.855-.988-2.779-1.22ZM5 13a1 1 0 1 1-2 0 1 1 0 0 1 2 0m0 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0m7 1a1 1 0 1 0-1-1 1 1 0 1 0-2 0 1 1 0 0 0 2 0 1 1 0 0 0 1 1M4.5 5a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h3V5zm4 0v3h3a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5zM3 5.5A1.5 1.5 0 0 1 4.5 4h7A1.5 1.5 0 0 1 13 5.5v2A1.5 1.5 0 0 1 11.5 9h-7A1.5 1.5 0 0 1 3 7.5zM6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z"/>
            </svg>
            ${trem.id}
        `;

        //coluna2
        const cell2 = row.insertCell(1);
        cell2.textContent = trem.emInspecao;

        //coluna3
        const cell3 = row.insertCell(2);
        cell3.textContent = trem.finalizado;
        cell3.style.color = trem.status;
    });
}
document.addEventListener('DOMContentLoaded', rendTabela);


//aviso
const aviso = "O trem 1984 necessita de manutenção";

function atualizarAviso() {
    document.getElementById("textoAviso").textContent = aviso;
}
atualizarAviso();

