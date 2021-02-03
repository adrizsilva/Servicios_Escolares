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
$ValidaF1 = 0;          // Se envío Ficha
$ValidaF2 = 0;          // Se envío Contrato
$ValidaF3 = 0;          // Se envío id
$ValidaF4 = 0;          // Se envío Comprobante
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

headerfull_('Reinscripcion');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
// verifica que todos los datos vengan en el formulario y no sean vacios, en caso contrario debe Regresar al formulario
$Valida_POST = (isset($_POST['calle']) && !empty($_POST['calle'])) &&
               (isset($_POST['colonia']) && !empty($_POST['colonia'])) &&
               (isset($_POST['ciudad']) && !empty($_POST['ciudad'])) &&
               (isset($_POST['estado']) && !empty($_POST['estado'])) &&
               (isset($_POST['postal']) && !empty($_POST['postal'])) &&
               (isset($_POST['tel1']) && !empty($_POST['tel1'])) &&
               (isset($_POST['cel1']) && !empty($_POST['cel1'])) &&
               (isset($_POST['ciclosig']) && !empty($_POST['ciclosig'])) &&
               (isset($_POST['gradosig']) && !empty($_POST['gradosig']));
    if (is_uploaded_file($_FILES['ficha']['tmp_name'])) { $ValidaF1 = 1; }
    if (is_uploaded_file($_FILES['contrato']['tmp_name'])) { $ValidaF2 = 1; }    
    if (is_uploaded_file($_FILES['idoficial']['tmp_name'])) { $ValidaF3 = 1; }
    if (is_uploaded_file($_FILES['compdomicilio']['tmp_name'])) { $ValidaF4 = 1; }
    $numarchivos = $ValidaF1+$ValidaF2+$ValidaF3+$ValidaF4;
    
    if ($Valida_POST == FALSE) {
        echo '<script type="text/javascript">'."\n";
        echo 'alert("Los datos están incompletos, por favor revisa el formulario nuevamente");'."\n"; 
        echo 'window.location = "reinscripcion.php"'."\n"; 
        echo '</script>'."\n";
        header("Location: reinscripcion.php");
    } else  {
        // validar el tipo de usuario       
        switch ($_SESSION['Type']) {
            case 0:     // ALUMNO Son los únicos que pueden subir sus datos
            if (isset($_POST['matricula'])) {
                //Recuperamos datos del formulario
                $_matricula = $_POST['matricula'];
                $_nombre = $_POST['nombre'];
                $_carrera = $_POST['carrera'];
                $_correo = $_POST['email'];
                $_calle = htmlentities($_POST['calle']);
                $_colonia = htmlentities($_POST['colonia']);
                $_ciudad = htmlentities($_POST['ciudad']);
                $_estado = htmlentities($_POST['estado']);
                $_postal = htmlentities($_POST['postal']);
                $_tel1 = htmlentities($_POST['tel1']);
                $_cel1 = htmlentities($_POST['cel1']);
                $_ciclosig = htmlentities($_POST['ciclosig']);
                $_cicloact = htmlentities($_POST['cicloact']);
                $_gradosig = htmlentities($_POST['gradosig']);
                $_flagdata = $_POST['flagdata'];        // hay cambios en los datos registrados
                if ($_SESSION['Seccion'] > 2) { $_cicloact = CICLOACTS; } else { $_cicloact = CICLOACTA; }
                
                $_flag = $_POST['Flag'];    // 0 para insert, 1 para update
                // --------- VALIDAMOS QUE EL USUARIO CORRESPONDA CON LOS DATOS ----------------
                if ($_matricula == $_SESSION['Id'] ) {
                    // Determinar el directorio en función a la sección
                    $target_dir = "reinscripcion/".$_SESSION['Seccion'];
                // REVISAMOS LOS ARCHIVOS --------------------------------------------------------
                    // FICHA DE CONTROL ESCOLAR       
                    if ($_FILES["ficha"]["error"] == 0 && $ValidaF1 == 1) {
                        echo '<p>Ingresando Ficha</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $ficha = basename($_FILES["ficha"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($ficha,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/ficha/".$_matricula . "." . $FileType1;    // La ficha se renombra a la matricula del alumno
                        //Revisar si el archivo existe
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        // Revisar el tamaño del archivo 1 Mb
                        if ($_FILES["ficha"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Ficha: El archivo excede el tamaño permitido: ".$_FILES["ficha"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "jpg" && $FileType1 != "png" && $FileType1 != "jpeg" && $FileType1 != "pdf" ) {
                            $errorflag += 1;
                            array_push ($errores, "Ficha: Solo se aceptan archivos jpg, png, jpeg o pdf");
                            $uploadOk = 0;
                        }
                        // Si cumple con todas las condiciones anteriores, es momento de subirlo
                        if ($uploadOk == 1) {
                            if (move_uploaded_file($_FILES["ficha"]["tmp_name"], $target_file)) {
                                $flagOK = 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Ficha: Error al cargar archivo");
                        }
                        // Ajustamos los errores si viene de un update
                        }
                    }
                    // CONTRATO DE SERVICIOS ESCOLARES
                    if ($_FILES["contrato"]["error"] == 0 && $ValidaF2 == 1) {
                        echo '<p>Ingresando Contrato</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $contrato = basename($_FILES["contrato"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($contrato,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/contrato/".$_matricula . "." . $FileType1;
                        //Revisar si el archivo existe
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        // Revisar el tamaño del archivo 1 Mb
                        if ($_FILES["contrato"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Contrato: El archivo excede el tamaño permitido: ".$_FILES["contrato"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "jpg" && $FileType1 != "png" && $FileType1 != "jpeg" && $FileType1 != "pdf" ) {
                            $errorflag += 1;
                            array_push ($errores, "Contrato: Solo se aceptan archivos jpg, png, jpeg o pdf");
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 1) {       // El archivo se puede cargar
                            if (move_uploaded_file($_FILES["contrato"]["tmp_name"], $target_file)) {
                                $flagOK += 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Contrato: Error al cargar archivo");
                        }
                        }
                    }
                    // IDENTIFICACION OFICIAL
                    if ($_FILES["idoficial"]["error"] == 0 && $ValidaF3 == 1) {
                        echo '<p>Ingresando Id</p>';
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
                        if($FileType1 != "jpg" && $FileType1 != "png" && $FileType1 != "jpeg" && $FileType1 != "pdf" ) {
                            $errorflag += 1;
                            array_push ($errores, "Id Oficial: Solo se aceptan archivos jpg, png, jpeg o pdf");
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 1) {       // El archivo se puede cargar
                            if (move_uploaded_file($_FILES["idoficial"]["tmp_name"], $target_file)) {
                                $flagOK += 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Id Oficial: Error al cargar archivo");
                        }
                        }
                    }
                    // COMPROBANTE DE DOMICILIO
                    if ($_FILES["compdomicilio"]["error"] == 0 && $ValidaF4 == 1) {
                        echo '<p>Ingresando Comprobante</p>';
                        $uploadOk = 1;  // inicializamos banderas de subida
                        $compdomicilio = basename($_FILES["compdomicilio"]["name"]);
                        //Renombrar los archivos
                        $FileType1 = strtolower(pathinfo($compdomicilio,PATHINFO_EXTENSION));
                        $target_file = $target_dir. "/domicilio/".$_matricula . "." . $FileType1;
                        //Revisar si el archivo existe
                        if (file_exists($target_file)) {
                            unlink($target_file);
                        }
                        // Revisar el tamaño del archivo
                        if ($_FILES["compdomicilio"]["size"] > MAXFILESIZE) {  
                            $errorflag += 1;
                            array_push ($errores, "Comprobante Domicilio: El archivo excede el tamaño permitido: ".$_FILES["compdomicilio"]["size"]);
                            $uploadOk = 0;
                        }
                        // Revisar el tipo de archivo
                        if($FileType1 != "jpg" && $FileType1 != "png" && $FileType1 != "jpeg" && $FileType1 != "pdf" ) {
                            $errorflag += 1;
                            array_push ($errores, "Comprobante Domicilio: Solo se aceptan archivos jpg, png, jpeg o pdf");
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 1) {       // El archivo se puede cargar
                            if (move_uploaded_file($_FILES["compdomicilio"]["tmp_name"], $target_file)) {
                                $flagOK += 1;
                            } else {
                                $errorflag += 1;
                                array_push ($errores, "Comprobante Domicilio: Error al cargar archivo");
                        }
                        }
                    }
                // Validar si los archivos se subieron correctamente y no hay errores, entonces intentar actualizar la BD
                if ($flagOK == $numarchivos && $errorflag == 0) {
                    //Subir los datos del formulario
                    $conexionBD=new alumnos();
                    if ($_flag == 0) {
                        $result=$conexionBD->insert_alumnos_contacto ($_matricula, $_calle, $_colonia, $_ciudad, $_estado, $_postal, $_tel1, $_cel1, $_correo);
                        if ($_correo != $_SESSION['Correo']) { 
                            echo '<p>Actualizar correo</p>';
                            $mail=$conexionBD->update_correo($_matricula, $_correo); 
                            }   // Si son distintos actualizalo en la otra tabla
                    } else  {
                        $result=$conexionBD->update_alumnos_contacto ($_matricula, $_calle, $_colonia, $_ciudad, $_estado, $_postal, $_tel1, $_cel1, $_correo);
                        if ($_correo != $_SESSION['Correo']) { 
                            echo '<p>Actualizar correo</p>';
                            $mail=$conexionBD->update_correo($_matricula, $_correo); 
                        } 
                    }
                    if ($_POST['flagdata'] > 0) {
                            $conexionBD->insert_reinscripcion ($_matricula, $_SESSION['Seccion'], $_cicloact, $_ciclosig, $_gradosig);
                    }
                    if (!$result) {
                        echo '<p>Algo fallo</p>';
                        $errorflag += 1;
                        array_push ($errores, "Base de Datos: Error al actualizar los datos");
                    }

                }
                // Validar si se logró toda la actualización
                if ($flagOK != $numarchivos || $errorflag > 0) { // o no se subieron los archivos o no se actualizó la BD
                    echo '<h4>Errores encontrados:</h4>'."\n";
                    echo '<P>fLAG: '.$flagOK.'</p><p> ErrorFlag: '.$errorflag.'</p><p> NumArchivos: '.$numarchivos.'</p>';
                    echo '<table id="errores">'."\n";
                    $max = sizeof($errores);
                    for($i = 0; $i < $max;$i++) {
                        $j = $i+1;
                        echo '<tr><td>'. $j .'</td><td>'.$errores[$i].'</td></tr>'."\n";
                    }
                    echo '</table><br>'."\n"; 
                    echo '<a href='.$_SERVER['HTTP_REFERER'].'>Regresar a formulario</a>'."\n";
                } else {    // Mostrar los datos que se subieron
                    echo '<h3>Actualización completa</h3>'."\n";
                    echo '<table>'."\n";
                    echo '<tr><td>Matricula</td><td>'.$_matricula.'</td></tr>'."\n";
                    echo '<tr><td>Nombre</td><td>'.$_nombre.'</td></tr>'."\n";
                    echo '<tr><td>Correo Electrónico</td><td>'.$_correo.'</td></tr>'."\n";
                    echo '<tr><td>Calle</td><td>'.$_calle.'</td></tr>'."\n";
                    echo '<tr><td>Colonia</td><td>'.$_colonia.'</td></tr>'."\n";
                    echo '<tr><td>Ciudad</td><td>'.$_ciudad.'</td></tr>'."\n";
                    echo '<tr><td>Estado</td><td>'.nomestado($_estado).'</td></tr>'."\n";
                    echo '<tr><td>Teléfono</td><td>'.$_tel1.'</td></tr>'."\n";
                    echo '<tr><td>Celular</td><td>'.$_cel1.'</td></tr>'."\n";
                    if ($ValidaF1 == 1) { echo '<tr><td>Ficha de Control Escolar*</td><td>'.$ficha.'</td></tr>'."\n";}
                    if ($ValidaF2 == 1) { echo '<tr><td>Contrato de Servicios*</td><td>'.$contrato.'</td></tr>'."\n";}
                    if ($ValidaF3 == 1) { echo '<tr><td>Identificación Oficial*</td><td>'.$idoficial.'</td></tr>'."\n";}
                    if ($ValidaF4 == 1) {echo '<tr><td>Comprobante de Domicilio*</td><td>'.$compdomicilio.'</td></tr>'."\n";}
                    echo '<tr><td>Fecha de Modificación</td><td>'.date("d").'/'.date("m").'/'.date("Y").'</td></tr>'."\n";
                    echo '</table><hr/>'."\n";
                    if ($numarchivos > 0) { '<p>* - Sujetos a revisión por parte de Control Escolar</p>'."\n"; }
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
