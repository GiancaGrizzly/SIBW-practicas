<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

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

if (isset($_SESSION['nombre'])) {

    $variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);
}

echo $twig->render('producto.html', $variablesTwig);

?>