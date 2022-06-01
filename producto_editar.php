<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$variablesTwig = [];

session_start();

$variablesTwig['fruta'] = get_fruta($_SESSION['fruta']);

if (isset($_SESSION['nombre'])) {

    $variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit-update-producto'])) {

        update_producto($variablesTwig['fruta'], $_POST['nombre'], $_POST['marca'], $_POST['precio'], $_POST['descripcion'], $_FILES['imagen1'], $_FILES['imagen2']);

        $variablesTwig['fruta'] = get_fruta($_SESSION['fruta']);
    }
    elseif (isset($_POST['submit-delete-producto'])) {

        delete_producto($_SESSION['fruta']);

        if (!headers_sent()) {

            header("Location: index.php");
        }
    }
}

echo $twig->render('producto_editar.html', $variablesTwig);

?>