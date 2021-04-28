<?php
    session_start();

    header("Content-Type: application/json");
    include_once("../class/class-usuario.php");

    $_POST = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'POST':
            $resultado = array(
                "codigoResultado"=> -1,
                "mensaje"=> "Vacio"
            );
            
            if($_POST["tipo"]=="login" || $_POST["tipo"]=="LOGIN")
            {
                $usuario = Usuario::verificarUsuario($_POST["correo"], $_POST["password"]);
            
                if($usuario)
                {
                    $resultado = array(
                        "codigoResultado"=> 1,
                        "mensaje"=> "Usuario autenticado",
                        "token"=> sha1(uniqid(rand(), true))
                    );
                    $_SESSION["token"] = $resultado["token"];

                    setcookie("token", $resultado["token"], time()+(60*60*24*31), "/");
                    setcookie("idUsuario", $usuario["idUsuario"], time()+(60*60*24*31), "/");

                    $respuesta["datosResultado"] = $resultado;
                    $respuesta["datosUsuario"] = $usuario;
                }
                else
                {
                    $resultado = array(
                        "codigoResultado"=> 0,
                        "mensaje"=> "Usuario Incorrecto"
                    );

                    setcookie("token", "", time()-1, "/");
                    setcookie("idUsuario", "", time()-1, "/");

                    $respuesta["datosResultado"] = $resultado;
                }
            }
            if($_POST["tipo"]=="signup" || $_POST["tipo"]=="SIGNUP")
            {
                if(isset($_POST["nombreUsuario"], $_POST["correo"], $_POST["password"], $_POST["tipoUsuario"], $_POST["fecha"]))
                {
                    Usuario::addUsuario($_POST["nombreUsuario"], $_POST["correo"], $_POST["password"], $_POST["tipoUsuario"], $_POST["fecha"]);
                }

                $usuario = Usuario::verificarUsuario($_POST["correo"], $_POST["password"]);
            
                if($usuario)
                {
                    $resultado = array(
                        "codigoResultado"=> 1,
                        "mensaje"=> "Usuario autenticado",
                        "token"=> sha1(uniqid(rand(), true))
                    );
                    $_SESSION["token"] = $resultado["token"];

                    setcookie("token", $resultado["token"], time()+(60*60*24*31), "/");
                    setcookie("idUsuario", $usuario["idUsuario"], time()+(60*60*24*31), "/");

                    $respuesta["datosResultado"] = $resultado;
                    $respuesta["datosUsuario"] = $usuario;
                }
                else
                {
                    $resultado = array(
                        "codigoResultado"=> 0,
                        "mensaje"=> "Usuario Incorrecto"
                    );

                    setcookie("token", "", time()-1, "/");
                    setcookie("idUsuario", "", time()-1, "/");

                    $respuesta["datosResultado"] = $resultado;
                }
            }

            echo json_encode($respuesta);

        break;
        
        case 'GET':
            if (isset($_GET['id']))
            {
                $usuario = Usuario::obtenerUsuario($_GET['id']);
            }
            else
            {
                $usuario = Usuario::obtenerUsuarios();
            }
            
            echo json_encode($usuario);
        break;
        
        case 'PUT':
            //Actualizar
        break;
        
        case 'DELETE':
            //Eliminar
        break;
    }
?>