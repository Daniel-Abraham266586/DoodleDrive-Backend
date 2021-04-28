<?php
    session_start();
    if(!isset($_SESSION["token"]))
    {
        header("Location: login.html");
    }

    if(!isset($_COOKIE["token"]))
    {
        header("Location: login.html");
    }

    if($_SESSION["token"] != $_COOKIE["token"])
    {
        header("Location: login.html");
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Unidad</title>

    <link rel="stylesheet" href="css/all.min.css"><!--FontAwesome-->
    <link rel="stylesheet" href="css/bootstrap.min.css"/><!--Bootstrap-->
	<link rel="stylesheet" href="css/normalize.css"><!--Normalize-->
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <!--Navbarhref="#"-->
    <nav class="navbar navbar-expand-md navbar-light bg-dark fixed-top">
        <a class="navbar-brand texto-Gris" onclick=""><i class="fab fa-google-drive"> </i> Drive</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <form class="form-inline my-2 my-lg-0" style="margin-left: auto;">
                <input type="text" id="" placeholder="Buscar en Drive" class="form-control">
                <button type="button" id="" class="btn btn-invisible-redondo texto-Gris my-2 my-sm-0 ml-1 mr-1" onclick=""><i class="fas fa-cog"></i></button>
                <button type="button" id="" class="btn btn-invisible-redondo texto-Gris my-2 my-sm-0 ml-1 mr-1" onclick=""><i class="fas fa-question-circle"></i></button>
                <button type="button" id="" class="btn btn-invisible-redondo texto-Gris my-2 my-sm-0 ml-2 mr-1" onclick=""><i class="fas fa-th"></i></button>
                <select id="lista-Usuarios" class="form-control ml-4" onchange="cerrarSesion()">
                    <option id="ptn-user" value="1"></option>
                    <option value="0">Cerrar Sesión</option>
                </select>
            </form>
        </div>
    </nav>
    <!--Navbar-->

    <main class="container-fluid" style="margin-top: 5rem;">
        <div class="row">
            <div class="col-3">
                <div>
                    <button id="btn-nuevo" class="btn btn-primary shadow" onclick="mostrar_ModalAddElement()"><i class="fas fa-plus"> </i>  Nuevo</button>
                </div>
                <br>
                
                <div>
                    <a class="texto-Gris pointerOver" onclick="mostrarDirectorio(0)"><i class="fas fa-archive"> </i> Mi Unidad</a><br>
                    <a class="texto-Gris"><i class="fas fa-user-friends"> </i> Compartidos conmigo</a><br>
                    <a class="texto-Gris"><i class="far fa-clock"> </i> Recientes</a><br>
                    <a class="texto-Gris pointerOver" onclick="mostrarDestacados()"><i class="far fa-star"> </i> Destacados</a><br>
                    <a class="texto-Gris"><i class="far fa-trash-alt"> </i> Papelera</a><br>

                    <hr>

                    <span class="texto-Gris"><i class="fab fa-mixcloud"> </i> Almacenamiento</span><br>
                    <button class="btn form-control texto-Azul-medium btn-blanco-bordeAzul">Comprar Almacenamiento</button><br>
                </div>
            </div>
            
            <div class="col-9">
                <div id="panel-contenido">
                    <div>
                        <span id="lbl-direccion"></span>
                        <i class="fas fa-grip-horizontal mr-1" style="float: right;"></i>
                        <i class="fas fa-info-circle mr-3" style="float: right;"></i>
                        <hr>

                        <div class="row">
                            <div class="col-4"><span>Nombre</span></div>
                            <div class="col-2"><span>Propietario</span></div>
                            <div class="col-3"><span>Última modificación</span></div>
                            <div class="col-2"><span>Tamaño del archivo</span></div>
                            <div class="col-1"><span>Eliminar</span></div>
                        </div>
                        <hr>

                        <div id="contenido">
                        </div>
                    </div>

                    <div>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>

    

  <!-- Modal -->
  <div class="modal fade" id="modal-addArchivo" tabindex="-1" aria-labelledby="modal-addArchivo-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="modal-addArchivo-div" class="modal-header">
                <h5 id="modal-addArchivo-label" class="modal-title">Subir Archivo</h5>
            </div>
            <div id="modal-AddArchivo-body" class="modal-body">

                <br><a>Nombre Archivo: </a>
                <input type="text" id="txt-NombreArchivoNuevo" class="form-control my-3">

                <br><a>URL: </a>
                <input type="text" id="txt-URLArchivoNuevo" class="form-control my-3">
                <br>
            </div>
            <div id="modal-addArchivo-footer" class="modal-footer">
                <button id="" type="button" class="btn btn-secondary" onclick="addArchivo()">Subir</button>
            </div>
        </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modal-addCarpeta" tabindex="-1" aria-labelledby="modal-addCarpeta-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="modal-addCarpeta-div" class="modal-header">
                <h5 id="modal-addCarpeta-label" class="modal-title">Crear Carpeta</h5>
            </div>
            <div id="modal-AddCarpeta-body" class="modal-body">
                <br><a>Nombre Carpeta: </a><br>
                <input type="text" id="txt-NombreCarpetaNueva" class="form-control my-3"><br>
            </div>
            <div id="modal-addCarpeta-footer" class="modal-footer">
                <button id="" type="button" class="btn btn-secondary" onclick="addCarpeta()">Crear</button>
            </div>
        </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modal-addElement" tabindex="-1" aria-labelledby="modal-addElement-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            
            <div id="modal-AddElement-body" class="modal-body">
                <br><a>Seleccione una opción: </a><br>
                <select id="slct-NE" class="form-control" onchange="seleccionarOpcion()">
                    <option value="0">Carpeta</option>
                    <option value="1">Archivo</option>
                </select>


            </div>
            <div id="modal-addElement-footer" class="modal-footer">
                <button id="" type="button" class="btn btn-secondary" onclick="addElement()">Continuar</button>
            </div>
        </div>
    </div>
  </div>
    
    <script src="js/jquery-3.5.1.min.js"></script><!--JQuery-->
    <script src="js/popper.min.js"></script><!--Popper-->
    <script src="js/all.min.js"></script><!--FontAwesome-->
    <script src="js/bootstrap.min.js"></script><!--Bootstrap-->
    <script src="js/axios.min.js"></script><!--Axios-->
    <script src="js/controlador.js"></script>
</body>
</html>