<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("db.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (isset($_GET['fruta'])) {

        $idFruta = $_GET['fruta'];
    }
    else {

        $idFruta = -1;
    }

    $fruta = getFrutaProducto($idFruta);

    echo $twig->render('producto_imprimir.html', ['fruta' => $fruta]);

?>