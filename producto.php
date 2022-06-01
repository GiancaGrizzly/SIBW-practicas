<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$variablesTwig = [];

session_start();

if (isset($_GET['fruta'])) {

    $_SESSION['fruta'] = $_GET['fruta'];
}
$variablesTwig['fruta'] = get_fruta($_SESSION['fruta']);

if (isset($_SESSION['nombre'])) {

    $variablesTwig['usuario'] = get_usuario($_SESSION['nombre']);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit-add-comentario'])) {

        insert_comentario($_SESSION['fruta'], $_SESSION['nombre'], $_POST['insertComentario']);
    }
    elseif (isset($_POST['submit-update-comentario'])) {

        update_comentario($_POST['idComentario'], $_POST['updateComentario']);
    }
    elseif (isset($_POST['submit-delete-comentario'])) {

        delete_comentario();
    }
}

$variablesTwig['comentarios'] = get_all_comentarios($_SESSION['fruta']);

echo $twig->render('producto.html', $variablesTwig);

?>