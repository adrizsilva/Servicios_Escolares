<?php

// Se utiliza para mostrar en un div los datos del alumno.
// Se llama desde la función ShowUser() en informacion.php
error_reporting(E_ALL);
//ini_set("display_errors","On");
//ini_set("session.gc_maxlifetime","14400");
//session_start();

include ("libs.php");           /* Librerias */
include ("dbconect.php");
$q = $_GET['q'];

echo '<form class="modal-content animate">'."\n";
echo '<div class="imgcontainer"><span onclick="document.getElementById(\'info\').style.display=\'none\'" class="close" title="Close Modal">&times;</span></div>'."\n";
echo '<div class="container">'."\n";

$conexionBD=new alumnos();
$resultado=$conexionBD->lista_alumnos_contacto($q);
if (!$resultado) { // No hay datos para consultar, inicializar Valores
    $calle_ = " ";
    $colonia_ = " ";
    $ciudad_ = " ";
    $estado_ = " ";
    $postal_ = " ";
    $tel1_ = " ";
    $cel1_ = " ";
    $correo_ = " ";
} else {    // tomamos los valores
    foreach ($resultado as $registro) {
    $calle_ = $registro['Calle'];
    $colonia_ = $registro['Colonia'];
    $ciudad_ = $registro['Ciudad'];
    $estado_ = $registro['Estado'];
    $postal_ = $registro['Postal'];
    $tel1_ = $registro['TelFijo'];
    $cel1_ = $registro['Celular'];
    $correo_ = $registro['Correo'];
}
}
echo '<h3>Datos de Contacto</h3><table width="60%">'."\n";
echo '<tr><td>Calle:</td><td>'.$calle_.'</td></tr>'."\n";
echo  '<tr><td>Colonia:</td><td>'.$colonia_.'</td></tr>'."\n";
echo '<tr><td>Ciudad:</td><td>'.$ciudad_.'</td></tr>'."\n";
echo '<tr><td>Estado:</td><td>'.nomestado($estado_).'</td></tr>'."\n";
echo '<tr><td>Código Postal:</td><td>'.$postal_.'</td></tr>'."\n";
echo '<tr><td>Teléfono:</td><td>'.$tel1_.'</td></tr>'."\n";
echo '<tr><td>Celular:</td><td>'.$cel1_.'</td></tr>'."\n";
echo '<tr><td>Correo electrónico:</td><td>'.$correo_.'</td></tr>'."\n";
echo '</table>';
echo '</div>'."\n";
echo '</form>'."\n";

?>
