<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiMesa.php';

class MesaController extends Mesa implements IApiMesa
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $numeroMesa = random_int(10000,99999);
        $sector = $parametros['sector'];
        $estado = $parametros['estado'];

        // Creamos La Mesa
        $Mesa = new Mesa();
        $Mesa->numeroMesa = $numeroMesa;
        $Mesa->sector = $sector;
        $Mesa->estado = $estado;
        $Mesa->CrearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Mesa por numero
        $numeroMesa = $args['numeroMesa'];
        $mesa = Mesa::obtenerMesa($numeroMesa);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listamesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $numeroMesa = $parametros['numeroMesa'];
        $sector = $parametros['sector'];
        $estado = $parametros['estado'];
        Mesa::modificarMesa($numeroMesa,$sector,$estado);

        $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $numeroMesa = $parametros['numeroMesa'];
        Mesa::borrarMesa($numeroMesa);

        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
