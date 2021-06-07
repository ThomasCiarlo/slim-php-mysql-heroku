<?php

class Pedido
{
    public $id;
    public $mesa;
    public $listaPedido;
    public $usuario;
    public $estado;
    public $codPedido;
    public $horaDeInicio;
    public $tiempoFinalizacion;
    public $importe;

    public function CrearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (mesa, listaPedido,usuario,estado,codPedido,horaDeInicio, importe) VALUES (:mesa, :listaPedido, :usuario, :estado, :codPedido, :horaDeInicio, :importe)");
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':listaPedido', $this->listaPedido,PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario,PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado,PDO::PARAM_STR);
        $consulta->bindValue(':codPedido', $this->codPedido,PDO::PARAM_INT);
        $consulta->bindValue(':horaDeInicio', $this->horaDeInicio, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe,PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mesa, listaPedido,usuario,estado,codPedido,horaDeInicio, importe FROM pedidos where fechaBaja is null");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mesa, listaPedido,usuario,estado,codPedido,horaDeInicio, importe FROM pedidos WHERE codPedido = :codPedido");
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPedidoPorEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mesa, listaPedido,usuario,estado,codPedido,horaDeInicio FROM pedidos WHERE estado = :estado LIMIT 1");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function borrarPedido($codPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE codPedido = :codPedido");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function ActualizarEstado($id,$estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
    }
}