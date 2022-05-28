<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$variablesTwig = [];

session_start();

$variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);

echo $twig->render('perfil_usuario.html', $variablesTwig);

?>