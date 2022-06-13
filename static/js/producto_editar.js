$(document).ready(function () {

    IDupdateproducto.onclick = update_producto;
    // IDdeleteproducto.onclick = delete_producto;
});

function update_producto() {

    var formData = new FormData(document.getElementById("id-formulario-editar"));

    $.ajax({
        url: "producto_editar.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        enctype: "multipart/form-data",
        cache: false,
        success: function (respuesta) {

            document.getElementById("errores").innerHTML = '';

            if (respuesta.status == "success") {

                // $("#imagen1").attr('src', respuesta.fruta.imagenes.img1.ruta);
                // $("#imagen2").src = respuesta.fruta.imagenes.img2.ruta;

                alert("Producto actualizado con éxito.");
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