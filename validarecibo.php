<?php 
ob_start();
session_start();

// Validar si viene el parametro de la matricula
if(isset($_GET['id_alumno'])) {
    $matricula=$_GET['id_alumno'];
} else {
    echo "<center><H1>Parametro Incorrecto...</br>Comunicate con el departamento de Control Escolar</H1></center>";
}

//Recuperamos Datos del alumno
$archivo = "recibos/".$_SESSION['Seccion']."/".$matricula.".pdf";   // Ruta de la boleta

//Validamos que esté buscando su propia boleta
if ($_SESSION['Id'] == $matricula) {
    if (file_exists($archivo)) 	{   //La boleta no está bloqueada
        header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
		header ("Pragma: no-cache");  
		header('Content-type: application/pdf'); 
		readfile($archivo); 
    } else 	{   // Revisar si la boleta está bloqueada
        $archivo2 = "boletas/".$_SESSION['Seccion']."/_".$matricula.".pdf"; 
        if (file_exists($archivo2)) { // Se encontro el archivo bloqueado, mostrar aviso de pago
            $archivo = "boletas/Aviso.pdf"; // aqui la ruta a la carpeta con todos tus pdf
            header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
            header ("Pragma: no-cache");  
            header('Content-type: application/pdf'); 
            readfile($archivo); 
        } else { // No se encuentra el archivo, solicitarlo impreso 
            echo "<center><H1>Eror de consulta...</br>El archivo solicitado no existe</br>";
            echo "Comunicate con el departamento de Control Escolar para una copia impresa</H1></center>";
        }
    }
} else {
	echo "<center><H1>Error de consulta, revisa tus datos por favor.</H1></center>";
}

ob_end_flush();
?>
