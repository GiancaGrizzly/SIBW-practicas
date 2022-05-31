<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$variablesTwig = [];

session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (update_usuario($_SESSION['nombre'], $_POST['nombre'], $_POST['password'], $_POST['email'])) {

        $_SESSION['nombre'] = $_POST['nombre'];
    }
}

$variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);

echo $twig->render('perfil_usuario.html', $variablesTwig);

?>