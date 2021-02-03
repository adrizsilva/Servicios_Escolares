<?php
error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("session.gc_maxlifetime","14400");
session_start();

include ("libs.php");           /* Librerias */
include ("dbconect.php");

// VALIDAR SI YA SE INICIO SESION
IF (isset($_SESSION['login'])&&($_SESSION['login'] == 1)) {
} else {        // Verificar si es la primera vez que envían el login
    $_SESSION['login'] = 0;
    $errores = array();
    if (isset($_POST['username']) && isset($_POST['psw'])) {    // Viene de un login
        $error = Ingresar($_POST['username'], $_POST['psw']); // Validarlo, si es false no existe el usuario
    }
}

headerfull_('Solicitud / Renovación de Becas');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    $fecha=0;
    // validar el tipo de usuario
    switch ($_SESSION['Type']) {
    case 0:     // ALUMNO
        // INICIALIZAMOS LOS VALORES DE REINSCRIPCION
        $GradoSig = $_SESSION['Grado']+1;
        if ($_SESSION['Seccion'] > 2) { $CicloSig = CICLOSIGS; } else { $CicloSig = CICLOSIGA; }
        if ($_SESSION['Seccion'] > 2) { $CicloAct = CICLOACTS; } else { $CicloAct = CICLOACTA; }
        // hacemos una consulta para verificar si hay datos previos.
        $conexionBD=new alumnos();
        $resultado=$conexionBD->lista_becas($_SESSION['Id'], $CicloAct);
        if (!$resultado) { 
            $flagfile = 0; // Es la primera vez que sube datos
            //echo '<p>NO existe un registro</p>';
        } else {    // tomamos los valores
            //echo '<p>SI existe un registro</p>';
            $flagfile = 1;
            foreach ($resultado as $registro) {
                $cicloact = $registro['CicloAct'];
                $ciclosig = $registro['CicloSig'];
                $status = $registro['Status'];
                $tipo = $registro['Tipo'];
                $fecha = $registro['Fecha'];
                $observaciones = $registro['Observaciones'];
            }
            // Validamos que documentos existen, ponemos un 1 en cada posición si existe el fichero
            $ficheros = array('0','0','0','0', '0');
            $directorio = "becas/".$_SESSION['Seccion'];
            $directorios = ["formato", "boleta", "ingresos", "idoficial", "estudio"];
            //$extensiones = ['pdf','jpg','jpeg','png'];
            for ($i = 0; $i < 5; $i++) {
                $target_file = $directorio . '/'.$directorios[$i]. '/'. $_SESSION['Id'].'.pdf';
                    if (file_exists($target_file)) { $ficheros[$i] = '1'; }
                }
        }
        
        if (isset($status)) {
            // Existe un registro, ¿Cómo va el proceso?
            switch ($status) {
                case 0:     // En proceso
                    echo '<h3>TRÁMITE EN PROCESO DE REVISIÓN</h3>';
                    echo '<p>'.$observaciones.'</p>';
                    break;
                case 1:     // Denegado
                    echo '<h3>TRÁMITE CON RESULTADO NEGATIVO</h3>';
                    break;
                case 2:     // Aceptado
                    echo '<h3>TRÁMITE CON RESULTADO FAVORABLE</h3>';
                    break;
            }
        } else {
            texto_becas($_SESSION['Seccion']);
        }
        
        ?>        
        <h3>Revisa la información y actualiza los datos necesarios</h3>
        <form id="beca" action="uploadbecas.php" method="post" enctype="multipart/form-data" >
         <div class="row gtr-uniform">
            <div class="col-3 col-12-small">
                Matricula <input id="matricula" name="matricula" type="text" value="<?php echo $_SESSION['Id'] ?>" readonly /> 
            </div>
            <div class="col-3 col-12-small">
                Sección <input id="seccion" name="seccion" type="text" value="<?php echo corto_seccion() ?>" readonly />
            </div>
            <div class="col-3 col-12-small">
                Ciclo Reinscripción <input id="ciclosig" name="ciclosig" type="text" value="<?php echo $CicloSig ?>" readonly /> 
            </div>
            <div class="col-3 col-12-small">
                Grado <input id="gradosig" name="gradosig" type="text" value="<?php echo $GradoSig ?>" readonly /> 
            </div>

            <div class="col-12">
                <input id="nombre" name="nombre" type="text" value="<?php echo $_SESSION['Nombres'] ?>" readonly /> 
            </div>

             <?php
            if ($flagfile == 1) {       // Ya existen archivos de este alumno.
                echo '<div class="col-12">'."\n";
                echo '<h4>Documentos Entregados anteriormente:</h4>'."\n";
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[0] == '1') { 
                    echo '<input type="checkbox" id="formato_" name="formato_" checked  disabled>'."\n".'<label for="formato_">Formato de Solicitud</label>'."\n";
                } else { 
                    echo '<input type="checkbox" id="formato_" name="formato_" disabled>'."\n".'<label for="formato_">Formato de Solicitud</label>'."\n"; 
                }
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[1] == '1') { 
                    echo '<input type="checkbox" id="boleta_" name="boleta_" checked disabled>'."\n".'<label for="boleta_">Boleta</label>'."\n";
                } else {
                    echo '<input type="checkbox" id="boleta_" name="boleta_" disabled>'."\n".'<label for="boleta_">Boleta</label>'."\n";
                }
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[2] == '1') { 
                    echo '<input type="checkbox" id="ingresos_" name="ingresos_" checked disabled>'."\n".'<label for="ingresos_">Comprobante de Ingresos</label>'."\n";
                } else {
                    echo '<input type="checkbox" id="ingresos_" name="ingresos_" disabled>'."\n".'<label for="ingresos_">Comprobante de Ingresos</label>'."\n";
                }
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[3] == '1') { 
                    echo '<input type="checkbox" id="idoficial_" name="idoficial_" checked disabled>'."\n".'<label for="idoficial_">Identificación Oficial</label>'."\n";
                } else {
                    echo '<input type="checkbox" id="idoficial_" name="idoficial_" disabled>'."\n".'<label for="idoficial_">Identificación Oficial</label>'."\n";
                }
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[4] == '1') { 
                    echo '<input type="checkbox" id="estudio_" name="estudio_" checked disabled>'."\n".'<label for="estudio_">Estudio Socioeconómico</label>'."\n";
                } else {
                    echo '<input type="checkbox" id="estudio_" name="estudio_" disabled>'."\n".'<label for="estudio_">Estudio Socioeconómico</label>'."\n";
                }
                echo '</div>'."\n";
                echo '<h4>Si lo requiere, suba los documentos solicitados en formato PDF, no mayores de 2 Mb</h4>'."\n";
            } else  {       // Aún no ha subido ningun archivo, hay que solicitarlos
                echo '<h4>A continuación suba los documentos solicitados en formato PDF, no mayores de 2 Mb</h4>'."\n";
            }
            echo '<div class="col-12">'."\n";
            echo '<select name="tipo" id="tipo" tabindex="1" required>';
            echo '<option value="" disabled';
            if (!isset($tipo)) { echo ' selected'; }
            echo '>Tipo de Beca solicitada</option>'."\n";
            echo '<option value="int" ';
            if ($tipo == 'int') { echo ' selected'; }
            echo '>Interna</option>'."\n";
            echo '<option value="sep" ';
            if ($tipo == 'sep') { echo ' selected'; }
            echo '>SEP</option>'."\n";
            echo '<option value="hno" ';
            if ($tipo == 'hno') { echo ' selected'; }
            echo '>Hermanos</option>'."\n";
            echo '</select>'."\n";
            ?>
            <hr/></div>
            <div class="col-6 col-12-small">
            <label>Formato de Solicitud</label>
            <input placeholder="Formato de Solicitud" id="formato" name="formato" tabindex="2" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/> 
            <hr/></div>
            <div class="col-6 col-12-small">
            <label>Boleta de Calificaciones</label>
            <input placeholder="Boleta de Calificaciones" id="boleta" name="boleta" tabindex="3" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/>  
            <hr/></div>
            <div class="col-6 col-12-small">
            <label>Comprobantes de Ingresos</label>
            <input placeholder="Comprobantes de Ingresos" id="ingresos" name="ingresos" tabindex="4" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/>  
            <hr/></div>
            <div class="col-6 col-12-small">
            <label>Identificación Oficial</label>
            <input placeholder="Identificación" id="idoficial" name="idoficial" tabindex="5" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/> 
            </div>
            <div class="col-6 col-12-small">
            <label>Pago Estudio Socioeconómico</label>
            <input placeholder="Pago Estudio Socioeconómico" id="estudio" name="estudio" tabindex="6" type="file" class="primary"/> 
            </div>
            <?php
                echo '<input id="Flag" name="Flag" type="hidden" value="'.$flagfile.'">';   // 0 para insert, 1 para update
                echo '<input id="cicloact" name="cicloact" type="hidden" value="'.$CicloAct.'">';
                echo '<input id="fecha" name="fecha" type="hidden" value="'.$fecha.'">';
            ?>
            <div class="col-12">
                <ul class="actions fit">
                    <li><input type="submit" tabindex="7" value="Solicitar" class="primary" /></li>
                </ul>
            </div>
        </div>
        </form>
        <?php
        break;
    case 1:     // USUARIO - Validar el tipo de usuario
        echo '<h3>Bienvenido</h3>'."\n";
        echo '<p>No deberías estar aquí</p>'."\n";
        break;
    }
} else {
    echo '<header class="major"><h2>Bienvenido al Sistema de Servicios <br>Escolares del Instituto Valladolid.</h2></header>'."\n";
    echo '<p><b>Ingresa con tus credenciales</b></p>'."\n";
    //   if (isset($error) && strlen($error)>2) { echo '<p>'.$error.'</p>'; }
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
