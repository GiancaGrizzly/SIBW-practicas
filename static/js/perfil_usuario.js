$(document).ready(function () {

    IDbotonperfil.onclick = guardar_cambios;
});

function guardar_cambios() {

    formData = {
        nombre: $("#nombre").val(),
        email: $("#email").val(),
        password: $("#password").val(),
    };

    $.ajax({
        url: "perfil_usuario.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (respuesta) {

            document.getElementById("errores").innerHTML = '';

            if (respuesta == "success") {

                $("#nombre").html = respuesta['nombre'];
                $("#email").html = respuesta['email'];

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