 /**
 * MASCARA TELEFONE
 * Criador: William Bruno
 */
 function mascara(o,f){
 	v_obj=o
 	v_fun=f
 	setTimeout("execmascara()",1)
 }
 function execmascara(){
 	v_obj.value=v_fun(v_obj.value)
 }
 function mtel(v){
    v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

/*Function of loading*/
function load(action) {
    var load_div = $('.ajax_load');
    if (action === 'open') {
        load_div.fadeIn().css('display', 'flex');
    } else {
        load_div.fadeOut();
    }
}