<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("db_frutas.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $frutas = get_all_frutas();

    echo $twig->render('portada.html', ['frutas' => $frutas]);

?>