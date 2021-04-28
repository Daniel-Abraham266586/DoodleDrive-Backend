var idUsuarioActual;
var idCarpetaActual = 0;
var datosUsuario;
var almacenamiento;
var continuar = false;
var elemento = null;

function leerCookie(nombre)
{
    var lista = document.cookie.split(";");
    
    for (i in lista)
    {
        var busca = lista[i].search(nombre);
        if (busca > -1)
        {
            micookie=lista[i]
        }
    }
    
    var igual = micookie.indexOf("=");
    var valor = micookie.substring(igual+1);
    
    return valor;
}

function accederUsuario()
{
    idUsuarioActual = leerCookie('idUsuario');

    axios(
        {
            url: 'api/usuarios.php?id='+idUsuarioActual,
            method: 'GET',
            responseType: 'json',
        })
        .then((resp)=>
        {
            console.log('(Usuario:)', resp);
            datosUsuario = resp.data;
            document.getElementById('ptn-user').innerHTML = datosUsuario.nombreUsuario;
            obtenerDatosUsuario();
        }
        )
        .catch((error)=>{console.error('(Axios)', error);});
}
accederUsuario();

function cerrarSesion()
{
    let opcion = document.getElementById('slct-NE').value;

    if(opcion == 0)
    {
        location.href = "logout.php"
    }
}

function obtenerDatosUsuario()
{
    if(idUsuarioActual!="" && idUsuarioActual!=null)
    {
        console.log(idUsuarioActual);

                axios(
                    {
                        url: 'api/archivos.php?idUsuario='+idUsuarioActual,
                        method: 'GET',
                        responseType: 'json',
                    })
                    .then((resp)=>
                    {
                        console.log('(Axios)', resp);
                        almacenamiento = resp.data;
                        
                        continuar = true;
                        mostrarDirectorio(idCarpetaActual);
                    }
                    )
                    .catch((error)=>{console.error('(Axios)', error);});
    }
    else
    {
        alert("Error Inesperado");
    }
}

    function mostrarDirectorio(idCarpeta)
    {
        idCarpetaActual = idCarpeta;
        console.log("IDCA: ", idCarpetaActual);

        document.getElementById('contenido').innerHTML = "";

        let carpetaActual = "";
        for(let i=0; i<almacenamiento.carpetas.length; i++)
        {
            if(almacenamiento.carpetas[i].idCarpeta == idCarpeta)
            {
                carpetaActual = almacenamiento.carpetas[i];
                showDireccion(carpetaActual.idCarpeta, carpetaActual.nombreCarpeta)
            }
        }

        // Carpetas
        for(let i=0; i<almacenamiento.carpetas.length; i++)
        {
            agregar = false;
            for(let j=0; j<carpetaActual.carpetas.length; j++)
            {
                if(almacenamiento.carpetas[i].idCarpeta == carpetaActual.carpetas[j])
                {
                    agregar = true;
                }
            }

            //console.log (jQuery.inArray(almacenamiento.archivos[i], carpetaActual.Archivos));

            if(agregar)
            {
                let tocar = `onclick="mostrarDirectorio(${almacenamiento.carpetas[i].idCarpeta})"`;
                document.getElementById('contenido').innerHTML +=
                `
                <div class="row pointerOver">
                    <div class="col-4" ${tocar}><span><i class="fas fa-folder"> </i> ${almacenamiento.carpetas[i].nombreCarpeta}</span></div>
                    <div class="col-2" ${tocar}><span> ${datosUsuario.nombreUsuario}</span></div>
                    <div class="col-3" ${tocar}><span> ${almacenamiento.carpetas[i].fecha}</span></div>
                    <div class="col-2" ${tocar}><span> -</span></div>
                    <div class="col-1"><center><button class="btn btn-outline-danger btn-sm" onclick="deleteCarpeta(${almacenamiento.carpetas[i].idCarpeta})"><i class="fas fa-trash-alt"></i></button></center></div>
                </div>
                <hr>
                `;
            }
        }

        // Archivos
        for(let i=0; i<almacenamiento.archivos.length; i++)
        {
            agregar = false;
            for(let j=0; j<carpetaActual.Archivos.length; j++)
            {
                if(almacenamiento.archivos[i].idArchivo == carpetaActual.Archivos[j])
                {
                    agregar = true;
                }
            }

            //console.log (jQuery.inArray(almacenamiento.archivos[i], carpetaActual.Archivos));

            if(agregar)
            {
                document.getElementById('contenido').innerHTML +=
                `
                <div class="row" onclick="">
                    <div class="col-4"><span><i class="fas fa-file-word"> </i> ${almacenamiento.archivos[i].nombreArchivo}</span></div>
                    <div class="col-2"><span> ${datosUsuario.nombreUsuario}</span></div>
                    <div class="col-3"><span> ${almacenamiento.archivos[i].fecha}</span></div>
                    <div class="col-2"><span> ${almacenamiento.archivos[i].size}</span></div>
                    <div class="col-1"><center><button class="btn btn-outline-danger btn-sm" onclick="deleteArchivo(${almacenamiento.archivos[i].idArchivo})"><i class="fas fa-trash-alt"></i></button></center></div>
                </div>
                <hr>
                `;
            }
        }
    }

function mostrarDestacados()
{
    document.getElementById('contenido').innerHTML = "";
    
    let agregar;

    for(let i=0; i<almacenamiento.archivos.length; i++)
        {
            agregar = false;
            for(let j=0; j<almacenamiento.destacados.length; j++)
            {
                if(almacenamiento.archivos[i].idArchivo == almacenamiento.destacados[j])
                {
                    agregar = true;
                }
            }

            if(agregar)
            {
                document.getElementById('contenido').innerHTML +=
                `
                <div class="row" onclick="">
                    <div class="col-3"><span> ${almacenamiento.archivos[i].nombreArchivo}</span></div>
                    <div class="col-3"><span> ${datosUsuario.nombreUsuario}</span></div>
                    <div class="col-3"><span> ${almacenamiento.archivos[i].fecha}</span></div>
                    <div class="col-3"><span> ${almacenamiento.archivos[i].size}</span></div>
                </div>
                <hr>
                `;
            }
        }
}

function mostrar_ModalAddElement()
{
    $('#modal-addElement').modal('show');
}

function addElement()
{
    if(elemento == 0)
    {
        mostrar_ModalAddCarpeta();
    }
    else if(elemento == 1)
    {
        if(datosUsuario.tipoUsuario=="Free")
        {
            if(almacenamiento.archivos.length<datosUsuario.limiteArchivos)
            {
                mostrar_ModalAddArchivo();
            }
            else
            {
                alert("Ha superado su cuota maxima de almacenamiento")
            }
        }
        else if (datosUsuario.tipoUsuario=="Premium")
        {
            mostrar_ModalAddArchivo();
        }
    }
    
    $('#modal-addElement').modal('hide');
}

function mostrar_ModalAddArchivo()
{
    document.getElementById('txt-NombreArchivoNuevo').value = '';
    document.getElementById('txt-URLArchivoNuevo').value = '';
    $('#modal-addArchivo').modal('show');
}

function addArchivo()
{
    let nombreNuevo = document.getElementById('txt-NombreArchivoNuevo').value;
    let urlNuevo = document.getElementById('txt-URLArchivoNuevo').value;

    if(nombreNuevo!='' && urlNuevo!='')
    {
        let date = new Date();
        let fechaActual = date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear();

      axios(
        {
            url: 'api/archivos.php',
            method: 'POST',
            responseType: 'json',
            data:
            {
                tipo: "archivo",
                idUsuario: idUsuarioActual,
                idCarpeta: idCarpetaActual,
                nombreArchivo: nombreNuevo,
                size: "26 mb",
                fecha: fechaActual,
                tipoArchivo: "TXT",
                url: urlNuevo
            }
        })
        .then((resp)=>
            {
                console.log('(Archivo Nuevo:)', resp);

                obtenerDatosUsuario();
                $('#modal-addArchivo').modal('hide');
            })
        .catch((error)=>{console.error('(Axios)', error);});
    }
}


function mostrar_ModalAddCarpeta()
{
    document.getElementById('txt-NombreCarpetaNueva').value = '';
    $('#modal-addCarpeta').modal('show');
}

function addCarpeta()
{
    let nombreNuevo = document.getElementById('txt-NombreCarpetaNueva').value;

    if(nombreNuevo!='')
    {
        let date = new Date();
        let fechaActual = date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear();

        console.log("IDCA ADD: ", idCarpetaActual);

      axios(
        {
            url: 'api/archivos.php',
            method: 'POST',
            responseType: 'json',
            data:
            {
                tipo: "carpeta",
                idUsuario: idUsuarioActual,
                idCarpetaPadre: idCarpetaActual,
                nombreCarpeta: nombreNuevo,
                fecha: fechaActual
            }
        })
        .then((resp)=>
            {
                console.log('(Carpeta Nuevo:)', resp);
                obtenerDatosUsuario();
                $('#modal-addCarpeta').modal('hide');
            })
        .catch((error)=>{console.error('(Axios)', error);});
    }
}

function deleteArchivo(id)
{
    console.log(id);

    axios(
        {
            url: 'api/archivos.php',
            method: 'DELETE',
            responseType: 'json',
            data:
            {
                tipo: "archivo",
                idUsuario: idUsuarioActual,
                idCarpeta: idCarpetaActual,
                idArchivo: id
            }
        })
        .then((resp)=>
            {
                console.log('(Archivo Eliminado:)', resp);
                obtenerDatosUsuario();
            })
        .catch((error)=>{console.error('(Axios)', error);});
}

function deleteCarpeta(id)
{
    console.log("eliminar", id);

    axios(
        {
            url: 'api/archivos.php',
            method: 'DELETE',
            responseType: 'json',
            data:
            {
                tipo: "carpeta",
                idUsuario: idUsuarioActual,
                idCarpetaPadre: idCarpetaActual,
                idCarpeta: id
            }
        })
        .then((resp)=>
            {
                console.log('(Carpeta Eliminada:)', resp);
                obtenerDatosUsuario();
                $('#modal-addCarpeta').modal('hide');
            })
        .catch((error)=>{console.error('(Axios)', error);});
}

function seleccionarOpcion()
{
    elemento = document.getElementById('slct-NE').value;
}

function showDireccion(idCarpeta, nombreCarpeta)
{
    let direccion = document.getElementById('lbl-direccion').innerHTML;

    if(direccion.indexOf(nombreCarpeta) < 0)
    {
        elemento = document.getElementById('lbl-direccion').innerHTML += 
        `
            <b class="pointerOver" onclick="mostrarDirectorio(${idCarpeta})">${" > "  + nombreCarpeta}</b> 
        `;
    }
    else if(direccion.indexOf(nombreCarpeta) >= 0)
    {
        let final = direccion.indexOf(nombreCarpeta) + nombreCarpeta.length;

        elemento = document.getElementById('lbl-direccion').innerHTML = direccion.substring(0, final);
    }

}