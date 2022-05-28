<?php

    require_once("db_frutas.php");

    function alert($msg) {

        echo "<script type='text/javascript'>alert('$msg');</script>";
    }


    function get_all_usuarios() {

        $mysqli = conectarBD();
        if ($mysqli->connect_errno) {

            echo("Fallo al conectar: " . $mysqli->connect_errno);
        }

        $myquery = $mysqli->query("SELECT nombre, password, rol, email FROM usuarios");

        $usuarios = array();

        while ($row = $myquery->fetch_assoc()) {

            array_push($usuarios, ["nombre" => $row['nombre'], "password" => $row['password'], "rol" => $row['rol'], "email" => $row['email']]);
        }

        return $usuarios;
    }


    function check_login($nombre, $password) {

        $usuarios = get_all_usuarios();
        
        for ($i = 0; $i < sizeof($usuarios); $i++) {
        
            if ($usuarios[$i]['nombre'] === $nombre) {

                // echo ($usuarios[$i]['password']);
            
                if (password_verify($password, $usuarios[$i]['password'])) {
                
                    return true;
                }
            }
        }
        
        return false;
    }


    function check_nombre($nombre) {

        $usuarios = get_all_usuarios();

        for ($i = 0; $i < sizeof($usuarios); $i++) {

            if ($usuarios[$i]['nombre'] === $nombre) {

                return false;
            }
        }

        return true;
    }


    function check_email($email) {

        $usuarios = get_all_usuarios();

        for ($i = 0; $i < sizeof($usuarios); $i++) {

            if ($usuarios[$i]['email'] === $email) {

                return false;
            }
        }

        return true;
    }


    function get_usuario($nombre) {

        $usuarios = get_all_usuarios();
        
        for ($i = 0; $i < sizeof($usuarios); $i++) {
        
            if ($usuarios[$i]['nombre'] === $nombre) {
            
                return $usuarios[$i];
            }
        }
        
        return 0;
    }


    function insert_usuario($nombre, $hash, $email, $rol) {

        $mysqli = conectarBD();
        if ($mysqli->connect_errno) {

            echo("Fallo al conectar: " . $mysqli->connect_errno);
        }

        $stmt_insert_usuario = $mysqli->prepare("INSERT INTO usuarios (nombre, password, email, rol) VALUES (?, ?, ?, ?);");
        $stmt_insert_usuario->bind_param('ssss', $nombre, $hash, $email, $rol);
        $stmt_insert_usuario->execute();
    }

?>