<?php

class Admin
{

    public static function CantidadOperaciones($sector,$fecha)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) from produccion p LEFT JOIN usuarios u ON u.id = p.Idempleado LEFT JOIN sectores s on s.id = u.puesto where s.sector = :sector and p.fechaDeProduccion = :fecha");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }


    public static function CantidadOperacionesMostrarNombre($sector,$fecha,$id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT u.usuario from produccion p LEFT JOIN usuarios u ON u.id = p.Idempleado LEFT JOIN sectores s on s.id = u.puesto where s.sector = :sector and p.fechaDeProduccion = :fecha and u.id = :id");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS,'Usuario');
    }

    public static function ProductoMasVendido($fecha)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(v.producto) cantidadVenta, p.descripcion FROM produccion v join productos p on p.id = v.producto where v.fechaDeProduccion > :fecha GROUP BY producto ORDER BY cantidadVenta DESC LIMIT 1");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }

    public static function ProductoMenosVendido($fecha)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(v.producto) cantidadVenta, p.descripcion FROM produccion v join productos p on p.id = v.producto where v.fechaDeProduccion > :fecha GROUP BY producto ORDER BY cantidadVenta ASC LIMIT 1");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }

    public static function MesaMasUsada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(p.mesa) cantidadMesa, p.mesa FROM pedidos p GROUP BY mesa ORDER BY cantidadMesa DESC LIMIT 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }

    public static function MesaMenosUsada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(p.mesa) cantidadMesa, p.mesa FROM pedidos p GROUP BY mesa ORDER BY cantidadMesa ASC LIMIT 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }

    public static function MesaMasVendio()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(p.importe) imp, p.mesa FROM pedidos p GROUP BY importe,p.mesa ORDER BY imp DESC LIMIT 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }

    public static function MesaMenosVendio()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(p.importe) imp, p.mesa FROM pedidos p GROUP BY importe,p.mesa ORDER BY imp ASC LIMIT 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }

    public static function MesaMayorImporte()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT t.mesa, t.importe FROM pedidos t WHERE t.importe = ( SELECT MAX( importe ) FROM pedidos)");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }

    public static function MesaMenorImporte()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT t.mesa, t.importe FROM pedidos t WHERE t.importe = ( SELECT MIN( importe ) FROM pedidos)");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NUM);
    }





}