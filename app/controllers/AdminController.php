<?php

use Slim\Psr7\Response;

require_once './models/Admin.php';

class AdminController extends Admin
{

    public function OperacionesPorSector($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $parametros = $request->getParsedBody();
        $sector = $parametros['sector'];
        $fecha = $parametros['fecha'];

        $oper = Admin::CantidadOperaciones($sector,$fecha);
        $payload = json_encode($oper[0][0]);

        
        $response->getBody()->write("Cantidad de Operaciones : " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function OperacionesPorSectorListaEmpleados($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $parametros = $request->getParsedBody();
        $sector = $parametros['sector'];
        $fecha = $parametros['fecha'];
        $id = $parametros['id'];

        $oper = Admin::CantidadOperacionesMostrarNombre($sector,$fecha,$id);
        $payload = json_encode($oper[0]->usuario);

       
        $response->getBody()->write("Empleado : " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ProductoMejorVendido($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $parametros = $request->getParsedBody();
        $fecha = $parametros['fecha'];

        $oper = Admin::ProductoMasVendido($fecha);
        $payload = json_encode($oper[0]);
      
        $response->getBody()->write("Producto : " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ProductoPeorVendido($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $parametros = $request->getParsedBody();
        $fecha = $parametros['fecha'];

        $oper = Admin::ProductoMenosVendido($fecha);
        $payload = json_encode($oper[0]);

        
        $response->getBody()->write("Producto : " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaMasUtilizada($request, $response, $args)
    {
        $oper = Admin::MesaMasUsada();
        $payload = json_encode($oper[0]);

        
        $response->getBody()->write("Mesa : " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaMenosUtilizada($request, $response, $args)
    {
        $oper = Admin::MesaMenosUsada();
        $payload = json_encode($oper[0]);
       
        $response->getBody()->write("Mesa : " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaMasFacturo($request, $response, $args)
    {
        $oper = Admin::MesaMasVendio();
        $payload = json_encode($oper[0][1]);
       
        $response->getBody()->write("Mesa Mas Facturo : " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaMenosFacturo($request, $response, $args)
    {
        $oper = Admin::MesaMenosVendio();
        $payload = json_encode($oper[0][1]);
       
        $response->getBody()->write("Mesa Menos Facturo: " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaConMayorImporte($request, $response, $args)
    {
        $oper = Admin::MesaMayorImporte();
        $payload = json_encode($oper[0]);
       
        $response->getBody()->write("Mesa Mayor Importe: " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaConMenorImporte($request, $response, $args)
    {
        $oper = Admin::MesaMenorImporte();
        $payload = json_encode($oper[0]);
       
        $response->getBody()->write("Mesa Menor Importe: " .$payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


}