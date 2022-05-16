<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    require_once("db_frutas.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $variablesTwig = [];

    session_start();

    if (isset($_GET['fruta'])) {

        $idFruta = $_GET['fruta'];
    }
    else {

        $idFruta = -1;
    }
    $variablesTwig['fruta'] = get_fruta($idFruta);

    echo $twig->render('producto_imprimir.html', $variablesTwig);

?>