<?php

class Produccion
{
    public $id;
    public $Idempleado;
    public $empleadoNombre;
    public $producto;
    public $idPedido;
    public $estado;
    public $tiempoEstimado;
    public $fechaDeProduccion;
    
    public static function AsignarPedido()
    {
        $NohayMas = true;
        $pedido = Pedido::obtenerPedidoPorEstado("1");
        if($pedido != null){

        $listaDeProductos = explode ( ";" , $pedido->listaPedido ,$limit = PHP_INT_MAX);

        foreach($listaDeProductos as $x)
        {
            $prod = Produccion::BuscarEnProduccion($x,$pedido->id);
            if($prod == null){
                $producto = Producto::obtenerProductoID($x);
                $user = null;
                $tip = $producto->tipo;
                switch($tip)
                {
                    case "COMIDA":
                        $user = Produccion::BuscarEmpleadoSinAsignacion("1");                      
                        break;
                    case "BEBIDA":
                        $user = Produccion::BuscarEmpleadoSinAsignacion("2");                        
                        break;
                    case "CERVEZA":
                        $user = Produccion::BuscarEmpleadoSinAsignacion("3");
                        break;
                    default:
                        return;
                    break;
                }
                
                Produccion::InsertarEnProduccion($user->id,$user->usuario,$x,$pedido->id,"2");
                Pedido::ActualizarEstado($pedido->id,2);                 
                $NohayMas = false;                      
            }

            if($NohayMas){
                Pedido::ActualizarEstado($pedido->id,"1");
            }
          }
        }

        $pedidosAEntregar = Pedido::obtenerPedidoPorEstado("1");

        if($pedidosAEntregar != null){
            $userMozo = Produccion::BuscarEmpleadoSinAsignacion("MOZO");
            if($userMozo != null){               
                Produccion::InsertarEnProduccion($userMozo->id,$userMozo->usuario,0,$pedidosAEntregar->id,"3");
                Pedido::ActualizarEstado($pedidosAEntregar->id,"2");
                Mesa::modificarEstado($pedidosAEntregar->mesa,3);
            }
        }


    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT Idempleado,empleadoNombre,producto,idPedido,fechaDeProduccion,estado FROM produccion");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Produccion');
    }

    public static function InsertarEnProduccion($idEmp,$NombreEmp,$idProducto,$idPedido,$estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO produccion (Idempleado,empleadoNombre,producto,idPedido,fechaDeProduccion,estado) VALUES (:Idempleado, :empleadoNombre,:producto,:idPedido,:fechaDeProduccion,:estado)");
        $consulta->bindValue(':Idempleado', $idEmp);
        $consulta->bindValue(':empleadoNombre', $NombreEmp);
        $consulta->bindValue(':producto', $idProducto);
        $consulta->bindValue(':idPedido', $idPedido);
        $consulta->bindValue(':fechaDeProduccion', date('Y-m-d'));
        $consulta->bindValue(':estado', $estado);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function BuscarEmpleadoSinAsignacion($puesto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave,puesto FROM usuarios WHERE estado = 'SIN ASIGNACION' and puesto = :puesto LIMIT 1");
        $consulta->bindValue(':puesto', $puesto);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function BuscarEnProduccion($idProducto,$idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,Idempleado, empleadoNombre,producto,idPedido,estado,fechaDeProduccion FROM produccion WHERE producto = :producto and idPedido = :idPedido");
        $consulta->bindValue(':producto', $idProducto);
        $consulta->bindValue(':idPedido', $idPedido);
        $consulta->execute();

        return $consulta->fetchObject('Produccion');
    }

    public static function BuscarEnProduccionID($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,Idempleado, empleadoNombre,producto,idPedido,estado,fechaDeProduccion FROM produccion WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Produccion');
    }
   
    public static function BuscarPedidoPorUser($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,Idempleado, empleadoNombre,producto,idPedido,estado,fechaDeProduccion FROM produccion WHERE  estado = '1' and Idempleado = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('Produccion');
    }

    public static function ActualizarEstado($id,$tiempo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE produccion set estado = '2',tiempoEstimado = :tiempo where id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->bindValue(':tiempo', $tiempo);
        $consulta->execute();
    }

    
}