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
        $error = Ingresar($_POST['username'], $_POST['psw']);
    }
}

headerfull_('Servicios Escolares');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    // validar el tipo de usuario
    switch ($_SESSION['Type']) {
    case 0:     // ALUMNO
        // ¿Existen avisos?
        getAvisos($_SESSION['Seccion'], $_SESSION['Grado']);
        
        // imprimir sus datos
        echo '<table>'."\n";
        echo '<tr><th width=20%>Matricula</th><td>'.$_SESSION['Id'].'</td></tr>'."\n";
        echo '<tr><th width=20%>Correo electrónico</th><td>'.$_SESSION['Correo'].'</td></tr>'."\n";
        echo '<tr><th width=20%>Grupo</th><td>'. $_SESSION['IdGrupo'].'</td></tr>'."\n";
        if ($_SESSION['Seccion'] == 4) {
            echo '<tr><th width=20%>Carrera</th>'."\n";
        } else { 
            echo '<tr><th width=20%>Sección</th>'."\n";
        }
        echo '<td>'.secciones($_SESSION['Seccion']).'</td></tr>'."\n";
        echo '<tr><th width=20%>Corto</th><td>'. corto_seccion().'</td></tr>'."\n";
        echo '<tr><th width=20%>Tabla</th><td>'. $_SESSION['Seccion'].'</td></tr>'."\n";
        if ($_SESSION['Seccion'] == 4) {
            echo '<tr><th width=20%>Carrera</th><td>'. $_SESSION['Carrera'].'</td></tr>'."\n";
        }
        echo '</table>'."\n";
        break;
    case 1:     // USUARIO - Validar el tipo de usuario----------------------------------
        echo '<h3>Bienvenido '.$_SESSION['Nombres'].'</h3>'."\n";
        switch ($_SESSION['Privs']) {
            case 2:     // Titular
                // Obtener los datos del titular
                if (isset($_POST['Active'])){   // Viene del formulario
                $_SESSION['Activo'] = $_POST['Active'];
                }
                if (isset($_SESSION['Activo'])) {        // ¿Que quieres hacer? ¿Seleccionar otro grupo o tomar alguna acción en el menú lateral
                    echo '<p>Cambia tu grupo activo ('.$_SESSION['Activo'].') o selecciona una acción del menú lateral</p>'."\n";
                    GrupoActivo($_SESSION['Id'], $_SESSION['Activo']);
                } else {       // No viene de formulario, ni ha seleccionado, mostrar el formulario limpio
                    echo '<p>Elige un grupo para comenzar a trabajar</p>'."\n";
                    GrupoActivo($_SESSION['Id'], 0);
                }
                break;
            case 5:
                echo '<p>ADMINISTRADOR</p>'."\n";
                break;
            }
        break;
    }
} else {
    echo '<header class="major"><h2>Bienvenido al Sistema de Servicios <br>Escolares del Instituto Valladolid.</h2></header>'."\n";
    echo '<p><b>Ingresa con tus credenciales</b></p>'."\n";
    if (isset($error) && strlen($error)>2) { echo '<script type="text/javascript"> alert ("'.$error.'"); </script> '."\n"; }
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
