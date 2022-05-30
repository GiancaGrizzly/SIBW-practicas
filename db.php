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

        $stmt_get_imagen = $mysqli->prepare("SELECT path FROM imagenes WHERE fruta = ?");
        $stmt_get_imagen->bind_param('i', $idFruta);
        $stmt_get_imagen->execute();

        $query_imagen = $stmt_get_imagen->get_result();
        $imagen = $query_imagen->fetch_assoc();

        $fruta = array("id"=>$row['id'], "nombre"=>$row['nombre'], "marca"=>$row['marca'], "precio"=>$row['precio'], "descripcion"=>$row['descripcion'], "path"=>$imagen['path']);
    }
    else {

        $fruta = array("id"=>"-1", "nombre"=>"nombredefecto", "marca"=>"marcadefecto", "precio"=>0, "descripcion"=>"descripciondefecto", "path"=>"static/images/granada.jpeg");
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

        $query_imagen = $mysqli->query("SELECT path FROM imagenes WHERE fruta=" . $row['id']);
        $imagen = $query_imagen->fetch_assoc();

        array_push($frutas, ["id"=>$row['id'], "marca"=>$row['marca'], "path"=>$imagen['path']]);
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
function check_if_not_exists($field, $value) {

    $mysqli = connect_db();

    $stmt_check = $mysqli->prepare("SELECT * FROM usuarios WHERE " . $field . " = ?");
    $stmt_check->bind_param('s',$value);
    $stmt_check->execute();

    $query_check = $stmt_check->get_result();

    if ($query_check->num_rows > 0) {

        return false;
    }
    return true;
}
/*
 * Devuelve un array con todos los comentarios
 */
function get_all_comentarios($idFruta) {

    $mysqli = connect_db();

    $stmt_get_comentarios = $mysqli->prepare("SELECT usuario, comentario, fecha FROM comentarios WHERE fruta=?");
    $stmt_get_comentarios->bind_param('i', $idFruta);
    $stmt_get_comentarios->execute();

    $query_comentarios = $stmt_get_comentarios->get_result();

    $comentarios = array();

    while ($row = $query_comentarios->fetch_assoc()) {

        array_push($comentarios, ["usuario"=>$row['usuario'], "comentario"=>$row['comentario'], "fecha"=>$row['fecha']]);
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

?>