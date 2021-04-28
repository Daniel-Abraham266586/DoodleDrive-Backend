var correoUsuario = "";
var passwordUsuario = "";

var datosResultado = null;
var datosUsuario = null;

function loginCorreo()
{
    correoUsuario = "";
    passwordUsuario = ""

    document.getElementById('pnl-login').innerHTML = 
    `
    <center>
        <img width="227" height="27" src="img/Google_2015_logo.svg"><br><br>
        <h4 style="text-align: center">Iniciar Sesión</h4>
        <a id="lgn-Drive" href="index.html" style="text-decoration: none;">Ir a Google Drive</a>
    </center>
    <br><input type="text" id="txtLogin-correo" class="form-control my-2 py-4" placeholder="Correo electrónico o teléfono">
    <a href="">¿Has olvidado tu correo electrónico?</a><br>

    <br><a class="lbl-texto-gris" style="font-size: 0.8rem;">¿No es tu ordenador? Usa el modo invitados para iniciar sesión de forma privada. </a>
    <a href="" style="font-size: 0.8rem;">Más información</a>
    <br>
    <br>
    <div>
        <button type="button" href="" class="btn btn-light btn-transparente azul-google" onclick="crearCuenta()">Crear Cuenta</button>
        <button type="button" id="" class="btn btn-primary my-2 my-sm-0 ml-4" style="float: right;" onclick="loginPassword()">Siguiente</button>
    </div>
    `;
}
loginCorreo();

function loginPassword()
{
    correoUsuario = document.getElementById('txtLogin-correo').value;

    if(correoUsuario!="" && correoUsuario!=null)
    {
        document.getElementById('pnl-login').innerHTML = 
        `
        <center>
            <img width="227" height="27" src="img/Google_2015_logo.svg"><br><br>
            <h4 style="text-align: center">Te damos la Bienvenida</h4>
            <select id="slctLogin-Usuario" class="form-control" onchange="loginCambiarUsuario()">
                <option value="1">${correoUsuario}</option>
                <option value="0">No soy yo</option>
            </select>
        </center>
        <br><input type="password" id="txtLogin-password" class="form-control my-2 py-4" placeholder="Ingresa tu contraseña">
        <input type="checkbox" id="chckbx-mostrarPassword" class="" onchange="showPassword()"><label for="chckbx-mostrarPassword"><span> Mostrar contraseña</span></label><br>            
        <br>
        <br>
        <div>
            <button type="button" href="" class="btn btn-light btn-transparente azul-google" onclick="loginCorreo()">Atras</button>
            <button type="button" id="" class="btn btn-primary my-2 my-sm-0 ml-4" style="float: right;" onclick="login()">Siguiente</button>
        </div>
        `;
    }
    else
    {
        alert("Ingrese el correo");
    }
}

function login()
{
    passwordUsuario = document.getElementById('txtLogin-password').value;

    if(correoUsuario!="" && passwordUsuario!="" || correoUsuario!=null && passwordUsuario!=null)
    {
        //console.log(correoUsuario);
        //console.log(passwordUsuario);

        axios(
            {
                url: 'api/usuarios.php',
                method: 'POST',
                responseType: 'json',
                data:
                {
                    tipo: "login",
                    correo: correoUsuario,
                    password: passwordUsuario
                }
            })
            .then((resp)=>
            {
                console.log('(Axios)', resp);
                datosResultado = resp.data.datosResultado;
                datosUsuario = resp.data.datosUsuario;

                if(datosResultado.codigoResultado == 1)
                {
                    location.href = "home.php"
                }
                else
                {
                    alert("Datos incorrectos");
                }
            }
            )
            .catch((error)=>{console.error('(Axios)', error);});
    }
    else
    {
        alert("Ingrese la contraseña");
    }
}

function loginCambiarUsuario()
{
    if(document.getElementById('slctLogin-Usuario').value == 0)
    {
        loginCorreo();
    }
}

function showPassword()
{
    if(document.getElementById('chckbx-mostrarPassword').checked)
    {
        document.getElementById('txtLogin-password').type = 'text';
    }
    else
    {
        document.getElementById('txtLogin-password').type = 'password';
    }   
}

function crearCuenta()
{
    correoUsuario = "";
    passwordUsuario = ""

    document.getElementById('pnl-login').innerHTML = 
    `
                <center>
                    <img width="227" height="27" src="img/Google_2015_logo.svg"><br><br>
                    <h4 style="text-align: center">Crear Cuenta</h4>
                </center>

                <br>
                <span>Nombre de Usuario:</span>
                <br><input type="text" id="txtSigin-nombre" class="form-control my-2 py-4" placeholder="Nombre de Usuario"><br>

                <span>Correo electronico:</span>
                <br><input type="text" id="txtSigin-correo" class="form-control my-2 py-4" placeholder="Correo electrónico o teléfono"><br>
                
                <span>Ingrese la contraseña:</span>
                <br><input type="password" id="txtSigin-Cotraseña" class="form-control my-2 py-4" placeholder="Contraseña"><br>
                
                <span>confirme la contraseña:</span>
                <br><input type="password" id="txtSigin-ConfirmarCotraseña" class="form-control my-2 py-4" placeholder="Contraseña"><br>
            
                <span>Tipo de cuenta:</span>
                <br><select name="" id="slct-tipoUsuario" class="form-control">
                    <option value="Free">Free</option>
                    <option value="Premium">Premium</option>
                </select><br>
                <br>
                <div>
                <button type="button" href="" class="btn btn-light btn-transparente azul-google" onclick="loginCorreo()">Atras</button>
                    <button type="button" id="" class="btn btn-primary my-2 my-sm-0 ml-4" style="float: right;" onclick="sigin()">Sigin</button>
                </div>
    `;
}

function sigin()
{
    let nombreUser = document.getElementById('txtSigin-nombre').value;
    let correoUser = document.getElementById('txtSigin-correo').value;
    let pass1 = document.getElementById('txtSigin-Cotraseña').value;
    let pass2 = document.getElementById('txtSigin-ConfirmarCotraseña').value;
    let tipoUser = document.getElementById('slct-tipoUsuario').value;

        if(nombreUser!="" && correoUser!="" && pass1!="" && pass2!="" && tipoUser!="")
        {
            if(isValidEmail(correoUser))
            {
                if(pass1 == pass2)
                {
                    let date = new Date();
                    let fechaActual = date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear();
                    axios(
                    {
                        url: 'api/usuarios.php',
                        method: 'POST',
                        responseType: 'json',
                        data:
                        {
                            tipo: "signup",
                            nombreUsuario: nombreUser,
                            correo: correoUser,
                            password: pass1,
                            tipoUsuario: tipoUser,
                            fecha: fechaActual
                        }
                    })
                    .then((resp)=>
                    {
                        console.log('(Axios)', resp);
                        datosResultado = resp.data.datosResultado;
                        datosUsuario = resp.data.datosUsuario;
        
                        if(datosResultado.codigoResultado == 1)
                        {
                            location.href = "home.php"
                        }
                        else
                        {
                            alert("Datos incorrectos");
                        }
                    }
                    )
                    .catch((error)=>{console.error('(Axios)', error);});
                }
                else
                {
                    alert("Las contraseñas no son iguales");    
                }
            }
            else
            {
                alert("Correo no valido");
            }
        }
        else
        {
            alert("Ingrese todos los datos solicitados");
        }
}

function isValidEmail(mail)
{ 
    return /^\w+([\.\+\-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail); 
  }