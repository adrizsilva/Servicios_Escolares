<?php
//error_reporting(E_ALL);
//ini_set("display_errors","On");
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

headerfull_('Recibos de pago');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    echo '<header class="major"><h2>Recibos de Pago</h2></header>'."\n";
    // validar si es alumno
    if ($_SESSION['Type'] == 0) { // Es alumno
        echo '<table id="circular">'."\n";
        // Primero validamos que existan los recibos revisando las rutas correctas
        // Recibo vigente
        $recibo = 'recibos/'.$_SESSION['Seccion'].'/'.$_SESSION['Id'].'.pdf';
        if (file_exists($recibo)) 	{   //Hay un recibo vigente de este alumno
            echo '<tr><td><a href="validpdf.php?context=2&id_alumno='.$_SESSION['Id'].'" target="_blank">Recibo de pago vigente</a></td></tr>'."\n";
        } else {
            echo '<tr><td>No encontramos Recibo Vigente ¿Ya se realizó el pago?</td></tr>'."\n";
        }
        // ciclo para recibos de inscripcion
        $recibo1 = 'recibos/'.$_SESSION['Seccion'].'/pago1/'.$_SESSION['Id'].'.pdf';
        if (file_exists($recibo1)) 	{ echo '<tr><td><a href="validpdf.php?context=3&id_alumno='.$_SESSION['Id'].'" target="_blank">Recibo Inscripción Pago 1</a></td></tr>'."\n"; }
        $recibo2 = 'recibos/'.$_SESSION['Seccion'].'/pago2/'.$_SESSION['Id'].'.pdf';
        if (file_exists($recibo2)) 	{ echo '<tr><td><a href="validpdf.php?context=4&id_alumno='.$_SESSION['Id'].'" target="_blank">Recibo Inscripción Pago 2</a></td></tr>'."\n"; }
        $recibo3 = 'recibos/'.$_SESSION['Seccion'].'/pago3/'.$_SESSION['Id'].'.pdf';
        if (file_exists($recibo3)) 	{ echo '<tr><td><a href="validpdf.php?context=5&id_alumno='.$_SESSION['Id'].'" target="_blank">Recibo Inscripción Pago 3</a></td></tr>'."\n"; }
        // Cerramos la tabla
        echo '</table>'."\n";
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
