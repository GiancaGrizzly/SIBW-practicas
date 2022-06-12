<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$variablesTwig = [];

session_start();
$variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errores = update_usuario($_SESSION['nombre'], $_POST['nombre'], $_POST['password'], $_POST['email']);

    if (count($errores) == 0) {

        $_SESSION['nombre'] = $_POST['nombre'];

        echo (json_encode("success"));
    }
    else echo (json_encode($errores));
}
else {
    echo $twig->render('perfil_usuario.html', $variablesTwig);
}

?>