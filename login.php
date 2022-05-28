<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    require_once("db_usuarios.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre = $_POST['nombre'];
        $password = $_POST['password'];
        
        if (check_login($nombre, $password)) {
        
            session_start();
            
            $_SESSION['nombre'] = $nombre;
        }
        else {
            alert("Error. Usuario o contraseña fallidos.");
        }
        
        if (!headers_sent()) {

            header("Location: index.php");	// redirecciona a la pagina ***
        }
    }

    echo $twig->render('login.html', []);

?>