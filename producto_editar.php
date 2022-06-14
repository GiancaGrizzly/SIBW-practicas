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

        $errores = update_producto($variablesTwig['fruta'], $_POST['nombre'], $_POST['marca'], $_POST['precio'], $_POST['descripcion'], $_FILES['img1'], $_FILES['img2']);

        if (empty($errores)) {

            $variablesTwig['fruta'] = get_fruta($_SESSION['fruta']);

            alert("Producto actualizado con éxito.");
        }
        else {
            $variablesTwig['errores'] = $errores;
        }
    }
    elseif (isset($_POST['submit-delete-producto'])) {

        delete_producto($_SESSION['fruta']);

        echo "<script> window.location.href = 'index.php'</script>";
    }
    elseif (isset($_POST['submit-etiquetas'])) {

        insert_etiquetas($_SESSION['fruta'], $_POST);

        alert("Etiquetas añadidas con éxito.");
    }
}

$variablesTwig['etiquetas'] = get_etiquetas($_SESSION['fruta']);

//    if (empty($errores)) {
//
//        echo (json_encode(['status'=>"success", 'fruta'=>$variablesTwig['fruta']]));
//    }
//    else echo (json_encode($errores));
//}
//elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
//
//    delete_producto($_SESSION['fruta']);
//
//    if (!headers_sent()) {
//
//        header("Location: index.php");
//    }
//}
//else echo $twig->render('producto_editar.html', $variablesTwig);

echo $twig->render('producto_editar.html', $variablesTwig);

?>