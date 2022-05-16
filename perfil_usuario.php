<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    require_once("db_usuarios.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $variablesTwig = [];

    session_start();

    if (isset($_SESSION['nombre'])) {

        $variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);
    }

    echo $twig->render('perfil_usuario.html', $variablesTwig);

?>