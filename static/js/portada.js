
function createGridFrutas(frutas) {

    var maincontainer = document.getElementById('idmain-portada');

    var nfrutas = frutas.length;
    for (var i=0; i<nfrutas; i++) {

        var gridItem = document.createElement('section');
        gridItem.className = 'grid-item';

        var figure = document.createElement('figure');

        var a1 = document.createElement('a');
        a1.href = "producto.php?fruta=" + frutas[i].id;

        var img = document.createElement('img');
        img.src = frutas[i].img1;

        a1.appendChild(img);
        figure.appendChild(a1);

        var figcaption = document.createElement('figcaption');

        var a2 = document.createElement('a');
        a2.href = "producto.php?fruta=" + frutas[i].id;

        var linktext = document.createTextNode(frutas[i].marca);

        a2.appendChild(linktext);
        figcaption.appendChild(a2);

        gridItem.appendChild(figure);
        gridItem.appendChild(figcaption);

        maincontainer.appendChild(gridItem);
    }
}