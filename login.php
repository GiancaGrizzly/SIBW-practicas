<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("db_usuarios.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre = $_POST['nombre'];
        $password = $_POST['password'];
        
        if (check_login($nombre, $password)) {
        
            session_start();
            
            $_SESSION['nombre'] = $nombre;
        }
        
        header("Location: index.php");	// redirecciona a la pagina ***
        
        exit();
    }

    echo $twig->render('login.html', []);

?>