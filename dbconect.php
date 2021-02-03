<?php
require_once 'conexionPDO.php';

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
// funciones de BD relacionadas con los usuarios
class Usuario {
  private $id;
  private $pass;
  private $type;
  private $privs;
  private $Nombre;
  private $Apellido;

//*************************************************************************************************
// Funcion:     login
// Descripción: Consulta las bases de datos para verificar si el usuario existe
// Parametros:  usuario, contraseña del formulario
//*************************************************************************************************

public function login($id,$pass){
  $conn = new Conexion();
  //Preparamos la consulta
  $stmt = $conn->prepare ("SELECT * FROM Usuarios WHERE Id = :ss AND Pass = :pp");
 $stmt->execute(array(':ss' => $id ,':pp'=> $pass));
 $stmt->setFetchMode(PDO::FETCH_ASSOC);
 if ($registro = $stmt->fetch()) {
   $usuario = array(
     'Id' => $registro['Id'],
     'Pass' => $registro['Pass'],
     'Type' => $registro['Type'],
     'Privs' => $registro['Privileges']
   );
   return $usuario;
 } //fin while
 // echo 'Error en la consulta';
 return false;
}

//*************************************************************************************************
// Funcion:     datosAlummno
// Descripción: Si el usuario existe y su type = 0, es alumno, entonces obten sus datos de la 
//              tabla correspondiente
// Parametros:  Matricula
//*************************************************************************************************

public function datosAlumno($Id){
 $alumno=null;
 $conn=new Conexion();
 try {
  $comando = $conn->prepare ("SELECT Nombre, Apellidos, Grado, Grupo, Seccion, IdGrupo, Correo FROM DatosIDAlumno WHERE Id = :ss");
  $comando->execute(array(':ss' => $Id));
  if($row = $comando->fetch(PDO::FETCH_ASSOC)) {
    $alumno=array(
      'Nombres'=>$row['Nombre'],
      'Apellidos'=>$row['Apellidos'],
      'Grado'=>$row['Grado'],
      'Grupo'=>$row['Grupo'],
      'Seccion'=>$row['Seccion'],
      'IdGrupo'=>$row['IdGrupo'],
      'Correo'=>$row['Correo']
    );
    return $alumno;
  } else {
    echo 'No existen datos del alumno';
    return false;
  }
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
}

//*************************************************************************************************
// Funcion:     datosUsuario
// Descripción: Si el usuario existe y su type = 1, es usuario (docente o administrador), 
//              entonces obten sus datos de la tabla correspondiente
// Parametros:  username
//*************************************************************************************************

public function datosUsuario($Id){
 $usuario=null;
 $conn=new Conexion();
 try {
  $comando = $conn->prepare ("SELECT Nombres, Seccion, Grado, Carrera FROM DatosIDUsuario WHERE Id = :ss");
  $comando->execute(array(':ss' => $Id));
  if($row = $comando->fetch(PDO::FETCH_ASSOC)) {
    $usuario=array(
      'Nombres'=>$row['Nombres'],
      'Seccion'=>$row['Seccion'],
      'Grado'=>$row['Grado'],
      'Carrera'=>$row['Carrera'],
    );
    return $usuario;
  } else {
    echo 'No existen datos del Usuario';
    return false;
  }
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
}

}

//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
// funciones relacionadas con los alumnos
class alumnos {
  private $id;
  private $nombre;
  private $apellidos;
  private $grado;
  private $grupo;
  private $seccion;
  // estos son para reinscripcion
  private $calle;
  private $colonia;
  private $ciudad;
  private $estado;
  private $postal;
  private $telfijo;
  private $celular;
  private $correo;
  private $ciclo;

public function lista_alumnos($Grupo){
    $listado=null;
    $conn=new Conexion();
    try {
        $stmt = $conn->prepare ("SELECT Id, Nombre, Apellidos, Grado, Grupo, Seccion, IdGrupo, Correo FROM DatosIDAlumno WHERE IdGrupo = :gg");
        $stmt->bindParam(':gg', $Grupo);
        $stmt->execute();
        $resultado= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Actualiza los datos para determinar el grado al que se reinscribe el alumno
//public function 

// Consulta los datos de contacto para el formulario de reinscripcion
public function lista_alumnos_contacto($Matricula) {
    $listado=null;
    $conn=new Conexion();
    try {
        $stmt = $conn->prepare ("SELECT Calle, Colonia, Ciudad, Estado, Postal, TelFijo, Celular, Correo FROM ContactoAlumno WHERE Id = :gg");
        $stmt->bindParam(':gg', $Matricula);
        $stmt->execute();
        $resultado= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Actualiza los datos de contacto en el formulario de reinscripcion
public function update_alumnos_contacto ($Matricula, $Calle, $Colonia, $Ciudad, $Estado, $Postal, $TelFijo, $Celular, $Correo) {
    try {
        $conn = new Conexion();
        $sql = "UPDATE ContactoAlumno SET Calle= :a, Colonia= :b, Ciudad=:c, Estado=:d, Postal=:e, TelFijo=:f, Celular=:g, Correo=:h WHERE Id=:i";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':a', $Calle);
        $stmt->bindParam(':b', $Colonia);
        $stmt->bindParam(':c', $Ciudad);
        $stmt->bindParam(':d', $Estado);
        $stmt->bindParam(':e', $Postal);
        $stmt->bindParam(':f', $TelFijo);
        $stmt->bindParam(':g', $Celular);
        $stmt->bindParam(':h', $Correo);
        $stmt->bindParam(':i', $Matricula);
        if ($stmt->execute()) {
            return true;
        } else {
            print_r($stmt->errorInfo());
            return false;
        }
    } catch(PDOException $e) {
        echo "Error en conexion: ".$e->getMessage();
        return false;
    }
}

// Actualiza los datos de contacto en el formulario de reinscripcion
public function update_correo ($Matricula, $Correo) {
    try {
        $conn = new Conexion();
        $sql = "UPDATE DatosIDAlumno SET Correo=? WHERE Id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array($Correo, $Matricula))) {
            $_SESSION['Correo'] = $Correo;
            return true;
        } else {    // error al actualizar el correo en la tabla principal
            print_r($stmt->errorInfo());
            return false;
        }
        
    } catch(PDOException $e) {
        echo 'Error: '. $e->getMessage();
        return false;
    }
return true;
}


// Si el registro no existe, se tiene que insertar
public function insert_alumnos_contacto ($Matricula, $Calle, $Colonia, $Ciudad, $Estado, $Postal, $TelFijo, $Celular, $Correo) {
    try {
        $conn = new Conexion();
        $sql = "INSERT INTO ContactoAlumno (Id, Calle, Colonia, Ciudad, Estado, Postal, TelFijo, Celular, Correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array($Matricula, $Calle, $Colonia, $Ciudad, $Estado, $Postal, $TelFijo, $Celular, $Correo))) {
            return true;
        } else {        //Problema en inserción
            print_r($stmt->errorInfo());
            return false;
        }
    } catch(PDOException $e) {
        echo "Error en conexion: ".$e->getMessage();
        return false;
    }
}
    
// Actualizar bitácora de reinscripcion
public function insert_reinscripcion ($Matricula, $Seccion, $CicloAct, $CicloSig, $Grado) {
    try {
        $conn = new Conexion();
        $sql = "INSERT INTO Reinscripciones (Id, Seccion, CicloAct, CicloSig, Grado, Fecha) VALUES (?, ?, ?, ?, ?, now())";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array($Matricula, $Seccion, $CicloAct, $CicloSig, $Grado))) {
            return true;
        } else {        //Problema en inserción
            print_r($stmt->errorInfo());
            return false;
        }
    } catch(PDOException $e) {
        echo "Error en conexion: ".$e->getMessage();
        return false;
    }
}

// Actualiza Es Status de la solicitud de Beca de un alumno
public function status_reinscripcion ($Matricula, $Status, $CicloAct) {
    try {
        $conn = new Conexion();
        $sql = "UPDATE Reincripciones SET Status=?, Fecha=now() WHERE Id=? and CicloAct=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array($Status, $Matricula, $CicloAct))) {
            return true;
        } else {    // error al actualizar el Status
            print_r($stmt->errorInfo());
            return false;
        }
        
    } catch(PDOException $e) {
        echo 'Error: '. $e->getMessage();
        return false;
    }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS BECAS 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Consulta la bitácora de becas para saber si el alumno ha intentado llenar los datos de registro
public function lista_becas($Matricula, $Ciclo) {
    $listado=null;
    $conn=new Conexion();
    try {
        $stmt = $conn->prepare ("SELECT Id, Seccion, CicloAct, CicloSig, Grado, Tipo, Status, Fecha, Observaciones FROM Becas WHERE Id = :gg and CicloAct = :cc");
        $stmt->bindParam(':gg', $Matricula);
        $stmt->bindParam(':cc', $Ciclo);
        $stmt->execute();
        $resultado= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Actualizar la bitácora cuando el alumno ha subido datos
public function insert_beca ($Matricula, $Seccion, $CicloAct, $CicloSig, $Grado, $Tipo) {
    try {
        $conn = new Conexion();
        $sql = "INSERT INTO Becas (Id, Seccion, CicloAct, CicloSig, Grado, Tipo, Fecha) VALUES (?, ?, ?, ?, ?, ?, now())";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array($Matricula, $Seccion, $CicloAct, $CicloSig, $Grado, $Tipo))) {
            return true;
        } else {        //Problema en inserción
            print_r($stmt->errorInfo());
            return false;
        }
    } catch(PDOException $e) {
        echo "Error en conexion: ".$e->getMessage();
        return false;
    }
}

// Actualiza la fecha de la solicitud de Beca de un alumno
public function update_beca ($Matricula, $Tipo, $Cicloact) {
    try {
        $conn = new Conexion();
        $sql = "UPDATE Becas SET Tipo=?, Fecha=now() WHERE Id=? and Cicloact=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array($Tipo, $Matricula, $Cicloact))) {
            return true;
        } else {    // error al actualizar el Status
            print_r($stmt->errorInfo());
            return false;
        }
        
    } catch(PDOException $e) {
        echo 'Error: '. $e->getMessage();
        return false;
    }
}

// Actualiza Es Status de la solicitud de Beca de un alumno
public function status_beca ($Matricula, $Status, $Fecha) {
    try {
        $conn = new Conexion();
        $sql = "UPDATE Becas SET Status=? WHERE Id=? and Fecha=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute(array($Status, $Matricula, $Fecha))) {
            return true;
        } else {    // error al actualizar el Status
            print_r($stmt->errorInfo());
            return false;
        }
        
    } catch(PDOException $e) {
        echo 'Error: '. $e->getMessage();
        return false;
    }
}


}
  



//*************************************************************************************************************************
//*************************************************************************************************************************
//*************************************************************************************************************************
//*************************************************************************************************************************
//*************************************************************************************************************************
// funciones para los titulares y usuarios
class Titular {
  private $IdUsuario;
  private $Ciclo;
  private $IdGrupo;
  private $Consecutivo;


public function lista_grupos($Usuario){
    $listado=null;
    $conn=new Conexion();
    try {
        $stmt = $conn->prepare ("SELECT IdUsuario, Ciclo, IdGrupo, Consecutivo FROM Titulares WHERE IdUsuario = :gg");
        $stmt->bindParam(':gg', $Usuario);
        $stmt->execute();
        $resultado= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
  
  
}

//*************************************************************************************************************************
//*************************************************************************************************************************
//*************************************************************************************************************************
//*************************************************************************************************************************
//*************************************************************************************************************************
// Se utiliza para los avisos de la página index
class aviso {
private $Consecutivo;
private $Seccion;
private $Grado;
private $Contenido;
private $Url;
private $Imagen;
private $Usuario;
private $Fecha_inicio;
private $Fecha_fin;
private $Activo;


//*************************************************************************************************
// Funcion:     getAvisos
// Descripción: Una vez que ingresó, buscar los avisos correspondientes a su sección o generales
// Parametros:  Seccion
//*************************************************************************************************

public function leer_avisos($Seccion, $Grado){
    $aviso=null;
    $conn=new Conexion();
    try {
        $stmt = $conn->prepare ("SELECT Seccion, Grado, Titulo, Contenido, Url, Imagen, Usuario FROM Avisos WHERE Seccion = :ss and Grado = 0 or Grado = :pp and Activo = 'Si'");
        $stmt->bindParam(':ss', $Seccion);
        $stmt->bindParam(':pp', $Grado);
        $stmt->execute();
        $resultado= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}



}


?>
