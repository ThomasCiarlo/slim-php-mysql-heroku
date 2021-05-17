<?php

class Mesa
{
    public $id;
    public $sector;
    public $numeroMesa;
    public $estado;

    public function CrearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (sector, numeroMesa,estado) VALUES (:sector, :numeroMesa, :estado)");
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':numeroMesa', $this->numeroMesa,PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado,PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, sector, numeroMesa,estado FROM mesas where fechaBaja is null");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($numeroMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, sector, numeroMesa,estado FROM mesas WHERE numeroMesa = :numeroMesa");
        $consulta->bindValue(':numeroMesa', $numeroMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($numeroMesa,$sector,$estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET numeroMesa = :numeroMesa, sector = :sector,estado = :estado WHERE numeroMesa = :numeroMesa");
        $consulta->bindValue(':numeroMesa', $numeroMesa, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $sector, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarMesa($numeroMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET fechaBaja = :fechaBaja WHERE numeroMesa = :numeroMesa");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':numeroMesa', $numeroMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}