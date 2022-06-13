$(document).ready(function () {

    IDbotonperfil.onclick = guardar_cambios;
});

function guardar_cambios() {

    var formData = new FormData(document.getElementById("id-formulario-perfil"));

    $.ajax({
        url: "perfil_usuario.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (respuesta) {

            document.getElementById("errores").innerHTML = '';

            if (respuesta == "success") {

                alert("Usuario actualizado con éxito.");
            }
            else print_errores(respuesta);
        }
    });
}

function print_errores(errores) {

    var containererrores = document.getElementById("errores");

    var nerrores = errores.length;
    for (var i=0; i<nerrores; i++) {

        var item = document.createElement('li');
        item.innerHTML = errores[i];
        containererrores.appendChild(item);
    }
}