<?php

class Producto
{
    public $id;
    public $descripcion;
    public $tipo;
    public $stock;
    public $importe;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (descripcion, tipo,stock,importe) VALUES (:descripcion, :tipo,:stock,:importe)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, tipo,stock,importe FROM productos where fechaBaja is null");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($descripcion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, tipo,stock,importe FROM productos WHERE descripcion = :descripcion");
        $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerProductoID($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, tipo,stock,importe FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificarProducto($descripcion,$tipo,$stock,$id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET descripcion = :descripcion, tipo = :tipo, stock = :stock WHERE id = :id");
        $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $stock, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProducto($descripcion)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET fechaBaja = :fechaBaja WHERE descripcion = :descripcion");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}