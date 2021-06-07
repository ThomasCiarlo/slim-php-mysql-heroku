<?php
require_once './models/Producto.php';
require_once './interfaces/IApiProducto.php';

class ProductoController extends Producto implements IApiProducto
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $tipo = $parametros['tipo'];
        $stock = $parametros['stock'];
        $importe = $parametros['importe'];

        // Creamos el usuario
        $pro = new Producto();
        $pro->descripcion = $descripcion;
        $pro->tipo = $tipo;
        $pro->stock = $stock;
        $pro->importe = $importe;
        $pro->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $descripcion = $args['descripcion'];
        $producto = Producto::obtenerProducto($descripcion);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaproductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $tipo = $parametros['tipo'];
        $stock = $parametros['stock'];
        $id = $parametros['id'];
        Producto::modificarProducto($descripcion,$tipo,$stock,$id);

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        Producto::borrarProducto($descripcion);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function DescargarProductos($request, $response, $args)
    {
      $lista = Producto::obtenerTodos();
      $rutaArchivo = "./DescargaDeArchivos/Productos.csv";

      foreach($lista as $prod){
        $str = $prod->id .",". $prod->descripcion .",". $prod->tipo .",". $prod->importe;
        Archivos::EscribirArchivos($rutaArchivo,$str);
      }
      
      $payload = json_encode(array("mensaje" => "Se guardo correctamente"));           

      $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
