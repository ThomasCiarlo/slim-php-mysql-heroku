<?php

class Usuario
{
    public $id;
    public $usuario;
    public $clave;
    public $puesto;
    public $estado;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave,puesto,estado) VALUES (:usuario, :clave,:puesto,:estado)");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave);
        $consulta->bindValue(':puesto', $this->puesto);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave,puesto FROM usuarios where fechaBaja is null");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave,puesto FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario($nombre,$clave,$puesto,$id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave, puesto = :puesto WHERE id = :id");
        $consulta->bindValue(':usuario', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $puesto, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function obtenerUserLogin($usuario,$clave)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario ,puesto FROM usuarios where fechaBaja is null and usuario = :usuario and clave = :clave");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function InsertarEncuesta($NotaRestaurante,$NotaMozo,$NotaMesa,$NotaCocinero,$Descripcion,$Pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (NotaRestaurante, NotaMozo, NotaMesa, NotaCocinero, Descripcion, Pedido, Fecha) VALUES (:NotaRestaurante, :NotaMozo, :NotaMesa, :NotaCocinero, :Descripcion, :Pedido, :Fecha)");
        $consulta->bindValue(':NotaRestaurante', $NotaRestaurante, PDO::PARAM_STR);
        $consulta->bindValue(':NotaMozo', $NotaMozo, PDO::PARAM_STR);
        $consulta->bindValue(':NotaMesa', $NotaMesa, PDO::PARAM_STR);
        $consulta->bindValue(':NotaCocinero', $NotaCocinero, PDO::PARAM_STR);
        $consulta->bindValue(':Descripcion', $Descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':Pedido', $Pedido, PDO::PARAM_STR);
        $consulta->bindValue(':Fecha', date("Y-m-d"), PDO::PARAM_STR);
        $consulta->execute();

        return true;
    }
}