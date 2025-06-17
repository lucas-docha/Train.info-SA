window.onload = function () {


    // Rota 1
    const nidR1 = 289;
    const mR1 = "carvão";
    const sR1 = "em andamento";
    const nR1 = 77;

    document.getElementById("infoid1").innerHTML = `${nidR1}`;
    document.getElementById("info1").innerHTML = `${mR1} - ${sR1} - ${nR1}`;

    // Rota 2
    const nidR2 = 989;
    const mR2 = "cascalho";
    const sR2 = "finalizada";
    const nR2 = 90;

    document.getElementById("infoid2").innerHTML = `${nidR2}`;
    document.getElementById("info2").innerHTML = `${mR2} - ${sR2} - ${nR2}`;

    // Rota 3
    const nidR3 = 1893;
    const mR3 = "arroz";
    const sR3 = "finalizada";
    const nR3 = 567;

    document.getElementById("infoid3").innerHTML = `${nidR3}`;
    document.getElementById("info3").innerHTML = `${mR3} - ${sR3} - ${nR3}`;

}

//ver mapa
function vernomapa() {
    window.location.href = "https://www.google.com.br/maps?hl=pt-BR";
}


//validação de cor de status da rota

const elementos = document.querySelectorAll('.status-geral-cor');

elementos.forEach(p => {
    const texto = p.textContent.toLowerCase();

    if (texto.includes('bom')) {
        p.style.color = '#c1ff72';
    } else if (texto.includes('ótimo')) {
        p.style.color = 'green';
    } else if (texto.includes('médio')) {
        p.style.color = '#f8bc29';
    } else if (texto.includes('ruim')) {
        p.style.color = 'red';
    }
});


