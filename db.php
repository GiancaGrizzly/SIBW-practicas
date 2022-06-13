<?php

const host = "mysql";
const username = "gianca";
const password = "1234";
const dbname = "SIBW";

$mysqli = null;

/*
 * Función para mostrar mensajes tipo alert desde php
 */
function alert($msg) {

    echo "<script type='text/javascript'>alert('$msg');</script>";
}

/*
 * Se conecta a la base de datos en caso de no haberse conectado ya
 */
function connect_db() {

    global $mysqli;

    if ($mysqli == null) {

        $mysqli = new mysqli(host, username, password, dbname);
        if ($mysqli->connect_errno) {

            echo("Fallo al conectar: " . $mysqli->connect_errno);
        }
    }
    return $mysqli;
}

/*
 * Devuelve la fruta con id $idFruta
 */
function get_fruta($idFruta) {

    $mysqli = connect_db();

    $stmt_get_fruta = $mysqli->prepare("SELECT * FROM frutas WHERE id = ?");
    $stmt_get_fruta->bind_param('i', $idFruta);
    $stmt_get_fruta->execute();

    $query_fruta = $stmt_get_fruta->get_result();

    if ($query_fruta->num_rows > 0) {

        $row = $query_fruta->fetch_assoc();

        $stmt_get_imagen = $mysqli->prepare("SELECT * FROM imagenes WHERE fruta = ?");
        $stmt_get_imagen->bind_param('i', $idFruta);
        $stmt_get_imagen->execute();

        $query_imagen = $stmt_get_imagen->get_result();
        $imagen1 = $query_imagen->fetch_assoc();
        $imagen2 = $query_imagen->fetch_assoc();

        $imagenes = array("img1"=>$imagen1, "img2"=>$imagen2);

        $fruta = array("id"=>$row['id'], "nombre"=>$row['nombre'], "marca"=>$row['marca'], "precio"=>$row['precio'], "descripcion"=>$row['descripcion'], "imagenes"=>$imagenes);
    }
    else {

        $fruta = array("id"=>"-1", "nombre"=>"nombredefecto", "marca"=>"marcadefecto", "precio"=>0, "descripcion"=>"descripciondefecto", "img1"=>"static/images/frutas-castilla.jpeg", "img2"=>"static/images/frutas-castilla.jpeg");
    }

    return $fruta;
}

/*
 * Devuelve un array con todas las frutas
 */
function get_all_frutas() {

    $mysqli = connect_db();

    $myquery = $mysqli->query("SELECT id, marca FROM frutas");

    $frutas = array();

    while ($row = $myquery->fetch_assoc()) {

        $query_imagen = $mysqli->query("SELECT ruta FROM imagenes WHERE fruta=" . $row['id']);
        $imagen = $query_imagen->fetch_assoc();

        array_push($frutas, ["id"=>$row['id'], "marca"=>$row['marca'], "ruta"=>$imagen['ruta']]);
    }

    return $frutas;
}

/*
 * Devuelve el usuario con nombre $nombre
 */
function get_usuario($nombre) {

    $mysqli = connect_db();

    $stmt_get_usuario = $mysqli->prepare("SELECT nombre, password, rol, email FROM usuarios WHERE nombre = ?");
    $stmt_get_usuario->bind_param('s', $nombre);
    $stmt_get_usuario->execute();

    $query_usuario = $stmt_get_usuario->get_result();

    if ($query_usuario->num_rows > 0) {

        $row = $query_usuario->fetch_assoc();

        $usuario = array("nombre"=>$row['nombre'], "password"=>$row['password'], "rol"=>$row['rol'], "email"=>$row['email']);

        return $usuario;
    }
    else return 0;
}

/*
 * Registra un nuevo usuario en la base de datos
 */
function insert_usuario($nombre, $hash, $email, $rol) {

    $mysqli = connect_db();

    $stmt_insert_usuario = $mysqli->prepare("INSERT INTO usuarios (nombre, password, email, rol) VALUES (?, ?, ?, ?);");
    $stmt_insert_usuario->bind_param('ssss', $nombre, $hash, $email, $rol);
    $stmt_insert_usuario->execute();
}

/*
 * Actualiza los datos de un usuario
 */
function update_usuario($usuario, $nombre, $password, $email) {

    $mysqli = connect_db();

    $stmt_get_usuario = $mysqli->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt_get_usuario->bind_param('s', $usuario);
    $stmt_get_usuario->execute();

    $query_usuario = $stmt_get_usuario->get_result();
    $usuario_db = $query_usuario->fetch_assoc();

    $errores = [];

    if ($usuario != $nombre) {

        if (exist('nombre', $nombre)) {

            $errores[] = "Ya existe un usuario con ese nombre.";
        }
    }

    if ($usuario_db['email'] != $email) {

        if (exist('email', $email)) {

            $errores[] = "Ya existe un usuario con ese correo electrónico.";
        }
    }

    if (empty($errores)) {

        if ($password != "********") {

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $update = 1;
        }
        else{
            $hash = $usuario_db['password'];
        }

        $stmt_update_usuario = $mysqli->prepare("UPDATE usuarios SET nombre=?, password=?, email=? WHERE id=?;");
        $stmt_update_usuario->bind_param('sssi', $nombre, $hash, $email, $usuario_db['id']);
        $stmt_update_usuario->execute();
    }

    return $errores;
}

/*
 * Comprueba login
 */
function check_login($nombre, $password) {

    $usuario = get_usuario($nombre);

    if ($usuario != 0) {

        if (password_verify($password, $usuario['password'])) {

            return true;
        }
    }
    return false;
}

/*
 * Comprueba si existe un usuario con el campo $field igual a $value
 */
function exist($field, $value) {

    $mysqli = connect_db();

    $stmt_check = $mysqli->prepare("SELECT * FROM usuarios WHERE " . $field . " = ?");
    $stmt_check->bind_param('s',$value);
    $stmt_check->execute();

    $query_check = $stmt_check->get_result();

    if ($query_check->num_rows > 0) {

        return true;
    }
    return false;
}

/*
 * Devuelve un array con todos los comentarios
 */
function get_all_comentarios($idFruta) {

    $mysqli = connect_db();

    $stmt_get_comentarios = $mysqli->prepare("SELECT id, usuario, comentario, fecha, editado FROM comentarios WHERE fruta=?");
    $stmt_get_comentarios->bind_param('i', $idFruta);
    $stmt_get_comentarios->execute();

    $query_comentarios = $stmt_get_comentarios->get_result();

    $comentarios = array();

    while ($row = $query_comentarios->fetch_assoc()) {

        array_push($comentarios, ["id"=>$row['id'], "usuario"=>$row['usuario'], "comentario"=>$row['comentario'], "fecha"=>$row['fecha'], "editado"=>$row['editado']]);
    }

    return $comentarios;
}

/*
 * Registra un nuevo comentario en la fruta $fruta hecho por $usuario
 */
function insert_comentario($fruta, $usuario, $comentario) {

    $mysqli = connect_db();

    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');

    $stmt_insert_usuario = $mysqli->prepare("INSERT INTO comentarios (fruta, usuario, comentario, fecha) VALUES (?, ?, ?, ?);");
    $stmt_insert_usuario->bind_param('isss', $fruta, $usuario, $comentario, $fecha);
    $stmt_insert_usuario->execute();
}

/*
 * Actualiza el comentario con id $idComentario
 */
function update_comentario($idComentario, $comentario) {

    $mysqli = connect_db();

    $stmt_update_comentario = $mysqli->prepare("UPDATE comentarios SET comentario=?, editado=true WHERE id=?;");
    $stmt_update_comentario->bind_param('si', $comentario, $idComentario);
    $stmt_update_comentario->execute();

    alert("Comentario actualizado con éxito.");
}

/*
 * Elimina el comentario con id $idComentario
 */
function delete_comentario($idComentario) {

    $mysqli = connect_db();

    $stmt_update_comentario = $mysqli->prepare("DELETE FROM comentarios WHERE id=?;");
    $stmt_update_comentario->bind_param('i', $idComentario);
    $stmt_update_comentario->execute();

    alert("Comentario eliminado con éxito.");
}

/*
 * Añade un nuevo producto al catálogo
 */
function insert_producto($fruta, $usuario, $comentario) {

    $mysqli = connect_db();

    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i');

    $stmt_insert_usuario = $mysqli->prepare("INSERT INTO comentarios (fruta, usuario, comentario, fecha) VALUES (?, ?, ?, ?);");
    $stmt_insert_usuario->bind_param('isss', $fruta, $usuario, $comentario, $fecha);
    $stmt_insert_usuario->execute();
}

/*
 * Actualiza el producto con id $idProducto
 */
function update_producto($fruta, $nombre, $marca, $precio, $descripcion, $imagen1, $imagen2) {

    $mysqli = connect_db();

    $errores = [];

    if ($imagen1['name'] != "") {

        $errores = check_error_imagen($imagen1);
    }

    if ($imagen2['name'] != "") {

        $errores = check_error_imagen($imagen2);
    }

    if (empty($errores)) {

        $stmt_update_producto = $mysqli->prepare("UPDATE frutas SET nombre=?, marca=?, precio=?, descripcion=? WHERE id=?;");
        $stmt_update_producto->bind_param('ssdsi', $nombre, $marca, $precio, $descripcion, $fruta['id']);
        $stmt_update_producto->execute();

        if ($imagen1['name'] != "") update_imagen($mysqli, $fruta['imagenes']['img1']['id'], $imagen1);
        if ($imagen2['name'] != "") update_imagen($mysqli, $fruta['imagenes']['img2']['id'], $imagen2);
    }

    return $errores;
}

/*
 * Elimina el producto con id $idProducto
 */
function delete_producto($idProducto) {

    $mysqli = connect_db();

    $stmt_update_comentario = $mysqli->prepare("DELETE FROM frutas WHERE id=?;");
    $stmt_update_comentario->bind_param('i', $idProducto);
    $stmt_update_comentario->execute();

    alert("Producto eliminado con éxito.");
}

/*
 * Comprueba que no haya errores con la imagen subida
 */
$extensions = array("jpeg", "jpg", "png");
function check_error_imagen($imagen) {

    global $extensions;
    $errores = [];
    $file_ext = strtolower(end(explode('.', $imagen['name'])));
    if (in_array($file_ext, $extensions) === false) {

        $errores[] = "Extension no permitida --> solo JPEG o PNG";
    }

    if ($imagen['size'] > 2097152) {	// 2MB

        $errores[] = "Tamaño del archivo demasiado grande";
    }

    return $errores;
}

/*
 * Actualiza la imagen con id $idImagen
 */
function update_imagen($mysqli, $idImagen, $imagen) {

    $file_path = "static/images/" . $imagen['name'];
    if (!file_exists($file_path)) {

        move_uploaded_file($imagen['tmp_name'], $file_path);
    }

    $stmt_update_imagen = $mysqli->prepare("UPDATE imagenes SET ruta=? WHERE id=?;");
    $stmt_update_imagen->bind_param('si', $file_path, $idImagen);
    $stmt_update_imagen->execute();
}

?>