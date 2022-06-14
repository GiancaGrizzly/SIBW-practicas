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

        $fruta = array("id"=>$row['id'], "nombre"=>$row['nombre'], "marca"=>$row['marca'], "precio"=>$row['precio'], "descripcion"=>$row['descripcion'], "publicado"=>$row['publicado'], "img1"=>$row['img1'], "img2"=>$row['img2']);
    }
    else {

        $fruta = array("id"=>"-1", "nombre"=>"nombredefecto", "marca"=>"marcadefecto", "precio"=>0, "descripcion"=>"descripciondefecto", "publicado"=>"0", "img1"=>"static/images/frutas-castilla.jpeg", "img2"=>"static/images/frutas-castilla.jpeg");
    }

    return $fruta;
}

/*
 * Devuelve un array con todas las frutas
 */
function get_all_frutas() {

    $mysqli = connect_db();

    $myquery = $mysqli->query("SELECT id, marca, publicado, img1 FROM frutas");

    $frutas = array();

    while ($row = $myquery->fetch_assoc()) {

        array_push($frutas, ["id"=>$row['id'], "marca"=>$row['marca'], "publicado"=>$row['publicado'], "img1"=>$row['img1']]);
    }

    return $frutas;
}

/*
 * Devuelve un array con las frutas que coinciden con el patrón $search
 */
function get_search_frutas($search) {

    $mysqli = connect_db();

//    $stmt_etiquetas = $mysqli->prepare("SELECT fruta FROM etiquetas WHERE etiqueta LIKE '%' + ? + '%'");
    $stmt_etiquetas = $mysqli->prepare("SELECT etiqueta, fruta FROM etiquetas");
//    $stmt_etiquetas->bind_param('s', $search);
    $stmt_etiquetas->execute();
    $query_etiquetas = $stmt_etiquetas->get_result();

    $etiquetas = array();
    while ($row = $query_etiquetas->fetch_assoc()) {

        if (strpos($row['etiqueta'], $search) !== false) {

            if (!in_array($row['fruta'], $etiquetas)) $etiquetas[] = $row['fruta'];
        }
    }

    $frutas = array();
    foreach ($etiquetas as $e) {

        $query_frutas = $mysqli->query("SELECT id, marca, publicado FROM frutas WHERE id=$e");
        $row = $query_frutas->fetch_assoc();
        array_push($frutas, ["id"=>$row['id'], "marca"=>$row['marca'], "publicado"=>$row['publicado']]);
    }

    return $frutas;
}

/*
 * Añade un nuevo producto al catálogo
 */
function insert_producto($nombre, $marca, $precio, $descripcion, $publicado, $imagen1, $imagen2) {

    $mysqli = connect_db();

    $errores = [];

    if ($imagen1['name'] != "") {

        $errores[] = check_error_imagen($imagen1);
    }
    else $errores[] = array("Introduzca imagen 1.");

    if ($imagen2['name'] != "") {

        $errores[] = check_error_imagen($imagen2);
    }
    else $errores[] = array("Introduzca imagen 2.");

    if (empty($errores[0]) && empty($errores[1])) {

        $img1_path = "static/images/" . $imagen1['name'];
        upload_imagen($imagen1, $img1_path);
        $img2_path = "static/images/" . $imagen2['name'];
        upload_imagen($imagen2, $img2_path);

        $stmt_insert_fruta = $mysqli->prepare("INSERT INTO frutas (nombre, marca, precio, descripcion, publicado, img1, img2) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $stmt_insert_fruta->bind_param('ssdsiss', $nombre, $marca, $precio, $descripcion, $publicado, $img1_path, $img2_path);
        $stmt_insert_fruta->execute();
    }

    return $errores;
}

/*
 * Actualiza la fruta $fruta
 */
function update_producto($fruta, $nombre, $marca, $precio, $descripcion, $publicado, $imagen1, $imagen2) {

    $mysqli = connect_db();

    $errores = [];

    if ($imagen1['name'] != "") {

        $errores = check_error_imagen($imagen1);
    }

    if ($imagen2['name'] != "") {

        $errores = check_error_imagen($imagen2);
    }

    if (empty($errores)) {

        if ($imagen1['name'] != "" && $imagen2['name'] != "") {

            $img1_path = "static/images/" . $imagen1['name'];
            upload_imagen($imagen1, $img1_path);
            $img2_path = "static/images/" . $imagen2['name'];
            upload_imagen($imagen2, $img2_path);

            $stmt_update_producto = $mysqli->prepare("UPDATE frutas SET nombre=?, marca=?, precio=?, descripcion=?, publicado=?, img1=?, img2=? WHERE id=?;");
            $stmt_update_producto->bind_param('ssdsissi', $nombre, $marca, $precio, $descripcion, $publicado, $img1_path, $img2_path, $fruta['id']);
        }
        elseif ($imagen1['name'] != "") {

            $img1_path = "static/images/" . $imagen1['name'];
            upload_imagen($imagen1, $img1_path);

            $stmt_update_producto = $mysqli->prepare("UPDATE frutas SET nombre=?, marca=?, precio=?, descripcion=?, publicado=?, img1=? WHERE id=?;");
            $stmt_update_producto->bind_param('ssdsisi', $nombre, $marca, $precio, $descripcion, $publicado, $img1_path, $fruta['id']);
        }
        elseif ($imagen2['name'] != "") {

            $img2_path = "static/images/" . $imagen2['name'];
            upload_imagen($imagen2, $img2_path);

            $stmt_update_producto = $mysqli->prepare("UPDATE frutas SET nombre=?, marca=?, precio=?, descripcion=?, publicado=?, img2=? WHERE id=?;");
            $stmt_update_producto->bind_param('ssdsisi', $nombre, $marca, $precio, $descripcion, $publicado, $img2_path, $fruta['id']);
        }
        else {

            $stmt_update_producto = $mysqli->prepare("UPDATE frutas SET nombre=?, marca=?, precio=?, descripcion=?, publicado=? WHERE id=?;");
            $stmt_update_producto->bind_param('ssdsii', $nombre, $marca, $precio, $descripcion, $publicado, $fruta['id']);
        }

        $stmt_update_producto->execute();
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
 * Devuelve un array con todos los usuarios
 */
function get_all_usuarios() {

    $mysqli = connect_db();

    $stmt_get_usuarios = $mysqli->prepare("SELECT nombre, rol FROM usuarios");
    $stmt_get_usuarios->execute();

    $query_usuarios = $stmt_get_usuarios->get_result();

    $usuarios = array();

    while ($row = $query_usuarios->fetch_assoc()) {

        array_push($usuarios, ["nombre"=>$row['nombre'], "rol"=>$row['rol']]);
    }

    return $usuarios;
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
 * Sube una imagen con al servidor
 */
function upload_imagen($imagen, $file_path) {

    if (!file_exists($file_path)) {

        move_uploaded_file($imagen['tmp_name'], $file_path);
    }
}

/*
 * Devuelve todas las etiquetas de la fruta $fruta
 */
function get_etiquetas($fruta) {

    $mysqli = connect_db();

    $stmt_get_etiquetas = $mysqli->prepare("SELECT etiqueta FROM etiquetas WHERE fruta = ?");
    $stmt_get_etiquetas->bind_param('i', $fruta);
    $stmt_get_etiquetas->execute();

    $query_etiquetas = $stmt_get_etiquetas->get_result();

    $etiquetas = array();

    while ($row = $query_etiquetas->fetch_assoc()) {

        $etiquetas[] = $row['etiqueta'];
    }

    return $etiquetas;
}

/*
 * Inserta etiquetas para la fruta $fruta
 */
function insert_etiquetas($fruta, $etiquetas) {

    $mysqli = connect_db();

    foreach ($etiquetas as $e) {

        if ($e != "") {

            $stmt_insert_etiqueta = $mysqli->prepare("INSERT INTO etiquetas (etiqueta, fruta) VALUES (?, ?);");
            $stmt_insert_etiqueta->bind_param('si', $e, $fruta);
            $stmt_insert_etiqueta->execute();
        }
    }
}

?>