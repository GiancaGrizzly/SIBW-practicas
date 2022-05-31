<?php

require_once "/usr/local/lib/php/vendor/autoload.php";
require_once "db.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!exist('nombre', $nombre) && !exist('email', $email)) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        insert_usuario($nombre, $hash, $email, "Usuario");

        session_start();

        $_SESSION['nombre'] = $nombre;
    }
    else {
        alert("Error. Ya existe un usuario con ese nombre o correo.");
    }

    if (!headers_sent()) {

        header("Location: index.php");
    }

}

echo $twig->render('registro.html', []);

?>