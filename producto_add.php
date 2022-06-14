<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$variablesTwig = [];

session_start();

if (isset($_SESSION['nombre'])) {

    $variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errores = insert_producto($_POST['nombre'], $_POST['marca'], $_POST['precio'], $_POST['descripcion'], $_FILES['img1'], $_FILES['img2']);

    if (empty($errores[0]) && empty($errores[1])) {

        alert("Producto insertado con éxito.");
    }
    else {
        $variablesTwig['errores'] = $errores;
    }
}

echo $twig->render('producto_add.html', $variablesTwig);


?>