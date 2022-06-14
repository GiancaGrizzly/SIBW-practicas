
function openForm(idForm) {

    document.getElementById(idForm).style.display = "block";
}

function closeForm(idForm) {

    document.getElementById(idForm).style.display = "none";
}

/* direccion_email = usuario + @ + servidor + dominio
 * usuario
 *   comienza por al menos una letra o número \w+
 *   seguida de 0 o más combinaciones de punto o guión y letras o números ((\.|-)\w+)*
 * servidor
 *   al menos una letra o número \w+
 * dominio
 *   comienza por punto \.
 *   le siguen 2, 3 o 4 letras \w{2,3,4}
 *   además, puede haber varios dominios (\.\w{2,3,4})+
 */
function comprobarEmail(email) {

    if (!/^\w+((\.|-)\w+)*@\w+(\.\w{2,4})+$/.test(email)) {
        alert("Dirección de correo electrónico inválida");
        document.getElementById("id-email").value = "";
    }
}

// Palabras censuradas
const censuradas = [
    "tonto",
    "tonta",
    "estúpido",
    "estúpida",
    "gilipollas",
    "imbécil",
    "mierda",
    "puto",
    "puta",
    "caraculo"
];
function censurarComentario(comentario, formulario, textArea) {

    for (var censurada of censuradas) {
        comentario = comentario.replace(censurada,"*".repeat(censurada.length));
    }

    document.forms[formulario][textArea].value = comentario;
}

function editbuttonfunction(id) {
    openForm('id-formulario-update');
    document.forms["formulario-update"]["updateComentario"].value = document.getElementById("c" + id).innerHTML;
    document.getElementsByName("idComentario")[0].value = id;
}

function createEtiqueta() {

    var etiqueta = document.createElement('input');
    etiqueta.type = 'text';
    etiqueta.name = Math.random().toString();

    document.getElementById('id-nuevas-etiquetas').appendChild(etiqueta);
}