<?php

    function conectarBD() {

        $host = "mysql";
        $username = "gianca";
        $password = "1234";
        $dbname = "SIBW";

        $mysqli = new mysqli($host, $username, $password, $dbname);

        return $mysqli;
    }


    function get_all_usuarios() {

        $mysqli = conectarBD();
        if ($mysqli->connect_errno) {

            echo("Fallo al conectar: " . $mysqli->connect_errno);
        }

        $myquery = $mysqli->query("SELECT nombre, password FROM usuarios");

        $usuarios = array();

        while ($row = $myquery->fetch_assoc()) {

            array_push($usuarios, ["nombre" => $row['nombre'], "password" => $row['password']]);
        }

        return $usuarios;
    }


    function check_login($nombre, $password) {

        $usuarios = get_all_usuarios();
        
        for ($i = 0; i < sizeof($usuarios); $i++) {
        
            if ($usuarios[$i]['nombre'] === $nombre) {
            
                if (password_verify($password, $usuarios[$i]['password'])) {
                
                    return true;
                }
            }
        }
        
        return false;
    }


    function get_usuario($nombre) {

        $mysqli = conectarBD();
        if ($mysqli->connect_errno) {

            echo("Fallo al conectar: " . $mysqli->connect_errno);
        }

        $stmt_frutas = $mysqli->prepare("SELECT * FROM frutas WHERE id = ?");
        $stmt_frutas->bind_param('i', $idFruta);
        $stmt_frutas->execute();

        $myquery = $stmt_frutas->get_result();

        $fruta = array("id"=> "-1", "nombre" => "nombredefecto", "marca" => "marcadefecto", "precio" => 0, "descripcion" => "descripciondefecto", "path" => "static/images/granada.jpeg");

        if ($myquery->num_rows > 0) {

            $row = $myquery->fetch_assoc();

            $smtm_image = $mysqli->prepare("SELECT path FROM imagenes WHERE fruta = ?");
            $smtm_image->bind_param('i', $idFruta);
            $smtm_image->execute();

            $query_imagen = $smtm_image->get_result();
            $imagen = $query_imagen->fetch_assoc();

            $fruta = array("id" => $row['id'], "nombre" => $row['nombre'], "marca" => $row['marca'], "precio" => $row['precio'], "descripcion" => $row['descripcion'], "path" => $imagen['path']);
        }

        return $fruta;
    }

?>