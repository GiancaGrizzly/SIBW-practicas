<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$variablesTwig = [];

session_start();

$variablesTwig['frutas'] = get_all_frutas();

if (isset($_SESSION['nombre'])) {

    $variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $hits = get_search_frutas($_POST['search']);

    echo (json_encode($hits));
}
else echo $twig->render('portada.html', $variablesTwig);

?>