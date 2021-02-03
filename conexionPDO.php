<?php
class Conexion extends PDO {
  private $tipo_de_base = 'mysql';
  private $host = 'localhost';
  private $nombre_de_base = 'Servicios';
  /*DATOS SERVIDOR LOCAL*/
  private $usuario = 'root';
  private $password = 'root';
  /* DATOS SERVIDOR HOSTGATOR*/
  // private $usuario='umvallae_uniuser';// aqui debes ingresar el nombre de usuario bd
  // private $password='45,w.;kxcVPE'; // password de acceso para el usuario de la bd

public function __construct() {
  try{
    parent::__construct($this->tipo_de_base.':host='.$this->host.';dbname='.$this->nombre_de_base, $this->usuario, $this->password);
  }catch(PDOException $e){
    echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
    exit;
  }
}

}

?>
