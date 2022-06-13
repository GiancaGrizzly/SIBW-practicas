$(document).ready(function () {

    IDbotonlogin.onclick = check_login;
});

function check_login() {

    var formData = new FormData(document.getElementById("id-formulario-login"));

    $.ajax({
        url: "login.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (respuesta) {

            if (respuesta == "success") {

                window.location.assign("index.php");
            }
            else alert("Error. Usuario o contraseña incorrectos.");
        }
    });
}