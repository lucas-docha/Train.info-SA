window.onload = function () {
    const grfcAtv = document.getElementById('trens-ativos');

    new Chart(grfcAtv, {
        type: 'pie',
        data: {
            labels: ['Trasnporte', 'Carga'],
            datasets: [{
                data: [14, 86],
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

    const grfcAtv2 = document.getElementById('trens-ativos2');

    new Chart(grfcAtv2, {
        type: 'pie',
        data: {
            labels: ['Trasnporte', 'Carga'],
            datasets: [{
                data: [14, 86],
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
}
