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

headerfull_('Boletas');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    // validar el tipo de usuario
    switch ($_SESSION['Type']) {
    case 0:     // ALUMNO No debería estar aquí
        break;
    case 1:     // USUARIO
        switch ($_SESSION['Privs']) {
                case 2: // Es titular
                    if (isset($_SESSION['Activo'])) {
                        echo '<p>Grupo: '.$_SESSION['Activo'].' - '.secciones().'</p>';
                         listado($_SESSION['Activo']);
                    } else {
                        echo '<p>No has seleccionado un grupo para mostrar, hazlo desde el <a href="index.php">Inicio</a></p>';
                    }
                    break;
                case 5: // Es administrador
                    echo '<p>Administrador</p>';
                    break;
                }
            break;
        }
} else {
    echo '<header class="major"><h2>Bienvenido al Sistema de Servicios <br>Escolares del Instituto Valladolid.</h2></header>';
    echo '<p><b>Ingresa con tus credenciales</b></p>';
    if (isset($error) && strlen($error)>2) { echo '<p>'.$error.'</p>'; }
}

echo '<div class="posts"></div>';
echo '</section>';

/* ------------------- AQUI TERMINA LA SECCION CENTRAL DE INFORMACION -------------------*/
// comienza el login
//<!-- main -->
footer_();

// Imprime el menú lateral de acuerdo a los datos y al contexto.
sidebar();

/* Scripts */
scripts();


?>
