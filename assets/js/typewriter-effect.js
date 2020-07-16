$(function () {

    $('.intro-heading').hide();
    typeWriter($('.intro-lead-in'), false, 2500); //false para não retirar a frase, 2500 p/ o setTimeout.

    setTimeout(() => {
        $('.intro-heading').show();
        typeWriter($('.intro-heading'), false, 2500);
    }, 1000);

});

function removeCaracter(elem) {

    const fraseArray = elem.html().split('');
    let fraseNova = '';
    let copyArray = fraseArray.slice();

    fraseArray.forEach((letra, i) => {
        setTimeout(function () {
            copyArray.pop();
            fraseNova = copyArray.join('');
            elem.html(fraseNova);
        }, 15 * i)

    });
    setTimeout(() => {
        elem.html(fraseArray.join('')).hide();
    }, 1500)
}

/**
* Efeito máquina de escrever
* Criador: Origamid
*/
function typeWriter(elem, retirar = false, seg) {
    elem.delay(600).slideDown('slow');
    const textoArray = elem.html().split('');
    elem.html('');
    let frase = '';
    textoArray.forEach((letra, i) => {
        setTimeout(function () {
            frase += letra;
            elem.html(frase);
        }, 75 * i)
    });

    if (retirar == true) {//Verifica se retira a frase.
        setTimeout(() => {
            removeCaracter(elem);
        }, seg);
    }

}
