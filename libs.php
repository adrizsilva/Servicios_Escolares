<?php
//-----------------------------------------------------------------------------------------------------------------------------------------
// CONSTANTES y ARREGLOS
//-----------------------------------------------------------------------------------------------------------------------------------------

define ('CICLOACTUAL', '2020-2021');
define ('CICLOACTS', '20-1');
define ('CICLOSIGS', '20-2');
define ('CICLOACTA', '20-0');
define ('CICLONEXT', '2021-2022');
//define ('CICLOSIGS', '21-1');
define ('CICLOSIGA', '21-0');
define ('RINSC_SEM', '1');          // Variable para activar los datos de reinscripción semestral: 0 para desactivar, 1 para activar
define ('RINSC', '2');              // Variable para activar los menus de reinscripcion anual: 0 NO, 1 ANUAL, 2 SEMESTRAL
define ('ESTADOS', array(
 ['1','Aguascalientes'], ['2', 'Baja California'], ['3','Baja California Sur'], ['4','Campeche'], ['5','Coahuila'], ['6','Colima'], ['7','Chiapas'],
 ['8','Chihuahua'], ['9','Ciudad de México'], ['10','Durango'], ['11','Guanajuato'],['12','Guerrero'], ['13','Hidalgo'],['14','Jalisco'],
 ['15','México'], ['16','Michoacán'], ['17','Morelos'],['18','Nayarit'], ['19','Nuevo León'], ['20','Oaxaca'], ['21','Puebla'],
 ['22','Querétaro'], ['23','Quintana Roo'], ['24','San Luis Potosí'], ['25','Sinaloa'], ['26','Sonora'], ['27','Tabasco'], ['28','Tamaulipas'],
 ['29','Tlaxcala'], ['30','Veracruz'], ['31','Yucatán'], ['32','Zacatecas']));

define ('MAXFILESIZE', 2097152);

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

// FUNCIONES AUXILIARES AUXILIARES AUXILIARES AUXILIARES AUXILIARES AUXILIARES AUXILIARES AUXILIARES AUXILIARES AUXILIARES

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
 
 
//*************************************************************************************************
// Funcion:     nomestado()
// Descripción: Cambia el numero del estado por su nombre
// Parametros:  numero de estado
// Regresa una variable texto con el nombre del estado
//*************************************************************************************************
function nomestado($state) {
    foreach (ESTADOS as list($clave, $valor)) {
        if ($clave == $state) {
            return $valor;
        }
    }
 }

//*************************************************************************************************
// Funcion:     tipobeca($tipo)
// Descripción: Cambia la clave de la beca por la descripcion completa
// Parametros:  tipo de beca
// Regresa una variable texto con la descripción
//*************************************************************************************************
function tipobeca($tipo) {
    switch ($tipo) {
        case 'int':
            return "Interna";
            break;
        case 'sep':
            return "SEP";
            break;
        case 'hno':
            return "Hermanos";
            break;
        default:
            return "Sin definir";
    }
 }
//*************************************************************************************************
// Funcion:     Secciones
// Descripción: Cambia el numero de la sección por su nombre y/o carrera
// Parametros:  Ninguno
// Regresa una variable texto con el nombre de la sección correspondiente
//*************************************************************************************************
function secciones() {
    $nombre = "";
    switch ($_SESSION['Seccion']) {
        case 0:
            $nombre = "PREESCOLAR";
            break;
        case 1:
            $nombre = "PRIMARIA";
            break;
        case 2:
            $nombre = "SECUNDARIA";
            break;
        case 3:
            $nombre = "PREPARATORIA";
            break;
        case 4:
            switch ($_SESSION['Carrera']) {
                case 'ARQ':
                    $nombre = "ARQUITECTURA";
                    break;
                case 'IND':
                    $nombre = "INDUSTRIAL";
                    break;
                case 'LAV':
                    $nombre = "ANIMACIÓN y VIDEOJUEGOS";
                    break;
                case 'LDE':
                    $nombre = "DERECHO";
                    break;
                case 'LNI':
                    $nombre = "NEGOCIOS";
                    break;
                case 'LFR':
                    $nombre = "FISIOTERAPIA Y REHABILITACIÓN";
                    break;
                case 'LFC':
                    $nombre = "FORMACIÓN CATEQUÉTICA";
                    break;
                case 'EFT':
                    $nombre = "TRAUMATOLOGÍA y ORTOPEDIA";
                    break;
                case 'EFN':
                    $nombre = "REHABILITACIÓN NEUROLÓGICA";
                    break;
                case 'EFD':
                    $nombre = "REHABILITACIÓN DEPORTIVA";
                    break;
                default:
                    $nombre = "UNIVERSIDAD";
                    break;
                
            }
            break;
        default:
            $nombre = "INSTITUTO VALLADOLID";
            break;
                
    }
    return $nombre;
}

function corto_seccion() {
    $nombre = "";
    switch ($_SESSION['Seccion']) {
        case 0:
            $nombre = "PRE";
            break;
        case 1:
            $nombre = "PRI";
            break;
        case 2:
            $nombre = "SEC";
            break;
        case 3:
            $nombre = "BAC";
            break;
        case 4:
            $nombre = $_SESSION['Carrera'];
            break;
        default:
            $nombre = "UNI";
            break;
    }
    return $nombre;
}

//-----------------------------------------------------------------------------------------------------------------------------------------

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

// FUNCIONES DE ESTILO   ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO ESTILO

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

//*************************************************************************************************
// Funcion:     header
// Descripción: Header y Title de la página
// Parametros:  Titulo
//*************************************************************************************************

function headerfull_($title) {
echo '<!DOCTYPE HTML><html>'."\n";
echo '<head>'."\n";
//echo '<script src="https://kit.fontawesome.com/2a04e74308.js" crossorigin="anonymous"></script>'."\n";
echo '<title>'.$title.'</title>'."\n";
echo '<meta charset="utf-8" />'."\n";
echo '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />'."\n";
echo '<link rel="stylesheet" href="assets/css/main.css" />'."\n";
echo '<link href="assets/css/fontawesome-all.min.css" rel="stylesheet">'."\n";
//scriptmuestra(1);
echo '</head>'."\n";
echo '<body class="is-preload">';
echo '<div id="wrapper">';
echo '<div id="main">';
echo '<div class="inner">';
if ($_SESSION['login'] == 1) {
    echo '<header id="header"><a href="index.php" class="logo">'.$_SESSION['Nombres'].' - <strong>'.$title.'</strong></a>'."\n";
} else {
    echo '<header id="header"><a href="index.php" class="logo"><strong>Instituto Valladolid</strong> - Servicios Escolares</a>'."\n";
}
echo '<ul class="icons">'."\n";
if ($_SESSION['login'] == 1) {
    echo '<li class="icon solid fa-sign-out-alt" style="width:auto;"><a href="logout.php" class="logo">'.$_SESSION['Id'].'</a></li>'."\n";
} else {
?>
    <li class="icon solid fa-key" onclick="document.getElementById('id01').style.display='block'" style="width:auto;"><a href="#" class="logo"> Ingresar</a></li>
<?php
    }
echo '</ul></header>'."\n";
echo '<section>'."\n";

}


//*************************************************************************************************
// Funcion:     footer
// Descripción: acceso al formulario de login o al exit
// Parametros:  Titulo
//*************************************************************************************************
function footer_() {
echo '<section>'."\n";
echo '<div id="id01" class="modal" width="30%";>'."\n";
echo '<form class="modal-content animate" action="index.php" method="post">'."\n";
echo '<div class="imgcontainer"><span onclick="document.getElementById(\'id01\').style.display=\'none\'" class="close" title="Close Modal">&times;</span></div>'."\n";
echo '<div class="container">'."\n";
echo '<label for="uname"><b>Usuario</b></label>'."\n";
echo '<input type="text" placeholder="Ingresar matrícula" name="username" required>'."\n";
echo '<label for="psw"><b>Contraseña</b></label>'."\n";
echo '<input type="password" placeholder="Ingresa Contraseña" name="psw" required>'."\n";
echo '<button type="submit" class="loginbutton">Ingresar</button>'."\n";
echo '</div>'."\n";
//echo '<div class="container" style="background-color:#f1f1f1"><span class="psw">¿Olvidaste tu <a href="#">Contraseña?</a></span><br><br></div>';
echo '</form>'."\n";
echo '</div> <!-- Modal -->'."\n";
echo '</section>'."\n";
// Para la información de los alumnos, solo si es un docente
scriptmuestra(2);

echo '</div>  <!-- inner -->'."\n";
echo '</div>'."\n";
}

//*************************************************************************************************
// Funcion:     scriptmuestra
// Descripción: carga el script y el div dependiendo del contexto de la llamada (1 header, 2 footer) validando privilegios
// Parametros:  contexto
//*************************************************************************************************
function scriptmuestra($pos) {
if (isset($_SESSION['Privs']) && $_SESSION['Privs'] > 1) {      // Solo se ejecuta si los privilegios de usuario son mayores a 2

    switch ($pos) {
        case 1:     // se utiliza para copiar este texto en el header; el parametro es la matricula del alumno a consultar
            ?>
<script>
function showUser(str) {
  if (str == "") {
    document.getElementById("info").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //document.getElementById("info").style.display='block';
        document.getElementById("info").innerHTML = this.responseText;
        document.getElementById("info").style.display='block';
      }
    };
    xmlhttp.open("GET","getuser.php?q="+str,true);
    xmlhttp.send();
  }
}
</script>
<?php
        break;
    case 2:     // se utiliza para copiar este texto en el footer
        echo '<section>'."\n";
        echo '<div id="info" class="modal" width="30%";>'."\n";
        echo '<form class="modal-content animate">'."\n";
        echo '<div class="imgcontainer"><span onclick="document.getElementById(\'info\').style.display=\'none\'" class="close" title="Close Modal">&times;</span></div>'."\n";
        echo '<div class="container">'."\n";
        echo '<br><table width="60%">'."\n";
        echo '<tr><th colspan=2>Datos</th></tr>'."\n";
        echo '<tr><td>Calle:</td><td></td></tr>'."\n";
        echo '<tr><td>Colonia:</td><td></td></tr>'."\n";
        echo '<tr><td>Ciudad:</td><td></td></tr>'."\n";
        echo '<tr><td>Estado:</td><td></td></tr>'."\n";
        echo '<tr><td>Código Postal:</td><td></td></tr>';
        echo '<tr><td>Teléfono:</td><td></td></tr>'."\n";
        echo '<tr><td>Celular:</td><td></td></tr>'."\n";; 
        echo '<tr><td>Correo electrónico:</td><td></td></tr>'."\n";; 
        echo '</table>'."\n";
        echo '</div>'."\n";
        echo '</form>'."\n";
        echo '</div> <!-- Modal -->'."\n";
        echo '</section>'."\n";    
        break;
    }
}

}

//*************************************************************************************************
// Funcion:     scripts
// Descripción: carga los scripts al final de la pagina
// Parametros:  Ninguno
//*************************************************************************************************
function scripts() {
echo '<script src="assets/js/jquery.min.js"></script>'."\n";
echo '<script src="assets/js/browser.min.js"></script>'."\n";
echo '<script src="assets/js/breakpoints.min.js"></script>'."\n";
echo '<script src="assets/js/util.js"></script>'."\n";
echo '<script src="assets/js/main.js"></script>'."\n";
echo '<script src="assets/js/aux.js"></script>'."\n";
echo '</body>'."\n";
echo '</html>'."\n";
}

//*************************************************************************************************
// Funcion:     Sidebar
// Descripción: Menús laterales en función a la sección
// Parametros:  $type: Tipo de usuario 'alumno' 0 /'usuario' 1
//*************************************************************************************************

function sidebar() {
?>
<div id="sidebar">
    <div class="inner">
	<!-- Menu -->
	<nav id="menu">
	<header class="major"><h2>Menu</h2></header>
	<ul>
<?php
$type = 20; // 20 no hay login, 0 es alumno, 1 es usuario
// Validar que hay login, y que tipo de usuario es
if (isset($_SESSION['login']) && ($_SESSION['login'] == 1)) {
  // han realizado Login
  $type = $_SESSION['Type'];
  switch ($type) {
    case '0':   // Es ALUMNO
		echo '<li><a href="index.php">Inicio</a></li>'."\n";
		echo '<li><span class="opener">Académico</span>'."\n";
        echo '<ul>'."\n";
            echo '<li><a href="validpdf.php?context=1&id_alumno='.$_SESSION['Id'].'" target="_blank">Boleta</a></li>'."\n";
            echo '<li><a href="circulares.php">Circulares</a></li>'."\n";
            echo '<li><a href="reglamento.php">Reglamento</a></li>'."\n";
            if (RINSC == 1 && $_SESSION['Seccion'] > 2) { // Se activa la reinscripcion semestral para prepa y uni
                    echo '<li><a href="reinscripcion.php">Reinscripción</a></li>'."\n";
            } else {
                if (RINSC == 2) { //  Se activa reinscripcion anual, para todos
                    echo '<li><a href="reinscripcion.php">Reinscripción</a></li>'."\n";
                }
            }
		echo '</ul>'."\n";
		echo '</li>'."\n";
        echo '<li><span class="opener">Financiero</span>'."\n";
		echo '<ul>'."\n";
            echo '<li><a href="recibos.php">Recibo de pago</a></li>'."\n";
            echo '<li><a href="formapago.php">Formas de pago</a></li>'."\n";
            echo '<li><a href="becas.php">Trámite de Beca</a></li>'."\n";
		echo '</ul>'."\n";
		echo '</li>'."\n";
        //echo '<li><a href="logout.php">Salir</a></li>'."\n";
        break;
    case '1': // Es USUARIO
    // Validar el tipo de usuario y los privilegios
      switch ($_SESSION['Privs']) {
        case '2':     // Titulares
            echo '<li><a href="index.php">Inicio</a></li>'."\n";
            echo '<li><span class="opener">Académico</span>'."\n";
            echo '<ul>'."\n";
            echo '<li><a href="informacion.php">Información</a></li>'."\n";
            echo '<li><a href="circulares.php">Circulares</a></li>'."\n";
            echo '<li><a href="#">Enviar mensajes</a></li>'."\n";
            echo '</ul>'."\n";
            echo '</li>'."\n";
            /*echo '<li><span class="opener">Financiero</span>'."\n";
            echo '<ul>'."\n";
            echo '<li><a href="recibo.php">Recibos de Pago</a></li>'."\n";
            echo '</ul>'."\n";
            echo '</li>'."\n";*/
            //echo '<li><a href="logout.php">Salir</a></li>'."\n";
            break;
        case '5':     // Admin
            echo '<li><a href="index.php">Inicio</a></li>'."\n";
            echo '<li><span class="opener">Académico</span>'."\n";
            echo '<ul>'."\n";
            echo '<li><a href="boleta.php">Ver Boletas</a></li>'."\n";
            echo '<li><a href="#">Bloqueo/Desbloqueo</a></li>'."\n";
            echo '<li><a href="#">Enviar mensajes</a></li>'."\n";
            echo '</ul>'."\n";
            echo '</li>'."\n";
            echo '<li><span class="opener">Financiero</span>'."\n";
            echo '<ul>'."\n";
            echo '<li><a href="#">Recibos de Pago</a></li>'."\n";
            echo '</ul>'."\n";
            echo '</li>'."\n";
            echo '<li><span class="opener">Administrativo</span>'."\n";
            echo '<ul>'."\n";
            echo '<li><a href="#">Usuarios</a></li>'."\n";
            echo '<li><a href="#">Perfiles</a></li>'."\n";
            echo '</ul>'."\n";
            echo '</li>'."\n";
            //echo '<li><a href="logout.php">Salir</a></li>'."\n";
            break;
        }
    default:
    
  }
}
echo '<li><span class="opener">Enlaces</span>'."\n";
echo '<ul>'."\n";
echo '<li><a href="http://aulavirtual.umvalla.edu.mx" target="_blank">Aula Virtual</a></li>'."\n";
echo '<li><a href="http://valladolid.edu.mx" target="_blank">Instituto Valladolid</a></li>'."\n";
echo '<li><a href="http://umvalla.edu.mx" target="_blank">Universidad Marista Valladolid</a></li>'."\n";
if (isset($_SESSION['Id'])) {
    switch ($_SESSION['Seccion']) {
        case 0:
            echo '<li><a href="media/Anuario_Preescolar.pdf" target="_blank">Anuario Escolar</a></li>'."\n";
            break;
        case 1:
            echo '<li><a href="media/Anuario_Primaria.pdf" target="_blank">Anuario Escolar</a></li>'."\n";
            break;
        case 2:
            echo '<li><a href="media/Anuario_Secundaria.pdf" target="_blank">Anuario Escolar</a></li>'."\n";
            break;
        case 3:
            echo '<li><a href="media/Anuario_Bachillerato.pdf" target="_blank">Anuario Escolar</a></li>'."\n";
            break;
        case 4:
            echo '<li><a href="media/Anuario_Universidad.pdf" target="_blank">Anuario Escolar</a></li>'."\n";
            break;
    }
    
}
echo '</ul>'."\n";
if (isset($_SESSION['Id'])) { echo '<li><a href="logout.php">Salir</a></li>'."\n"; }
echo '</li>'."\n";
echo '</ul>'."\n";
echo '</nav>'."\n";

contacto();     //Imprime los datos de contacto de la sección correspondiente - SOLAMENTE  SI INICIARON SESION

}

//*************************************************************************************************
// Funcion:     contacto
// Descripción: Escribe los datos de contacto en funcion a la seccion del alumno
// Parametros:  Ninguno, los toma de variables de sesion
//*************************************************************************************************
function contacto(){
// verificar si hay login y determinar las cadenas necesarias
if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
  switch($_SESSION['Seccion']) {
    case '0':
      $correo="preescolar@valladolid.edu.mx";
      $telefono="4433 13 2098 / 4433 41 6978";
      break;
    case '1':
      $correo="primaria@valladolid.edu.mx";
      $telefono="4433 12 3280 / 4433 12 3392";
      break;
    case '2':
      $correo="secundaria@valladolid.edu.mx";
      $telefono="4433 12 7137 / 4433 13 9886";
      break;
    case '3':
      $correo="preparatoria@valladolid.edu.mx";
      $telefono="4433 23 5150 / 4433 23 7130";
      break;
    case '4':
      $correo="secretaria@umvalla.edu.mx";
      $telefono="4433 43 0295 / 4433 23 7161";
      break;
  }
  if ($_SESSION['Seccion']<5) {   //No es Administrativo, es necesario poner los datos de contacto
    echo '<section>'."\n";
    echo '<p>La comunicación del Instituto con sus alumnos es importante. Para ello ponemos a tu disposición los siguientes medios:</p>'."\n";
	  echo '<ul class="contact">'."\n";
    echo '<li class="icon solid fa-envelope">'.$correo.'</li>'."\n";
    echo '<li class="icon solid fa-phone">'.$telefono.'</li>'."\n";
    echo '</ul>'."\n";
	  echo '</section>'."\n";
  }
}
	// Footer
echo '<footer id="footer">'."\n";
echo '<p class="copyright">&copy; Instituto Valladolid. Todos los derechos reservados.</a>.</p>'."\n";
echo '</footer></div></div></div>'."\n";

}

//*************************************************************************************************

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

// FUNCIONES BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND BACKEND

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************


//*************************************************************************************************
// Funcion:     Ingresar
// Descripción: Realiza el login de usuario
// Parametros:  $Id, $Pass
//*************************************************************************************************

function Ingresar($Id, $Pass) {
$errorflag = 0;
$errortext = "";
$conn = new Usuario();
$user = $conn->login($_POST['username'], $_POST['psw']);
if ($user) {
	// Consiguio los datos
	$_SESSION['Id'] = $user['Id'];
	$_SESSION['Type'] = $user['Type'];
	$_SESSION['Privs'] = $user['Privs'];
	if ($_SESSION['Type'] == 0) {
		// Es alumno
		$alumno = $conn->datosAlumno($_SESSION['Id']);
		if ($alumno) {
			$_SESSION['Nombres'] = $alumno['Nombres']." ".$alumno['Apellidos'];
			$_SESSION['Grado'] = $alumno['Grado'];
			$_SESSION['Grupo'] = $alumno['Grupo'];
			$_SESSION['Seccion'] = $alumno['Seccion'];
			$_SESSION['Correo'] = $alumno['Correo'];
			$_SESSION['IdGrupo'] = $alumno['IdGrupo'];
			if ($_SESSION['Seccion'] == 4) {
					$_SESSION['Carrera'] = substr($alumno['IdGrupo'],0,3);
			}
		} else {
			$errorflag += 1;
            $errortext = "No existen datos del alumno";
		}
	} else {
		// Es Usuario
		$usuario = $conn->datosUsuario($_SESSION['Id']);
		if ($usuario) {
			$_SESSION['Nombres'] = $usuario['Nombres'];
			$_SESSION['Seccion'] = $usuario['Seccion'];
			$_SESSION['Grado'] = $usuario['Grado'];
			$_SESSION['Carrera'] = $usuario['Carrera'];
		} else {
			$errorflag += 1;
            $errortext = "No existen datos de usuario";
		}
	}
	$_SESSION['login'] = 1;
} else {
	$errorflag += 1;
	$_SESSION['login'] = 0;
	$errortext = "Credenciales de acceso no válidas";
}

return $errortext;

}


//*************************************************************************************************
// Funcion:     getAvisos
// Descripción: Una vez que ingresó, buscar los avisos correspondientes a su sección o generales
// Parametros:  Seccion, grado
//*************************************************************************************************

function getAvisos($seccion, $grado) {
    $conn = new aviso();
    $aviso = $conn->leer_avisos($seccion, $grado);
    $indice = count($aviso);
    if ($indice>0) {
    		echo '<section>'."\n";
    		echo '<header class="major"><h2>Avisos</h2></header>'."\n";
    		echo '<div class="box alt">'."\n";
            echo '<div class="row gtr-50 gtr-uniform">'."\n";
            foreach($aviso as $contenido) {
                //echo '<article>';
                echo '<div class="col-4"><span class="image fit"><a href="'.$contenido['Url'].'" class="image"><img src="images/'.$contenido['Imagen'].'" alt="" /></a></span>'."\n";
                echo '<h4>'.$contenido['Titulo'].'</h4>'."\n";
                echo '<p>'.$contenido['Contenido'].'</p>'."\n";
                echo '</div>'."\n";
            }
            echo '</div>'."\n";
            echo '</div>'."\n";
    		/* echo '<div class="posts">';
    		foreach($aviso as $contenido) {
                echo '<article><a href="'.$contenido['Url'].'" class="image"><img src="images/'.$contenido['Imagen'].'" alt="" /></a>';
                echo '<h3>'.$contenido['Titulo'].'</h3>';
                echo '<p>'.$contenido['Contenido'].'</p>';
                echo '</article>';
            }
            echo '</div>'; */
            echo '</section>'."\n";        
    }
}


//*************************************************************************************************
// Funcion:     getGrupos
// Descripción: Si el usuario es Docente Titular, buscar sus grupos y mostrarlos.
// Parametros:  Id
//*************************************************************************************************

function GrupoActivo($IdUsuario, $GrupoActivo) {
    $conn = new Titular();
    $grupo = $conn->lista_grupos($IdUsuario);
    $indice = count($grupo);
    if ($indice>0) {
        echo '<form method="post" action="index.php">'."\n";
        echo '<div class="row gtr-uniform">'."\n";
        echo '<div class="col-6 col-12-xsmall">'."\n";
        echo '<legend>Grupo Activo</legend>'."\n";
        echo '<select name="Active" id="Active">'."\n";
        if ($GrupoActivo == '0') {
            echo '<option value="">Selecciona el Grupo Activo</option>'."\n";
        } else {
            echo '<option value="'.$GrupoActivo.'">'.$GrupoActivo.'</option>'."\n";
        }
        foreach($grupo as $contenido) {
           // if ($contenido['IdGrupo'] != $GrupoActivo) {
                echo '<option value="'.$contenido['IdGrupo'].'">'.$contenido['IdGrupo'].'</option>'."\n";
           // }
        }
        echo '</select>'."\n";
        echo '</div>'."\n";
        echo '<div class="col-12">'."\n";
        echo '<ul class="actions">'."\n";
        echo '<li><input type="submit" value="Activar " class="primary" /></li>'."\n";
        echo '</ul>'."\n";
        echo '</div>'."\n";
        echo '</div>'."\n";
        echo '</form>'."\n";
    }
}

        

//*************************************************************************************************
// Funcion:     listado
// Descripción: Obtener un listado de los alumnos correspondientes al grupo activo del Titular
// Parametros:  IdGrupo
//*************************************************************************************************

function listado($grupo) {
    $conn = new alumnos();
    $lista = $conn->lista_alumnos($grupo);
    $indice = count($lista);
    if ($indice>0) {
        echo '<table>'."\n";
        echo '<tr><th>Matrícula</th><th>Apellidos</th><th>Nombres</th><th><center>Boleta</center></th><th><center>Recibo de Pago</center></th><th><center>Información</center></th></tr>'."\n";
        foreach($lista as $datos) {
            echo '<tr><td>'.$datos['Id'].'</td><td>'.$datos['Apellidos'].' </td><td>'.$datos['Nombre'].'</td>'."\n";
            echo '<td><center><a href="validpdf.php?context=1&id_alumno='.$datos['Id'].'" target="_blank"><i class="fas fa-book-open"></i></a></center></td>'."\n";
            echo '<td><center><a href="validpdf.php?context=2&id_alumno='.$datos['Id'].'" target="_blank"><i class="fas fa-money-check-alt"></i></a></center></td>'."\n";
            echo '<td><center><a href="#" class="logo"><i class="fas fa-address-card" onclick="showUser('.$datos['Id'].')" style="width:auto;"></a></i></center></td></tr>'."\n";
        }
        echo '</table>'."\n";
        
    }
}

//*************************************************************************************************
// Funcion:     listado_recibo
// Descripción: Obtener un listado de los alumnos correspondientes al grupo activo del Titular
// Parametros:  IdGrupo
//*************************************************************************************************

function listado_recibo($grupo) {
    $conn = new alumnos();
    $lista = $conn->lista_alumnos($grupo);
    $indice = count($lista);
    if ($indice>0) {
        echo '<table>'."\n";
        foreach($lista as $datos) {
            echo '<tr><td><a href="validpdf.php?context=2&id_alumno='.$datos['Id'].'" target="_blank">'.$datos['Id'].'</a></td><td>'.$datos['Apellidos'].' </td><td>'.$datos['Nombre'].'</td><td>'.$datos['IdGrupo'].'</td></tr>'."\n";
        }
        echo '</table>'."\n";
        
    }
}

//*************************************************************************************************

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

// FUNCIONES ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS ETIQUETAS

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************

//*************************************************************************************************
// Funcion:     texto_reinscripcion
// Descripción: Imprime el HTML previo al formulario, eventualmente deberá desaparecer
// Parametros:  Seccion del alumno ingresado
// Regresa:     Nada
//*************************************************************************************************
function texto_reinscripcion($seccion) {
    switch ($seccion) {
        case 0:     // Preescolar
            echo '<p>Preescolar</p>'."\n";
            break;
        case 1:     // Primaria
            ?>
            <ol>
            <li>Descarga la <strong>ficha de reinscripción</strong></li>
            <li>Llena solamente el <strong>primer recuadro</strong> de la ficha de reinscripción.</li>
            <li>Guarda la ficha de reinscripción con el <strong>nombre completo y grado al cual se reinscribe el alumno.</strong></li>
            <li>Envía por correo electrónico la ficha de reinscripción a <strong>cmaciel@valladolid.edu.mx</strong> encargada del Departamento de Control Escolar Carmen Lucia Maciel Domínguez</li>
            <li>Realiza los pagos para la reinscripción, para ello <strong>descarga las <a href='recibos.php'>fichas referenciadas.</a></strong></li>
            </ol> 
            <p>NOTA:  Para la entrega y firma de documentos oficiales, como es el contrato de servicios, ficha médica y la carta compromiso del alumno se hará de manera presencial, por lo cual, como Institución estamos esperando indicaciones de nuestras autoridades.</p>
            <center><h3>COSTOS DEL CICLO ESCOLAR 2020 – 2021</h3></center>
            <p> Reinscripción <b>$5,100.00</b><br>
            Para su comodidad se han divido los pagos en 3 partes</p>
            <table id="alumnos" width="80%">
            <tr><th>Pago</th><th>Fecha de vencimiento</th><th>Cantidad</th></tr>
            <tr><td>1°</td><td>*20 de junio</td><td>$1,500.00</td></tr>
            <tr><td>2°</td><td>31 de julio</td><td>**$1,800.00</td></tr>
            <tr><td>3°</td><td>31 de agosto</td><td>**$1,800.00</td></tr>
            </table>
            <p>*Para los alumnos que realicen el tramite de convenio o beca interna deberán realizar el primer pago de $1,500.00 antes del 23 de mayo, para poder realizar este trámite.</p>
            <p>**Para los alumnos con convenio para el ciclo escolar 2020-2021 al pago de la reinscripción se le debe realizar el descuento otorgado. También se divide en 3 partes quedando de la siguiente manera: el primer pago es de $1,500.00 y los otros dos pagos son del resto del pago divido en 2, por lo anterior, las fichas referenciadas no se podrán utilizar para estos dos pagos.</p>
            <?php
            break;
        case 2:     //Secundaria
            ?>
            <ol>
            <li>Descarga la <strong><a href="/media/Ficha_Control_Escolar_Secundaria_Formulario.pdf" target="_blank">Ficha de control escolar</a></strong></li>
            <li>Llena los datos solicitados y guarda el archivo con el <strong>nombre completo y grado al cual se reinscribe el alumno.</strong></li>
            <li>Envía por correo electrónico la ficha de reinscripción a <strong>secundaria@valladolid.edu.mx</strong>, con atención a la encargada del Departamento de Control Escolar: Nancy Dueñas Arroyo</li>
            <li>Realiza los pagos para la reinscripción, para ello <strong>descarga las <a href='recibos.php'>fichas referenciadas.</a></strong></li>
            </ol> 
            <p>NOTA:  Para la entrega y firma de documentos oficiales, como es el contrato de servicios, ficha médica y la carta compromiso del alumno se hará de manera presencial, por lo cual, como Institución estamos esperando indicaciones de nuestras autoridades.</p>
            <center><h3>COSTOS DEL CICLO ESCOLAR 2020 – 2021</h3></center>
            <p> Reinscripción <b>$6,500.00</b><br>
            Para su comodidad se han divido los pagos en 3 partes</p>
            <table id="alumnos" width="80%">
            <tr><th>Pago</th><th>Fecha de vencimiento</th><th>Cantidad</th></tr>
            <tr><td>1°</td><td>*20 de junio</td><td>$1,500.00</td></tr>
            <tr><td>2°</td><td>31 de julio</td><td>**$2,500.00</td></tr>
            <tr><td>3°</td><td>31 de agosto</td><td>**$2,500.00</td></tr>
            </table>
            <p>* Para los alumnos que realicen el tramite de convenio o beca interna deberán realizar el primer pago de $1,500.00 antes del 23 de mayo, para poder realizar este trámite.</p>
            <p>** Para los alumnos con convenio para el ciclo escolar 2020-2021 al pago de la reinscripción se le debe realizar el descuento otorgado. También se divide en 3 partes quedando de la siguiente manera: el primer pago es de $1,500.00 y los otros dos pagos son del resto del pago divido en 2, por lo anterior, las fichas referenciadas no se podrán utilizar para estos dos pagos.</p>
            <?php
            break;
        case 3:     //Preparatoria
            ?>
            <ol>
            <li>Descarga la <strong><a href="media/Ficha_de_Control_Escolar_Preparatoria.pdf" target="_blank">Ficha de Registro</a></strong></li>
            <li>Llena los datos solicitados y guarda el archivo con el <strong>nombre completo y grado al cual se reinscribe el alumno.</strong></li>
            <li>Envía por correo electrónico la ficha de reinscripción a <strong>controlescolar@valladolid.edu.mx</strong>, con atención a la encargada del Departamento de Control Escolar: <b>María del Carmen Vázquez Zepeda.</b></li>
            <li>Realiza los pagos para la reinscripción, para ello <strong>descarga las <a href='recibos.php'>fichas referenciadas.</a></strong></li>
            </ol> 
            <p>NOTA:  Para la entrega y firma de documentos oficiales, como es el contrato de servicios y la carta compromiso del alumno se hará de manera presencial, por lo cual, como Institución estamos esperando indicaciones de nuestras autoridades.</p>
            <?php
            break;
        case 4:     //Universidad
            ?>
            <ol>
            <li>Descarga la <strong><a href="media/Formulario_Ficha_de_Registro.pdf" target="_blank">Ficha de Registro</a></strong></li>
            <li>Llena los datos solicitados y guarda el archivo con el <strong>nombre completo y grado</strong> al cual se reinscribe el alumno.</li>
            <li>Envía por correo electrónico la ficha de reinscripción a <strong>controlescolar@umvalla.edu.mx</strong>, con atención a la encargada del Departamento de Control Escolar: <b>Adriana Navarro.</b></li>
            <li>Realiza los pagos para la reinscripción, para ello <strong>descarga las <a href='recibos.php'>fichas referenciadas.</a></strong></li>
            </ol> 
            <p>NOTA:  Para la entrega y firma de documentos oficiales, como es el contrato de servicios y la carta compromiso del alumno se hará de manera presencial, por lo cual, como Institución estamos esperando indicaciones de nuestras autoridades.</p>
            <?php
            break;
        default:     //
            echo '<p>PROCESO DEFAULT</p>';
            break;
        }

}



//*************************************************************************************************
// Funcion:     texto_becas
// Descripción: Imprime el HTML previo al formulario, uno para preescolar a bachillerato, uno para universidad
// Parametros:  Seccion del alumno ingresado
// Regresa:     Nada
//*************************************************************************************************
function texto_becas($seccion) {
    if ($seccion <4) {      // Instituto
        ?>
        <h3>Proceso de Solicitud o Renovación de Beca</h3>
        <ol>
            <li>Descarga el <strong><a href="media/FormatoBecasInstituto.pdf" target="_blank">Formato de Solicitud</a></strong>. Este es un archivo PDF que deberás llenar digitalmente.</li>
            <li>Prepara los archivos digitales indicados en el formato. Recomendamos que los escanees en formato PDF (menores a 1 Mb)</li>
            <li>Realiza el primer pago de reinscripción, para ello <strong>descarga las <a href='recibos.php'>fichas referenciadas.</a></strong>. Es requisito realizarlo antes del 20 de mayo para ser tomado en cuenta en el proceso de asignación/reasignación de becas.</li>
            <li>Sube los archivos en esta página.
        </ol> 
        <p>* En el caso de INE y Comprobantes de pago, en el mismo archivo deberán incluirse los datos de ambos Padres o Tutores.</p>
        <?php
    } else {        // Universidad
        ?>
        <h3>Proceso de Solicitud o Renovación de Beca</h3>
        <ol>
             <li>Descarga el <strong><a href="media/FormatoBecasUniversidad.pdf" target="_blank">Formato de Solicitud</a></strong>. Este es un archivo PDF que deberás llenar digitalmente.</li>
            <li>Prepara los archivos digitales indicados en el formato. Recomendamos que los escanees en formato PDF (menores a 1 Mb)</li>
            <li>Realiza el primer pago de reinscripción, para ello <strong>descarga las <a href='recibos.php'>fichas referenciadas.</a></strong>. Es requisito realizarlo antes del 20 de mayo para ser tomado en cuenta en el proceso de asignación/reasignación de becas.</li>
            <li>Sube los archivos en esta página.
        </ol> 
        <p>* En el caso de INE y Comprobantes de pago, en el mismo archivo deberán incluirse los datos de ambos Padres o Tutores.</p>
        <?php
    }
}


