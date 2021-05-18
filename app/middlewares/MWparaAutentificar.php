<?php

use GuzzleHttp\Psr7\Response;
use Slim\Psr7\Response as ResponseMW;
use PSR\Http\Message\ServerRequestInterface as Request;
use PSR\http\Server\RequestHandlerInterface as RequestHandler;
require_once './middlewares/AutentificadorJWT.php';

class MWparaAutentificar
{
	public function VerificarUsuario(Request $request,RequestHandler $handler) : ResponseMW  {
         
		  if( $request->getMethod() === "GET")
		  {		     
		     $response = $handler->handle($request);
			 $response->getBody()->write('<p>NO necesita credenciales para los get </p>');
		  }
		  else
		  {
			
			$response = new Response();
		    $ArrayDeParametros = $request->getParsedBody();
		    $token=$ArrayDeParametros['token'];
		    try
			{
				AutentificadorJWT::VerificarToken($token);
				$todoOk = true;
			}
			catch(Exception){
				$todoOk = false;
			}
			
			if($todoOk){
				$payload=AutentificadorJWT::ObtenerData($token);
				if($payload->puesto=="Administrador")
				{   
					$response = $handler->handle($request);
					$response->getBody()->write("<p>Bienvenido $payload->usuario </p>");			  
					
				}
				else
				{	
					$response = new ResponseMW();
					$response->getBody()->write(json_encode(array("mensaje" => "Error debe ser administrador")));					
				    return $response->withHeader('Content-Type', 'application/json');					
				}
			}  
		  }
		  $response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
		  return $response;   
	
	}
}

?>