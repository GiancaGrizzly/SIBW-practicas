$(document).ready(function () {

    IDbotonregistro.onclick = registrar;
});

function registrar() {

    if (!checkEmail($("#email").val())) {

        alert("Dirección de correo electrónico inválida");
    }
    else {

        var formData = new FormData(document.getElementById("id-formulario-registro"));

        $.ajax({
            url: "registro.php",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (respuesta) {

                if (respuesta == "success") {

                    alert("Usuario registrado con éxito.")

                    window.location.assign("index.php");
                }
                else alert("Error. Ya existe un usuario con ese nombre o correo.");
            }
        });
    }
}

function checkEmail(email) {

    return /^\w+((\.|-)\w+)*@\w+(\.\w{2,4})+$/.test(email);
}