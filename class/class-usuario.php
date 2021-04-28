<?php
    class Usuario
    {
        private $idUsuario;
        private $nombreUsuario;
        private $correo;
        private $password;
        private $tipoUsuario;
        private $limiteArchivos;

        public function __construct($idUsuario, $nombreUsuario, $correo, $password, $tipoUsuario, $limiteArchivos)
        {
            $this->idUsuario = $idUsuario;
            $this->nombreUsuario = $nombreUsuario;
            $this->correo = $correo;
            $this->password = $password;
            $this->tipoUsuario = $tipoUsuario;
            $this->limiteArchivos = $limiteArchivos;
        }


        public static function obtenerUsuarios()
        {
            $contenidoArchivo = file_get_contents('../data/usuarios.json');
            echo $contenidoArchivo;
        }

        public static function obtenerUsuario($id)
        {
            $contenidoArchivo = file_get_contents('../data/usuarios.json');
            $usuarios = json_decode($contenidoArchivo, true);

            for($i=0; $i<sizeof($usuarios); $i++)
            {
                if($usuarios[$i]["idUsuario"] == $id)
                {
                    $usuario = $usuarios[$i];
                    return $usuario;
                    break;
                }
            }
            return null;
        }

        public static function verificarUsuario($email, $password)
        {
            $contenidoArchivo = file_get_contents('../data/usuarios.json');
            $usuarios = json_decode($contenidoArchivo, true);

            for($i=0; $i<sizeof($usuarios); $i++)
            {
                if($usuarios[$i]["correo"]==$email && $usuarios[$i]["correo"]==$email)
                {
                    return $usuarios[$i];
                    break;
                }
            }

            return null;
        }

        public static function asignarIdUsuario()
        {
            $contenidoArchivo = file_get_contents('../data/usuarios.json');
            $usuarios = json_decode($contenidoArchivo, true);

            $aleatorio = -1;

            
                    $tomar = false;

                    do{
                        $aleatorio = rand(0, sizeof($usuarios)+1);
                        $tomar = false;

                        for($j=0; $j<sizeof($usuarios); $j++)
                        {
                            if($usuarios[$j]["idUsuario"] == $aleatorio)
                            {
                                $tomar = true;
                                break;
                            }
                        }

                    }while($tomar);
            

            return $aleatorio;
        }

        public static function addUsuario($nombreUsuario, $correo, $password, $tipoUsuario, $fecha)
        {
            $contenidoArchivo = file_get_contents('../data/usuarios.json');
            $usuarios = json_decode($contenidoArchivo, true);
            
            $idUsuario = Usuario::asignarIdUsuario();

            $limiteArchivos = 0;
            if($tipoUsuario=="Free")
            {
                $limiteArchivos = 10;
            }
                    
                $user = array(
                    "idUsuario"=> $idUsuario,
                    "nombreUsuario"=> $nombreUsuario,
                    "correo"=> $correo,
                    "password"=> $password,
                    "tipoUsuario"=> $tipoUsuario,
                    "limiteArchivos"=> $limiteArchivos
                );

                $usuarios[] = $user;

                $archivo = fopen('../data/usuarios.json', 'w');
                fwrite($archivo, json_encode($usuarios));
                fclose($archivo);



            // Creamos el Almacenamiento:
            $contenidoArchivoAlmacenamiento = file_get_contents('../data/almacenamiento.json');
            $files = json_decode($contenidoArchivoAlmacenamiento, true);

            $carpetas[] = array(
                "idCarpeta"=>0,
                "nombreCarpeta"=>"Mi Unidad",
                "fecha"=> $fecha,
                "carpetas"=> array(),
                "Archivos"=> array()
            );

            $archivos[] = array(
                "idArchivo"=> 0,
                "nombreArchivo"=> "Bienvenida",
                "size"=>"26 mb",
                "fecha"=>  $fecha,
                "tipoArchivo"=> "TXT",
                "url"=> ""  
            );

            $files[] = array(
                "idUsuario"=> $idUsuario,
                "carpetas"=> $carpetas,
                "archivos"=> $archivos,
                "destacados"=> array(),
                "papelera"=> array()
            );
              
            $archivo = fopen('../data/almacenamiento.json', 'w');
            fwrite($archivo, json_encode($files));
            fclose($archivo);

            return array("codigo"=>"1", "respuesta"=>"usuario guardado");
        }

        public function getIdUsuario()
        {
            return $this->idUsuario;
        }

        public function setIdUsuario($idUsuario)
        {
            $this->idUsuario = $idUsuario;

            return $this;
        }

        public function getNombreUsuario()
        {
            return $this->nombreUsuario;
        }

        public function setNombreUsuario($nombreUsuario)
        {
            $this->nombreUsuario = $nombreUsuario;

            return $this;
        }

        public function getCorreo()
        {
            return $this->correo;
        }

        public function setCorreo($correo)
        {
            $this->correo = $correo;

            return $this;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function setPassword($password)
        {
            $this->password = $password;

            return $this;
        }

        public function getTipoUsuario()
        {
            return $this->tipoUsuario;
        }

        public function setTipoUsuario($tipoUsuario)
        {
            $this->tipoUsuario = $tipoUsuario;

            return $this;
        }

        public function getLimiteArchivos()
        {
            return $this->limiteArchivos;
        }

        public function setLimiteArchivos($limiteArchivos)
        {
            $this->limiteArchivos = $limiteArchivos;

            return $this;
        }
    }
?>