<?php
require_once './models/Produccion.php';
require_once './interfaces/IApiProduccion.php';

class ProduccionController extends Produccion implements IApiProduccion
{
    public function CargarUno($request, $response, $args)
    {
        Produccion::AsignarPedido();

        $payload = json_encode(array("mensaje" => "Produccion Activada Con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
