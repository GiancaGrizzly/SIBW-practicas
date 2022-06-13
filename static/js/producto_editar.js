$(document).ready(function () {

    IDupdateproducto.onclick = update_producto;
    // IDdeleteproducto.onclick = delete_producto;
});

function update_producto() {

    // var formData = new FormData(document.getElementById("id-formulario-editar"));
    var formData = new FormData();
    formData.append('nombre', $("#nombre").val());
    formData.append('marca', $("#marca").val());
    formData.append('precio', $("#precio").val());
    formData.append('descripcion', $("#descripcion").val());

    formData.append('img1', $("#img1")[0].files[0]);
    // formData.append('img2', $("#img2")[0].files[0]);

    $.ajax({
        url: "producto_editar.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        enctype: "multipart/form-data",
        success: function (respuesta) {

            document.getElementById("errores").innerHTML = '';

            if (respuesta.status == "success") {

                // $("#imagen1").src = respuesta.fruta.imagenes.img1.ruta;
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