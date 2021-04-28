<?php
    class Archivo
    {
        private $idArchivo;
        private $nombreArchivo;
        private $size;
        private $fecha;
        private $url;

        public function __construct($idArchivo, $nombreArchivo, $size, $fecha, $tipoArchivo, $url)
        {
            $this->idArchivo = $idArchivo;
            $this->nombreArchivo = $nombreArchivo;
            $this->size = $size;
            $this->fecha = $fecha;
            $this->tipoArchivo = $tipoArchivo;
            $this-$url = $url;
        }

        public static function obtenerArchivos($idUsuario)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            for($i=0; $i<sizeof($files); $i++)
            {
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    $filesUsuario = $files[$i];
                    return $filesUsuario;
                    break;
                }
            }
        }

        public static function obtenerArchivo($idUsuario, $idArchivo)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            for($i=0; $i<sizeof($files); $i++)
            {
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    $filesUsuario = $files[$i];

                    for($j=0; $j<sizeof($filesUsuario["archivos"]); $j++)
                    {
                        if($filesUsuario["archivos"][$j]["idArchivo"] == $idArchivo)
                        {
                            $file = $filesUsuario["archivos"][$j];
                            break;
                        }
                    }
                    
                    break;
                }
            }

            echo json_encode($file);
        }

        public static function asignarIdArchivo($idUsuario)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            $aleatorio = -1;

            for($i=0; $i<sizeof($files); $i++)
            {
                //Buscamos el Usuario
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    $tomar = false;

                    do{
                        $aleatorio = rand(0, sizeof($files[$i]["archivos"])+1);
                        $tomar = false;

                        for($j=0; $j<sizeof($files[$i]["archivos"]); $j++)
                        {
                            if($files[$i]["archivos"][$j]["idArchivo"] == $aleatorio)
                            {
                                $tomar = true;
                                break;
                            }
                        }

                    }while($tomar);

                    break;
                }
            }

            return $aleatorio;
        }

        public static function guardarArchivo($idUsuario, $idCarpeta, $nombreArchivo, $size, $fecha, $tipoArchivo, $url)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            for($i=0; $i<sizeof($files); $i++)
            {
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    $idArchivo = Archivo::asignarIdArchivo($idUsuario);
                    
                    $file = array(
                        "idArchivo"=> $idArchivo,
                        "nombreArchivo"=> $nombreArchivo,
                        "size"=> $size,
                        "fecha"=> $fecha,
                        "tipoArchivo"=> $tipoArchivo,
                        "url"=> $url
                    );

                    $files[$i]["archivos"][] = $file;

                    for($j=0; $j<sizeof($files[$i]); $j++)
                    {
                        if($files[$i]["carpetas"][$j]["idCarpeta"] == $idCarpeta)
                        {
                            $files[$i]["carpetas"][$j]["Archivos"][] = $idArchivo;

                            $archivo = fopen('../data/almacenamiento.json', 'w');
                            fwrite($archivo, json_encode($files));
                            fclose($archivo);
                            break;
                        }
                    }
                    break;
                }
            }

            subirArchivo();

            return array("codigo"=>"1", "respuesta"=>"guardado");
        }

        private function subirArchivo()
        {

        }

        public static function descargarArchivo()
        {

        }

        public static function asignarIdCarpeta($idUsuario)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            $aleatorio = -1;

            for($i=0; $i<sizeof($files); $i++)
            {
                //Buscamos el Usuario
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    $tomar = false;

                    do{
                        $aleatorio = rand(0, sizeof($files[$i]["carpetas"])+1);
                        $tomar = false;

                        for($j=0; $j<sizeof($files[$i]["carpetas"]); $j++)
                        {
                            if($files[$i]["carpetas"][$j]["idCarpeta"] == $aleatorio)
                            {
                                $tomar = true;
                                break;
                            }
                        }

                    }while($tomar);

                    break;
                }
            }

            return $aleatorio;
        }

        public static function guardarCarpeta($idUsuario, $idCarpetaPadre, $nombreCarpeta, $fecha)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            for($i=0; $i<sizeof($files); $i++)
            {
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    $idCarpeta = Archivo::asignarIdCarpeta($idUsuario);

                    $folder = array(
                        "idCarpeta"=> $idCarpeta,
                        "nombreCarpeta"=> $nombreCarpeta,
                        "fecha"=> $fecha,
                        "carpetas"=> array(),
                        "Archivos"=> array()
                    );

                    $files[$i]["carpetas"][] = $folder;

                    for($j=0; $j<sizeof($files[$i]); $j++)
                    {
                        if($files[$i]["carpetas"][$j]["idCarpeta"] == $idCarpetaPadre)
                        {
                            $files[$i]["carpetas"][$idCarpetaPadre]["carpetas"][] = $idCarpeta;

                            $archivo = fopen('../data/almacenamiento.json', 'w');
                            fwrite($archivo, json_encode($files));
                            fclose($archivo);
                            break;
                        }
                    }
                }
            }

            return array("codigo"=>"1", "respuesta"=>"guardado");
        }

        public static function removeArchivo($idUsuario, $idCarpeta, $idArchivo)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            for($i=0; $i<sizeof($files); $i++)
            {
                //Buscamos el Usuario
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    // Buscamos el Archivo
                    for($j=0; $j<sizeof($files[$i]["archivos"]); $j++)
                    {
                        if($files[$i]["archivos"][$j]["idArchivo"] == $idArchivo)
                        {
                            array_splice($files[$i]["archivos"], $j, 1);

                            break;
                        }
                    }

                    // Buscamos la Carpeta
                    for($j=0; $j<sizeof($files[$i]["carpetas"]); $j++)
                    {
                        if($files[$i]["carpetas"][$j]["idCarpeta"] == $idCarpeta)
                        {
                            // Buscamos la referencia al Archivo
                            for($l=0; $l<sizeof($files[$i]["carpetas"][$j]["Archivos"]); $l++)
                            {
                                if($files[$i]["carpetas"][$j]["Archivos"][$l] == $idArchivo)
                                {
                                    array_splice($files[$i]["carpetas"][$j]["Archivos"], $l, 1);
                                    //unset($files[$i]["carpetas"][$j]["Archivos"][$l]);
                                    
                                    break;
                                }
                            }
                            break;
                        }
                    }

                    break;
                }
            }

            $archivo = fopen('../data/almacenamiento.json', 'w');
            fwrite($archivo, json_encode($files));
            fclose($archivo);

            return array("codigo"=>"1", "respuesta"=>"archivo eliminado");
        }

        public static function removeCarpeta($idUsuario, $idCarpetaPadre, $idCarpeta)
        {
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            for($i=0; $i<sizeof($files); $i++)
            {
                $archivosDentroCarpeta = array();
                $carpetasDentroCarpeta = array();

                //Buscamos el Usuario
                if($files[$i]["idUsuario"] == $idUsuario)
                {
                    // Buscamos la carpeta
                    for($j=0; $j<sizeof($files[$i]["carpetas"]); $j++)
                    {
                        if($files[$i]["carpetas"][$j]["idCarpeta"] == $idCarpeta)
                        {
                            $archivosDentroCarpeta = $files[$i]["carpetas"][$j]["Archivos"];
                            $carpetasDentroCarpeta = $files[$i]["carpetas"][$j]["carpetas"];
                            array_splice($files[$i]["carpetas"], $j, 1);

                            break;
                        }
                    }

                    // Buscamos la Carpeta Padre
                    for($j=0; $j<sizeof($files[$i]["carpetas"]); $j++)
                    {
                        if($files[$i]["carpetas"][$j]["idCarpeta"] == $idCarpetaPadre)
                        {
                            // Buscamos la referencia a la Carpeta Eliminada
                            for($l=0; $l<sizeof($files[$i]["carpetas"][$j]["carpetas"]); $l++)
                            {
                                if($files[$i]["carpetas"][$j]["carpetas"][$l] == $idCarpeta)
                                {
                                    array_splice($files[$i]["carpetas"][$j]["carpetas"], $l, 1);
                                    
                                    break;
                                }
                            }
                            break;
                        }
                    }

                    // Eliminamos los Archivos dentro de la Carpeta Eliminada
                    for($j=0; $j<sizeof($files[$i]["archivos"]); $j++)
                    {
                        for($k=0; $k<sizeof($archivosDentroCarpeta); $k++)
                        {
                            if($files[$i]["archivos"][$j]["idArchivo"] == $archivosDentroCarpeta[$k])
                            {
                                array_splice($files[$i]["archivos"], $j, 1);

                                break;
                            }
                        }
                    }

                    for($j=0;$j<sizeof($files[$i]["carpetas"]); $j++)
                    {
                        for($k=0; $k<sizeof($carpetasDentroCarpeta); $k++)
                        {
                            if($files[$i]["carpetas"][$j]["idCarpeta"] == $carpetasDentroCarpeta[$k])
                            {
                                Archivo::removeCarpeta($idUsuario, $idCarpeta, $carpetasDentroCarpeta[$k]);
                            }
                        }
                    }

                    break;
                }
            }


            $archivo = fopen('../data/almacenamiento.json', 'w');
            fwrite($archivo, json_encode($files));
            fclose($archivo);

            return array("codigo"=>"1", "respuesta"=>"carpeta eliminada");
        }
        
        public function getIdArchivo()
        {
            return $this->idArchivo;
        }

        public function setIdArchivo($idArchivo)
        {
            $this->idArchivo = $idArchivo;

            return $this;
        }

        public function getNombreArchivo()
        {
            return $this->nombreArchivo;
        }

        public function setNombreArchivo($nombreArchivo)
        {
            $this->nombreArchivo = $nombreArchivo;

            return $this;
        }

        public function getTipoArchivo()
        {
            return $this->tipoArchivo;
        }

        public function setTipoArchivo($tipoArchivo)
        {
            $this->tipoArchivo = $tipoArchivo;

            return $this;
        }

        public function getUrl()
        {
            return $this->url;
        }

        public function setUrl($url)
        {
            $this->url = $url;

            return $this;
        }
    }
?>