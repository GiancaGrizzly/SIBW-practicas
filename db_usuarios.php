<?php

    require_once("db_frutas.php");


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


    function get_usuario($nombre) {

        $usuarios = get_all_usuarios();
        
        for ($i = 0; $i < sizeof($usuarios); $i++) {
        
            if ($usuarios[$i]['nombre'] === $nombre) {
            
                return $usuarios[$i];
            }
        }
        
        return 0;
    }

?>