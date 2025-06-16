window.onload = function () {


    //dados rota1 
    const nidR1 = 289;
    const mR1 = "carvão";
    const sR1 = "em andamento";
    const nR1 = 77;

    document.getElementById("infoid1").innerHTML = `ID da Rota: ${nidR1}`
    document.getElementById("info1").innerHTML = `Material: ${mR1}, Status:  ${sR1}, Rota: ${nR1}`

    //dados rota2
    const nidR2 = 989;
    const mR2 = "cascalho";
    const sR2 = "finalizada";
    const nR2 = 90;

    document.getElementById("infoid2").innerHTML = ` ID da Rota: ${nidR2}`
    document.getElementById("info2").innerHTML = `Material: ${mR2}, Status: ${sR2}, Rota: ${nR2}`

    //dados rota3
    const nidR3 = 1893;
    const mR3 = "arroz";
    const sR3 = "finalizada";
    const nR3 = 567;

    document.getElementById("infoid3").innerHTML = ` ID da Rota: ${nidR3}`
    document.getElementById("info3").innerHTML = `Material: ${mR3}, Status: ${sR3}, Rota: ${nR3}`
}

//ver mapa
function vernomapa() {
    window.location.href = "https://www.google.com.br/maps?hl=pt-BR";
}


//rotas matuÊ
const niveis = [1, 2, 3, 4];
const nivel = 1;

const idStatus = [
    { id: 'statusTremRota', nivel: 2 },
    { id: 'statusTremRota2', nivel: 1 },
    { id: 'statusTremRota3', nivel: 3 },
    { id: 'statusTremRota4', nivel: 4 },
    { id: 'statusTremRota5', nivel: 2 },
    { id: 'statusTremRota6', nivel: 1 },
    { id: 'statusTremRota7', nivel: 3 },
    { id: 'statusTremRota8', nivel: 4 },
     { id: 'statusTremRota8', nivel: 4 },
];

idStatus.forEach(item => {
    const texto = document.getElementById(item.id);

    if (!texto) return;

    switch (item.nivel) {
        case 1:
            texto.style.color ="green";
            break;
        case 2:
            texto.style.color ="yellow";
            break;
        case 3:
            texto.style.color ="lime green #ADFF2F";
            break;
        case 4:
            texto.style.color ="red";
            break;
    }

})


