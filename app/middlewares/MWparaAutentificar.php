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
			
			$response = new ResponseMW();

			$arrayConToken = $request->getHeader('Authorization');
		    $token=$arrayConToken[0];

			$token = str_replace ("Bearer " , "" , $token);


		    try
			{
				AutentificadorJWT::VerificarToken($token);
				$todoOk = true;
			}
			catch(Exception){

				if($token == "")
				{
					$response->getBody()->write(json_encode(array("mensaje" => "Primero debe iniciar sesion")));
					return $response;
				}
				$response->getBody()->write(json_encode(array("mensaje" => "Token incorrecto")));
				$todoOk = false;
			}
			
			if($todoOk){
				$payload=AutentificadorJWT::ObtenerData($token);
				if($payload->puesto=="ADMINISTRADOR")
				{   
					$response = $handler->handle($request);			  
					
				}
				else
				{	
					$response = new ResponseMW();
					$response->getBody()->write(json_encode(array("mensaje" => "Error debe ser administrador")));					
				    return $response->withHeader('Content-Type', 'application/json');					
				}
			}  
		  }
		  return $response;   
	
	}
}

?>