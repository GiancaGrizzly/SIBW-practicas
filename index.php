<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("db.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $frutas = getFrutasPortada();

    echo $twig->render('portada.html', ['frutas' => $frutas]);

?>