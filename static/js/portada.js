$(document).ready(function () {

    idboton.onclick = search;
});

function search() {

    var formData = new FormData(document.getElementById("id-busqueda"));

    $.ajax({
        url: "index.php",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (respuesta) {

            alert(respuesta);

            // var hits = document.getElementById("hits");
            // hits.style.display = "block";
            //
            // for (var fruta in respuesta) {
            //
            //     var li = document.createElement('li');
            //     var a = document.createElement('a');
            //     a.href = "producto.php?fruta=" + fruta.id;
            //     a.innerHTML = fruta.marca;
            //
            //     li.appendChild(a);
            //     hits.appendChild(li);
            // }
        },
        error: function (error, exception) {
            alert("error\n" + exception);
        }
    });
}