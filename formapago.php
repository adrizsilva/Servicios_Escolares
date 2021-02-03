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

headerfull_('Formas de pago');

/* ---------------- AQUI COMIENZA LA SECCION CENTRAL DE INFORMACION -----------------------*/
if ($_SESSION['login'] == 1) { // realizó login exitoso
    // validar si es alumno
    if ($_SESSION['Type'] == 0) { // Es alumno
        ?>
        <!-- Section -->
        <section>
        <!--<header class="major"><h2>Formas de Pago</h2></header>-->
        <div class="features">
		<article>
			<span class="icon solid fa-comments-dollar"></span>
			<div class="content">
			<h3>Transferencia electrónica</h3>
			<?php 
			if ($_SESSION['Seccion']<2) { // Preescolar y Primaria
			?>       
			<p>IMPULSORA CULTURAL VALLADOLID, A.C.<br>
			CLABE: 072470008750130259<br>
			BANORTE<br>
			Correo para notificaciones: 
			<a href="mailto:icvacpadres@gmail.com"> icvacpadres@gmail.com</a>
			<?php
			} else { // Secundaria, Preparatoria y Universidad
			?>                            
            <p>PATRONATO E IMPULSO EDUCATIVO, A.C.<br>
			CLABE: 072470008750130178<br>
			BANORTE<br>
			Correo para notificaciones:
			<a href="mailto:pieacpadres@gmail.com"> pieacpadres@gmail.com</a><br>
			<?php } ?>
			Especificar el Nombre Completo del Alumno</p>
            </div>
        </article>
        <?php
        if ($_SESSION['Seccion']<2) { // Preescolar y Primaria?>       
        <article>
			<span class="icon solid fa-money-check"></span>
			<div class="content">
			<h3>Depósito Bancario Cuenta de Cheques</h3>
            <p>IMPULSORA CULTURAL VALLADOLID, A.C.<br>
			CTA. 0875013025<br>
			BANORTE<br>
            Enviar el comprobante correspondiente <a href="mailto:icvacpadres@gmail.com">icvacpadres@gmail.com </a> especificando Nombre Completo del ALUMNO, nivel y grado para poder aplicar correctamente el pago en Sistema</p>
			</div>
		</article>
		<?php } // ¿Se realiza deposito bancario a cuenta de cheques? ?>                            
        <article>
            <span class="icon solid fa-school"></span>
            <div class="content">
            <h3>Pago en la Institución</h3>
            <p>En horario de 8:00hrs a 13:00hrs de Lunes a Viernes.<br>Los pagos que se realizan aqui puede ser en efectivo, cheque o tarjeta de crédito.</p>
            </div>
        </article>
		<article>
			<span class="icon solid fa-money-bill-wave"></span>
			<div class="content">
			<h3>Depósito en BANORTE con ficha referenciada</h3>
			<p>Puede realizar su pago en ventanilla en Banorte con los <a href='recibos.php'>Recibos de pago</a>.</p>
            </div>
		</article>

		</div>
		</section>
	<?php
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
