$(document).ready(function () {

    IDbotonlogin.onclick = check_login;
});

function check_login() {

    formData = {
        nombre: $("#nombre").val(),
        password: $("#password").val(),
    };

    $.ajax({
        url: "login.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (respuesta) {

            if (respuesta == "success") {

                window.location.assign("index.php");
            }
            else alert("Error. Usuario o contraseña incorrectos.");
        }
    });
}