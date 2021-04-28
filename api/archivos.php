<?php
    session_start();
    
    header("Content-Type: application/json");
    include_once("../class/class-archivo.php");

    if(!isset($_SESSION["token"]))
    {
        exit;
    }

    if(!isset($_COOKIE["token"]))
    {
        exit;
    }

    if($_SESSION["token"] != $_COOKIE["token"])
    {
        exit;
    }

    $_POST = json_decode(file_get_contents('php://input'), true);

    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'POST':

            $respuesta = array("codigo"=>"0", "respuesta"=>"error");

            if($_POST["tipo"]=="archivo" || $_POST["tipo"]=="ARCHIVO")
            {
                if(isset($_POST["idUsuario"], $_POST["idCarpeta"], $_POST["nombreArchivo"], $_POST["size"], $_POST["fecha"], $_POST["tipoArchivo"], $_POST["url"]))
                {
                    $respuesta = Archivo::guardarArchivo($_POST["idUsuario"], $_POST["idCarpeta"], $_POST["nombreArchivo"], $_POST["size"], $_POST["fecha"], $_POST["tipoArchivo"], $_POST["url"]);
                }
            }
            else if($_POST["tipo"]=="carpeta" || $_POST["tipo"]=="CARPETA")
            {
                if(isset($_POST["idUsuario"], $_POST["idCarpetaPadre"], $_POST["nombreCarpeta"], $_POST["fecha"]))
                {
                    $respuesta = Archivo::guardarCarpeta($_POST["idUsuario"], $_POST["idCarpetaPadre"], $_POST["nombreCarpeta"], $_POST["fecha"]);
                }
            }

            echo json_encode($respuesta);
        break;
        
        case 'GET':
            if (isset($_GET['idUsuario'], $_GET['idArchivo']))
            {
                $almacenamiento = Archivo::obtenerArchivo($_GET['idUsuario'], $_GET['idArchivo']);
            }
            else if (isset($_GET['idUsuario']))
            {
                $almacenamiento = Archivo::obtenerArchivos($_GET['idUsuario']);
            }

            echo json_encode($almacenamiento);
        break;
        
        case 'PUT':
            //Actualizar
        break;
        
        case 'DELETE':
            
            $respuesta = array("codigo"=>"0", "respuesta"=>"error");

            if($_POST["tipo"]=="archivo" || $_POST["tipo"]=="ARCHIVO")
            {
                if(isset($_POST["idUsuario"], $_POST["idCarpeta"], $_POST["idArchivo"]))
                {
                    $respuesta = Archivo::removeArchivo($_POST["idUsuario"], $_POST["idCarpeta"], $_POST["idArchivo"]);
                }
            }
            else if($_POST["tipo"]=="carpeta" || $_POST["tipo"]=="CARPETA")
            {
                if(isset($_POST["idUsuario"], $_POST["idCarpetaPadre"], $_POST["idCarpeta"]))
                {
                    $respuesta = Archivo::removeCarpeta($_POST["idUsuario"], $_POST["idCarpetaPadre"], $_POST["idCarpeta"]);
                }
            }

            echo json_encode($respuesta);

        break;
    }
?>