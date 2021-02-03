<?php 
// Para no permitir que se descarguen archivos que no correspondan al usuario activo, ni que se vean las rutas
ob_start();
session_start();
//Validamos variables por get
if(isset($_GET['context']) && isset($_GET['id_alumno'])) {
    $contexto = $_GET['context'];
    $matricula=$_GET['id_alumno'];
} else {
    echo "<center><H1>Parametro Incorrecto...</br>Comunicate con el departamento de Control Escolar</H1></center>";
}
//Validamos que esté buscando sus propios archivos o que la persona que consulta sea docente o administrador
if ($_SESSION['Id'] == $matricula or $_SESSION['Privs'] > 1) {
    // Accion a realizar en función al contexto
    switch ($contexto) {        // El contexto permite saber que es lo que estás descargando
        case 1:     // Calificaciones
            //Recuperamos Datos del alumno
            $archivo = "boletas/".$_SESSION['Seccion']."/".$matricula.".pdf";   // Ruta de la boleta
            if (file_exists($archivo)) 	{   //La boleta no está bloqueada
                header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
                header ("Pragma: no-cache");  
                header('Content-type: application/pdf'); 
                readfile($archivo); 
            } else 	{   // Revisar si la boleta está bloqueada
                $archivo2 = "boletas/".$_SESSION['Seccion']."/_".$matricula.".pdf"; 
                if (file_exists($archivo2)) { // Se encontro el archivo bloqueado, mostrar aviso de pago
                    $archivo = "boletas/".$_SESSION['Seccion']."/Aviso.pdf"; // aqui la ruta a la carpeta con todos tus pdf
                    header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
                    header ("Pragma: no-cache");  
                    header('Content-type: application/pdf'); 
                    readfile($archivo); 
                } else { // No se encuentra el archivo, solicitarlo impreso 
                    echo "<center><H1>Eror de consulta...</br>El archivo solicitado no existe</br>";
                    echo "Comunicate con el departamento de Control Escolar para una copia impresa</H1></center>";
                }
            }
            break;
        case 2:     // Recibo de pago Vigente
            $archivo = 'recibos/'.$_SESSION['Seccion'].'/'.$matricula.'.pdf';
            if (file_exists($archivo)) 	{   //La boleta no está bloqueada
                header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
                header ("Pragma: no-cache");  
                header('Content-type: application/pdf'); 
                readfile($archivo); 
            } else { // No se encuentra el archivo, solicitarlo impreso 
                echo "<center><H1>Eror de consulta...</br>El archivo solicitado no existe</br>";
                echo "Comunicate con el departamento de Control Escolar para una copia impresa</H1></center>";
            }
            break;
        case 3:     // Recibo de pago Inscripcion 1
            $archivo = 'recibos/'.$_SESSION['Seccion'].'/pago1/'.$matricula.'.pdf';
            if (file_exists($archivo)) 	{   //La boleta no está bloqueada
                header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
                header ("Pragma: no-cache");  
                header('Content-type: application/pdf'); 
                readfile($archivo); 
            } else { // No se encuentra el archivo, solicitarlo impreso 
                echo "<center><H1>Eror de consulta...</br>El archivo solicitado no existe</br>";
                echo "Comunicate con el departamento de Control Escolar para una copia impresa</H1></center>";
            }
            break;
        case 4:     // Recibo de pago Inscripcion 2
            $archivo = 'recibos/'.$_SESSION['Seccion'].'/pago2/'.$matricula.'.pdf';
            if (file_exists($archivo)) 	{   //La boleta no está bloqueada
                header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
                header ("Pragma: no-cache");  
                header('Content-type: application/pdf'); 
                readfile($archivo); 
            } else { // No se encuentra el archivo, solicitarlo impreso 
                echo "<center><H1>Eror de consulta...</br>El archivo solicitado no existe</br>";
                echo "Comunicate con el departamento de Control Escolar para una copia impresa</H1></center>";
            }
            break;
        case 5:     // Recibo de pago Inscripcion 3
            $archivo = 'recibos/'.$_SESSION['Seccion'].'/pago3/'.$matricula.'.pdf';
            if (file_exists($archivo)) 	{   //La boleta no está bloqueada
                header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
                header ("Pragma: no-cache");  
                header('Content-type: application/pdf'); 
                readfile($archivo); 
            } else { // No se encuentra el archivo, solicitarlo impreso 
                echo "<center><H1>Eror de consulta...</br>El archivo solicitado no existe</br>";
                echo "Comunicate con el departamento de Control Escolar para una copia impresa</H1></center>";
            }
            break;
    }
            
} else {
	echo "<center><H1>Error de consulta, revisa tus datos por favor.</H1></center>";
}

ob_end_flush();
?>
