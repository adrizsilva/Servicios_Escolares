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

headerfull_('Reinscripcion');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    // validar el tipo de usuario
    switch ($_SESSION['Type']) {
    case 0:     // ALUMNO
        texto_reinscripcion($_SESSION['Seccion']);
        // hacemos una consulta para verificar si hay datos previos.
        $conexionBD=new alumnos();
        $resultado=$conexionBD->lista_alumnos_contacto($_SESSION['Id']);
        if (!$resultado) {
            $flagfile = 0;  // Es la primera vez que sube datos
            $flagdata = 0;
        } else {    // tomamos los valores
            $flagfile = 1;
            $flagdata = 0;
            foreach ($resultado as $registro) {
                $calle_ = $registro['Calle'];
                $colonia_ = $registro['Colonia'];
                $ciudad_ = $registro['Ciudad'];
                $estado_ = $registro['Estado'];
                $postal_ = $registro['Postal'];
                $tel1_ = $registro['TelFijo'];
                $cel1_ = $registro['Celular'];
            }
            // Validamos que documentos existen, ponemos un 1 en cada posición si existe el fichero
            $ficheros = array('0','0','0','0');
            $directorio = "reinscripcion/".$_SESSION['Seccion'];
            $directorios = ["ficha", "contrato", "idoficial", "domicilio"];
            $extensiones = ['pdf','jpg','jpeg','png'];
            for ($i = 0; $i < 4; $i++) {
                $target_file = $directorio . '/'.$directorios[$i]. '/'. $_SESSION['Id'];
                for ($j = 0; $j < 4; $j++) {
                    $target_fileext = $target_file . '.' . $extensiones[$j];
                    if (file_exists($target_fileext)) { $ficheros[$i] = '1'; }
                }
            }
        }
        // INICIALIZAMOS LOS VALORES DE REINSCRIPCION
        $GradoSig = $_SESSION['Grado']+1;
        if ($_SESSION['Seccion'] > 2) { $CicloSig = CICLOSIGS; } else { $CicloSig = CICLOSIGA; }
        if ($_SESSION['Seccion'] > 2) { $CicloAct = CICLOACTS; } else { $CicloAct = CICLOACTA; }
        ?>
        
        <h3>Revisa la información y actualiza los datos necesarios</h3>
        <form id="contact" action="uploaddatos.php" method="post" enctype="multipart/form-data" >
         <div class="row gtr-uniform">
            <div class="col-3 col-12-small">
                Matricula <input id="matricula" name="matricula" type="text" value="<?php echo $_SESSION['Id'] ?>" readonly /> 
            </div>
            <div class="col-3 col-12-small">
                Carrera <input id="carrera" name="carrera" type="text" value="<?php echo $_SESSION['Carrera'] ?>" readonly /> 
            </div>
           <div class="col-3 col-12-small">
                Ciclo Reinscripción <input id="CicloSig" name="ciclosig" type="text" value="<?php echo $CicloSig ?>" readonly /> 
            </div>
            <div class="col-3 col-12-small">
                Grado <input id="gradosig" name="gradosig" type="text" value="<?php echo $GradoSig ?>" readonly /> 
            </div>

            <div class="col-6 col-12-small">
                <input id="nombre" name="nombre" tabindex="1" type="text" value="<?php echo $_SESSION['Nombres'] ?>" readonly /> 
            </div>
            <div class="col-6 col-12-small">
                <input id="calle" name="calle" placeholder="Calle y número" tabindex="2" type="text" <?php if ($flagfile==1) { echo 'value="'.$calle_.'"'; } ?> onchange="acumular()" required/> 
            </div>
            <div class="col-6 col-12-small">
                <input id="colonia" name="colonia" placeholder="Colonia" tabindex="5" type="text" <?php if ($flagfile==1) { echo 'value="'.$colonia_.'"'; } ?> onchange="acumular()" required/> 
            </div>
            <div class="col-6 col-12-small">
                <input id="ciudad" name="ciudad" placeholder="Ciudad" tabindex="6" type="text" <?php if ($flagfile==1) { echo 'value="'.$ciudad_.'"'; } ?> onchange="acumular()" required/> 
            </div>
            <div class="col-6 col-12-small">
                <select name="estado" id="estado" tabindex="7" required>
                    <?php
                    if ($flagfile == 1) { $state = $estado_; } else { $state = '16';}
                    foreach (ESTADOS as list($clave, $valor)) {
                        echo '<option value="'.$clave.'"';
                        if ($clave == $state) { echo ' selected'; }
                        echo '>'.$valor.'</option>'."\n";
                    } ?>
				</select>
            </div>
            <div class="col-6 col-12-small">
                <input id="postal" name="postal" placeholder="Código Postal" tabindex="8" type="text" <?php if ($flagfile==1) { echo 'value="'.$postal_.'"'; } ?> onchange="acumular()" required/> 
            </div>
            <div class="col-6 col-12-small">
                <input id="tel1" name="tel1" placeholder="Teléfono fijo (sin guiones ni espacio)" pattern="[0-9]{10}" tabindex="9" type="tel" <?php if ($flagfile==1) { echo 'value="'.$tel1_.'"'; } ?> onchange="acumular()" required/>
            </div>
            <div class="col-6 col-12-small">
                <input id="cel1" name="cel1" placeholder="Teléfono móvil (sin guiones ni espacio)" pattern="[0-9]{10}" tabindex="10" type="tel" <?php if ($flagfile==1) { echo 'value="'.$cel1_.'"'; } ?> onchange="acumular()" required/>
            </div>
            <div class="col-6 col-12-small">
                <input  name="email" id="email" value="<?php echo $_SESSION['Correo'] ?>" placeholder="Correo electrónico" tabindex="11" type="email"  onchange="acumular()" required/>
            </div>
            <?php
            if ($flagfile == 1) {
                echo '<div class="col-12">'."\n";
                echo '<h4>Documentos Entregados anteriormente:</h4>'."\n";
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[0] == '1') { 
                    echo '<input type="checkbox" id="ficha_" name="ficha_" checked  disabled>'."\n".'<label for="ficha_">Ficha de Control Escolar</label>'."\n";
                } else { 
                    echo '<input type="checkbox" id="ficha_" name="ficha_" disabled>'."\n".'<label for="ficha_">Ficha de Control Escolar</label>'."\n"; 
                }
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[1] == '1') { 
                    echo '<input type="checkbox" id="contrato_" name="contrato_" checked disabled>'."\n".'<label for="contrato_">Contrato de Servicios Educativos</label>'."\n";
                } else {
                    echo '<input type="checkbox" id="contrato_" name="contrato_" disabled>'."\n".'<label for="contrato_">Contrato de Servicios Educativos</label>'."\n";
                }
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[2] == '1') { 
                    echo '<input type="checkbox" id="idoficial_" name="idoficial_" checked disabled>'."\n".'<label for="idoficial_">Identificación Oficial</label>'."\n";
                } else {
                    echo '<input type="checkbox" id="idoficial_" name="idoficial_" disabled>'."\n".'<label for="idoficial_">Identificación Oficial</label>'."\n";
                }
                echo '</div>'."\n";
                echo '<div class="col-6 col-12-small">'."\n";
                if ($ficheros[3] == '1') { 
                    echo '<input type="checkbox" id="compdom_" name="compdom_" checked disabled>'."\n".'<label for="compdom_">Comprobante de Domicilio</label>'."\n";
                } else {
                    echo '<input type="checkbox" id="compdom_" name="compdom_" disabled>'."\n".'<label for="compdom_">Comprobante de Domicilio</label>'."\n";
                }
                echo '</div>'."\n";
                echo '<h4>Si lo requiere, sube los documentos solicitados en formato PDF o JPG, no mayores de 2 Mb</h4>'."\n";
            } else  {
                echo '<h4>A continuación sube los documentos solicitados en formato PDF o JPG, no mayores de 2 Mb</h4>'."\n";
            }
            ?>
            <div class="col-6 col-12-small">
            <label>Ficha de Control Escolar</label>
            <input placeholder="Ficha de Control Escolar" id="ficha" name="ficha" tabindex="12" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/> 
            <hr/></div>
            <div class="col-6 col-12-small">
            <label>Contrato de Servicios Educativos</label>
            <input placeholder="Contrato de Servicios" id="contrato" name="contrato" tabindex="13" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/>  
            <hr/></div>
            <div class="col-6 col-12-small">
            <label>Identificación Oficial</label>
            <input placeholder="Identificación" id="idoficial" name="idoficial" tabindex="14" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/> 
            </div>
            <div class="col-6 col-12-small">
            <label>Comprobante de Domicilio</label>
            <input placeholder="Comprobante de domicilio" id="compdomicilio" name="compdomicilio" tabindex="15" type="file" <?php if ($flagfile == 0) {echo 'required';} ?>/> 
            </div>
            <?php
                echo '<input id="Flag" name="Flag" type="hidden" value="'.$flagfile.'">';   // 0 para insert, 1 para update
                echo '<input id="flagdata" name="flagdata" type="hidden">';   // mayor a 0 si se modifico algún dato
                echo '<input id="cicloact" name="cicloact" type="hidden" value="'.$CicloAct.'">';
            ?>
            <div class="col-12">
                <ul class="actions fit">
                    <li><input type="submit" value="Actualizar" class="primary" /></li>
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
