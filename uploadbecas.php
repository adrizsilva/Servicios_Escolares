<?php
error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("session.gc_maxlifetime","14400");
session_start();

include ("libs.php");           /* Librerias */
include ("dbconect.php");

$errorflag = 0;         // Cuenta los errores encontrados
$flagOK = 0;            // Suma los archivos subidos, debe ser igual a 4 para que se considere completo el proceso
$uploadOk = 0;          // 0 si existe algún errro, 1 si es posible subir el archivo. Se inicializa en 1 al comenzar cada validación. Esta relacionada con la variable anterior
$errores = array();     // Acumula en un arreglo los errores para mostrarlos al final
$Valida_POST = FALSE;   // Bandera que revisa que los datos en el formulario no estén vacios.
$ValidaF1 = 0;          // Se envío Formato
$ValidaF2 = 0;          // Se envío Boleta
$ValidaF3 = 0;          // Se envío Ingresos
$ValidaF4 = 0;          // Se envío Id
$ValidaF5 = 0;          // Se envío Estudio
$numarchivos = 0;       // Cuantos archivos se enviaron

// VALIDAR SI YA SE INICIO SESION
if (isset($_SESSION['login'])&&($_SESSION['login'] == 1)) {
} else {
    $_SESSION['login'] = 0;
    $errores = array();
    if (isset($_POST['username']) && isset($_POST['psw'])) {
        $error = Ingresar($_POST['username'], $_POST['psw']); // Validarlo, si es false no existe el usuario
    }
}

headerfull_('Becas');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    // verifica que todos los datos vengan en el formulario y no sean vacios, en caso contrario debe Regresar al formulario
    $Valida_POST = (isset($_POST['tipo']) && !empty($_POST['tipo']));
    if (is_uploaded_file($_FILES['formato']['tmp_name'])) { $ValidaF1 = 1; }
    if (is_uploaded_file($_FILES['boleta']['tmp_name'])) { $ValidaF2 = 1; }    
    if (is_uploaded_file($_FILES['ingresos']['tmp_name'])) { $ValidaF3 = 1; }
    if (is_uploaded_file($_FILES['idoficial']['tmp_name'])) { $ValidaF4 = 1; }
    if (is_uploaded_file($_FILES['estudio']['tmp_name'])) { $ValidaF5 = 1; }
    
    $numarchivos = $ValidaF1+$ValidaF2+$ValidaF3+$ValidaF4+$ValidaF5;
    if ($Valida_POST == FALSE) {
        echo '<script type="text/javascript">'."\n";
        echo 'alert("Los datos están incompletos, por favor revisa el formulario nuevamente");'."\n"; 
        echo 'window.location = "becas.php"'."\n"; 
        echo '</script>'."\n";
        header("Location: becas.php");
    } else  {
        // validar el tipo de usuario
        
        switch ($_SESSION['Type']) {
            case 0:     // ALUMNO Son los únicos que pueden subir sus datos
            if (isset($_POST['matricula'])) {
                //Recuperamos datos del formulario
                $_matricula = $_POST['matricula'];
                $_nombre = $_POST['nombre'];
                $_seccion = $_POST['seccion'];
                $_ciclosig = $_POST['ciclosig'];
                $_cicloact = $_POST['cicloact'];
                $_grado = $_POST['gradosig'];
                $_tipo = $_POST['tipo'];                
                $_flag = $_POST['Flag'];    // 0 para insert, 1 para update
                $_fecha = $_POST['fecha'];
                // --------- VALIDAMOS QUE EL USUARIO CORRESPONDA CON LOS DATOS ----------------
                if ($_matricula == $_SESSION['Id'] ) {
                    // Determinar el directorio en función a la sección
                    $target_dir = "becas/".$_SESSION['Seccion'];
                // REVISAMOS LOS ARCHIVOS --------------------------------------------------------
                    // FORMATO DE SOLICITUD
                    if ($_FILES["formato"]["error"] == 0 && $ValidaF1 == 1) {
                        //echo '<p>Ingresando Formato</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $formato = basename($_FILES["formato"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($formato,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/formato/".$_matricula . "." . $FileType1;    // La ficha se renombra a la matricula del alumno
                        // Revisar si el archivo existe
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        // Revisar el tamaño del archivo 1 Mb
                        if ($_FILES["formato"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Formato: El archivo excede el tamaño permitido: ".$_FILES["formato"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "pdf" ) {
                            $errorflag += 1;
                            array_push ($errores, "Formato: Solo se aceptan archivos pdf");
                            $uploadOk = 0;
                        }
                        // Si cumple con todas las condiciones anteriores, es momento de subirlo
                        if ($uploadOk == 1) {
                            if (move_uploaded_file($_FILES["formato"]["tmp_name"], $target_file)) {
                                $flagOK = 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Formato: Error al cargar archivo");
                            }
                        }
                    }
                    // BOLETA DE CALIFICACIONES
                    if ($_FILES["boleta"]["error"] == 0 && $ValidaF2 == 1) {
                        //echo '<p>Ingresando Boleta</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $boleta = basename($_FILES["boleta"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($boleta,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/boleta/".$_matricula . "." . $FileType1;
                        //Revisar si el archivo existe
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        // Revisar el tamaño del archivo 1 Mb
                        if ($_FILES["boleta"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Boleta: El archivo excede el tamaño permitido: ".$_FILES["boleta"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "pdf" ) {
                            $errorflag += 1;
                            array_push ($errores, "Boleta: Solo se aceptan archivos pdf");
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 1) {       // El archivo se puede cargar
                            if (move_uploaded_file($_FILES["boleta"]["tmp_name"], $target_file)) {
                                $flagOK += 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Boleta: Error al cargar archivo");
                        }
                        }
                    }
                    // INGRESOS
                    if ($_FILES["ingresos"]["error"] == 0 && $ValidaF3 == 1) {
                        //echo '<p>Ingresando Comprobante de Ingresos</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $ingresos = basename($_FILES["ingresos"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($ingresos,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/ingresos/".$_matricula . "." . $FileType1;
                        //Revisar si el archivo existe
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        // Revisar el tamaño del archivo 1 Mb
                        if ($_FILES["ingresos"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Ingresos: El archivo excede el tamaño permitido: ".$_FILES["ingresos"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "pdf" ) {
                        $errorflag += 1;
                            array_push ($errores, "Ingresos: Solo se aceptan archivos pdf");
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 1) {       // El archivo se puede cargar
                            if (move_uploaded_file($_FILES["ingresos"]["tmp_name"], $target_file)) {
                                $flagOK += 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Ingresos: Error al cargar archivo");
                            }
                        }
                    }
                    // IDENTIFICACION OFICIAL
                    if ($_FILES["idoficial"]["error"] == 0 && $ValidaF4 == 1) {
                        //echo '<p>Ingresando Id</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $idoficial = basename($_FILES["idoficial"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($idoficial,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/idoficial/".$_matricula . "." . $FileType1;
                        //Revisar si el archivo existe
                        if (file_exists($target_file)) {
                             unlink($target_file);
                        }
                        // Revisar el tamaño del archivo 1 Mb
                        if ($_FILES["idoficial"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Id Oficial: El archivo excede el tamaño permitido: ".$_FILES["idoficial"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "pdf" ) {
                        $errorflag += 1;
                            array_push ($errores, "Id Oficial: Solo se aceptan archivos pdf");
                            $uploadOk = 0;
                        }                        echo '<p>Vamos a insertar</p>';
                        if ($uploadOk == 1) {       // El archivo se puede cargar
                            if (move_uploaded_file($_FILES["idoficial"]["tmp_name"], $target_file)) {
                                $flagOK += 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Id Oficial: Error al cargar archivo");
                        }
                        }
                    }
                    // PAGO ESTUDIO SOCIOECONOMICO
                    if ($_FILES["estudio"]["error"] == 0 && $ValidaF5 == 1) {
                        //echo '<p>Ingresando Comprobante de Pago</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $estudio = basename($_FILES["estudio"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($estudio,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/estudio/".$_matricula . "." . $FileType1;
                        //Revisar si el archivo existe
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        // Revisar el tamaño del archivo 1 Mb
                        if ($_FILES["estudio"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Estudio Socioeconómico: El archivo excede el tamaño permitido: ".$_FILES["estudio"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "pdf" ) {
                        $errorflag += 1;
                            array_push ($errores, "Estudio Socioeconómico: Solo se aceptan archivos pdf");
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 1) {       // El archivo se puede cargar
                            if (move_uploaded_file($_FILES["estudio"]["tmp_name"], $target_file)) {
                                $flagOK += 1;
                            } else { 
                                $errorflag += 1;
                                array_push ($errores, "Estudio Socioeconómico: Error al cargar archivo");
                        }
                        }
                    }
                // Validar si los archivos se subieron correctamente y no hay errores, entonces intentar actualizar la BD
                if ($flagOK == $numarchivos && $errorflag == 0) {
                    //Subir los datos del formulario
                    $conexionBD=new alumnos();
                    if ($_flag == 0) {
                        $result=$conexionBD->insert_beca ($_matricula, $_seccion, $_cicloact, $_ciclosig, $_grado, $_tipo);
                    } else {
                        $result=$conexionBD->update_beca ($_matricula, $_tipo, $_cicloact);
                    }
                    if (!$result) {
                        $errorflag += 1;
                        array_push ($errores, "Base de Datos: Error al actualizar los datos");
                    }

                }
                // Validar si se logró toda la actualización
                if ($flagOK != $numarchivos || $errorflag > 0) { // o no se subieron los archivos o no se actualizó la BD
                    echo '<h4>Errores encontrados:</h4>'."\n";
                    echo '<table id="errores">'."\n";
                    $max = sizeof($errores);
                    for($i = 0; $i < $max;$i++) {
                        $j = $i+1;
                        echo '<tr><td>'. $j .'</td><td>'.$errores[$i].'</td></tr>'."\n";
                    }
                    echo '</table><br>'."\n"; 
                    echo '<a href='.$_SERVER['HTTP_REFERER'].'>Regresar a formulario</a>'."\n";
                } else {    // Mostrar los datos que se subieron
                    echo '<h3>Solicitud Recibida</h3>'."\n";
                    echo '<table>'."\n";
                    echo '<tr><td>Matricula</td><td>'.$_matricula.'</td></tr>'."\n";
                    echo '<tr><td>Nombre</td><td>'.$_nombre.'</td></tr>'."\n";
                    echo '<tr><td>Ciclo de Reinscripción</td><td>'.$_ciclosig.'</td></tr>'."\n";
                    echo '<tr><td>Grado</td><td>'.$_grado.'</td></tr>'."\n";
                    echo '<tr><td>Tipo</td><td>'.tipobeca($_tipo).'</td></tr>'."\n";
                    if ($ValidaF1 == 1) { echo '<tr><td>Formato de Solicitud*</td><td>'.$formato.'</td></tr>'."\n";}
                    if ($ValidaF2 == 1) { echo '<tr><td>Boleta de Calificaciones*</td><td>'.$boleta.'</td></tr>'."\n";}
                    if ($ValidaF3 == 1) { echo '<tr><td>Comprobante de Ingresos*</td><td>'.$ingresos.'</td></tr>'."\n";}
                    if ($ValidaF4 == 1) {echo '<tr><td>Identificación Oficial*</td><td>'.$idoficial.'</td></tr>'."\n";}
                    if ($ValidaF5 == 1) {echo '<tr><td>Comprobante de Pago de Estudio Socioeconómico*</td><td>'.$estudio.'</td></tr>'."\n";}
                    echo '<tr><td>Fecha de Modificación</td><td>'.date("d").'/'.date("m").'/'.date("Y").'</td></tr>'."\n";
                    echo '</table><hr/>'."\n";
                    if ($numarchivos > 0) { '<p>* - Sujetos a revisión por parte del Comité de Becas</p>'."\n"; }
                    echo '<p>Espere comunicación por parte del comité de BECAS en los medios de contacto proporcionados en el Formato de Solicitud</p>';
                }
                }   //Fin de validación de matricula = usuario activo
            }       // Fin de validación de que se envio la matrícula en POST
        break;
        case 1:     // USUARIO - Validar el tipo de usuario
            echo '<h3>Bienvenido</h3>'."\n";
            echo '<p>No deberías estar aquí</p>'."\n";
            break;
        }           // Fin del Switch
} // fin del else de validación
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
