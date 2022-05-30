
function openForm()
{
    document.getElementById("id-container-comentarios").style.display = "block";
    document.getElementById("id-boton-comentarios").style.display = "none";
}

function closeForm()
{
    document.getElementById("id-container-comentarios").style.display = "none";
    document.getElementById("id-boton-comentarios").style.display = "block";
}

function addComment(nombre, comentario)
{
    var fecha = new Date();
    nombre = nombre + ". " + fecha.getDate() + "/" + fecha.getMonth() + "/" + fecha.getFullYear() + ". " + fecha.getHours() + ":" + fecha.getMinutes();
    document.getElementById("id-nombre-fecha").style.fontWeight = "bold";
    document.getElementById("id-nombre-fecha").innerHTML = nombre;
    document.getElementById("id-comentario").innerHTML = comentario;
    document.getElementById("id-formulario-comentarios").reset();
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
function comprobarEmail(email)
{
    if (!/^\w+((\.|-)\w+)*@\w+(\.\w{2,4})+$/.test(email)) {
        alert("Dirección de correo electrónico inválida");
        document.getElementById("id-email").value = "";
    }
}

// Palabras censuradas y asteriscos correspondientes
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

function censurarComentario(comentario)
{
    for (var censurada of censuradas) {
        comentario = comentario.replace(censurada,"*".repeat(censurada.length));
    }

    // document.getElementById("id-comentario").value = comentario;
    
    document.forms["formulario"]["comentario"].value = comentario;
}

function createListComentarios(comentarios) {

    var containercomentarios = document.getElementById('lista-comentarios');

    var ncomentarios = comentarios.length;
    for (var i=0; i<ncomentarios; i++) {

        var liItem = document.createElement('li');
        liItem.className = 'li-comentarios';

        var nombrefecha = document.createElement('span');
        nombrefecha.innerHTML = comentarios[i].usuario + ". " + comentarios[i].fecha;

        var comentario = document.createElement('p');
        comentario.innerHTML = comentarios[i].comentario;

        liItem.appendChild(nombrefecha);
        liItem.appendChild(comentario);

        containercomentarios.appendChild(liItem);
    }
}