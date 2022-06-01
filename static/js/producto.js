
function openForm(idForm) {

    document.getElementById(idForm).style.display = "block";
}

function closeForm(idForm) {

    document.getElementById(idForm).style.display = "none";
}

function addComment(nombre, comentario) {

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

function createListComentarios(comentarios, rol_usuario) {

    var containerComentarios = document.getElementById('lista-comentarios');

    var ncomentarios = comentarios.length;
    for (var i=0; i<ncomentarios; i++) {

        var liItem = document.createElement('li');
        liItem.className = 'li-comentarios';

        var nombrefecha = document.createElement('p');
        nombrefecha.style.fontWeight = 'bold';
        if (comentarios[i].editado) {

            nombrefecha.innerHTML = comentarios[i].usuario + ". " + comentarios[i].fecha + " - [Editado]";
        }
        else {
            nombrefecha.innerHTML = comentarios[i].usuario + ". " + comentarios[i].fecha;
        }

        if (rol_usuario == "Moderador") {

            var editButton = document.createElement('button');
            editButton.id = comentarios[i].id;
            editButton.type = 'button';
            editButton.onclick = function () {
                openForm('id-formulario-update');
                document.forms["formulario-update"]["updateComentario"].value = document.getElementById("c" + this.id).innerHTML;
                document.getElementsByName("idComentario")[0].value = this.id;
            }
            editButton.innerHTML = "Editar";

            nombrefecha.appendChild(editButton);
        }

        var comentario = document.createElement('p');
        comentario.id = "c" + comentarios[i].id;
        comentario.innerHTML = comentarios[i].comentario;

        liItem.appendChild(nombrefecha);
        liItem.appendChild(comentario);

        containerComentarios.appendChild(liItem);
    }
}