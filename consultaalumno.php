<meta charset="utf-8"/>
<?php
include ("dbconect.php"); 
// Rescatamos el parámetro que nos llega mediante la url que invoca xmlhttp
$Id=$_POST["id"];
$resultadoConsulta = '';
$msg = 'El alumno a consultar es '.$Id;
if ($Id) {
    $conexionBD=new alumnos();
    $resultado=$conexionBD->lista_alumnos_contacto($Id);
    if ($resultado) {
        foreach ($resultado as $registro) {
            $calle_ = $registro['Calle'];
            $colonia_ = $registro['Colonia'];
            $ciudad_ = $registro['Ciudad'];
            $estado_ = $registro['Estado'];
            $postal_ = $registro['Postal'];
            $tel1_ = $registro['TelFijo'];
            $cel1_ = $registro['Celular'];
        }
        $respuesta = '<p>'.$calle_.'</p><p>'.$colonia_.'</p>';
    }
//Devolvemmos la cadena de respuesta
echo $msg.$respuesta
} else {
 echo 'No se han recibido datos';
}
?> 
