<?php
error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("session.gc_maxlifetime","14400");
session_start();

include ("libs.php");           /* Librerias */
include ("dbconect.php");

// VALIDAR SI YA SE INICIO SESION
IF (isset($_SESSION['login'])&&($_SESSION['login'] == 1)) {
    // sesion iniciada
} else {
    // Verificar si es la primera vez que envían el login
    $_SESSION['login'] = 0;
    $errores = array();
    if (isset($_POST['username']) && isset($_POST['psw'])) {
        // Viene de un login
        $error = Ingresar($_POST['username'], $_POST['psw']); // Validarlo, si es false no existe el usuario
    }
}

headerfull_('Reglamentos');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    //echo '<header class="major"><h2>Reglamento</h2></header>'."\n";
    // validar si es alumno
    if ($_SESSION['Type'] == 0) { // Es alumno
        $dir = 'reglamento/'.$_SESSION['Seccion'].'/';
        $directorio=opendir($dir); 
        if ($gestor = opendir($dir)) {      // SE PUEDE ABRIR EL DIRECTORIO
            echo '<table id="circular">'."\n";
            while (false !== ($archivo = readdir($gestor))) {
                if($archivo=='.' or $archivo=='..') { 
                    echo ''; 
                } else { 
                    echo '<tr><td><a href="'.$dir.$archivo.'" target="_blank">'.$archivo.'</a></td></tr>'."\n";
                }
            }
            echo '</table>'."\n";
        } else {
            echo '<center><h3>AÚN NO EXISTEN CIRCULARES EN ESTE CICLO ESCOLAR</h3></center>'."\n";
        }

    closedir($directorio); 
}
} else {
    echo '<header class="major"><h2>Bienvenido al Sistema de Servicios <br>Escolares del Instituto Valladolid.</h2></header>'."\n";
    echo '<p><b>Ingresa con tus credenciales</b></p>'."\n";
    if (isset($error) && strlen($error)>2) { echo '<p>'.$error.'</p>'."\n"; }
}

echo '<div class="posts"></div>'."\n";
echo '</section>'."\n";

/* ------------------- AQUI TERMINA LA SECCION CENTRAL DE INFORMACION -------------------*/
// comienza el login
//<!-- main -->
footer_();

// Imprime el menú lateral de acuerdo a los datos y al contexto.
sidebar();

/* Scripts */
scripts();

?>
