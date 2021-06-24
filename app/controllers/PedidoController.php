<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiPedido.php';

class PedidoController extends Pedido implements IApiPedido
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $mesa = Mesa::obtenerMesaEstado(4);

        if($mesa != null){

        $ArrayPedidos = $parametros['ArrayPedidos'];
        $usuario = "";
        $estado = "1";
        $codPedido = random_int(10000,99999);
        $horaDeInicio = date("H:i:s");
        $nombreCliente = $parametros['nombreCliente'];

        $pedidosListado = explode ( ";" , $ArrayPedidos ,$limit = PHP_INT_MAX);
        $importe = 0;
        $idProductos = "";
        foreach($pedidosListado as $x)
        {
           $prod = Producto::obtenerProducto($x);
           if($prod != null){
             $idProductos .= $prod->id.";";
             $importe = $prod->importe + $importe;
           }
        }
                            
        $Pedido = new Pedido();
        $Pedido->mesa = $mesa->id;
        $Pedido->listaPedido = $idProductos;
        $Pedido->usuario = $usuario;
        $Pedido->estado = $estado;
        $Pedido->codPedido = $codPedido;
        $Pedido->horaDeInicio = $horaDeInicio;
        $Pedido->importe = $importe;
        $Pedido->nombreCliente = $nombreCliente;

        $Pedido->CrearPedido();
        Mesa::modificarEstado($Pedido->mesa,1);

        $payload = json_encode(array("mensaje" => "Pedido creado con exito","CodPedido" => "$codPedido"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay mesa disponible"));
      }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Pedido por numero
        $codPedido = $args['codPedido'];
        $pedido = Pedido::obtenerPedido($codPedido);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codPedido = $parametros['codPedido'];
        Pedido::borrarPedido($codPedido);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
