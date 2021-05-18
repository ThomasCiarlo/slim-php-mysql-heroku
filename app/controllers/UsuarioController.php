<?php

use Slim\Psr7\Response;

require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './middlewares/AutentificadorJWT.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $puesto = $parametros['puesto'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->puesto = $puesto;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['usuario'];
        $clave = $parametros['clave'];
        $puesto = $parametros['puesto'];
        $id = $parametros['id'];
        Usuario::modificarUsuario($nombre,$clave,$puesto,$id);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUserLogin($request, $response, $args)
    {
      $token = "";
      $parametros = $request->getParsedBody();

      $usuario = $parametros['usuario'];
      $clave = $parametros['clave'];

      $usr = Usuario::obtenerUserLogin($usuario,$clave);

      if($usr != null)
      {
        $token = AutentificadorJWT::CrearToken($usr);
        $payload = json_encode(array("mensaje" => "Usuario logiado con exito"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
      }

      $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json')
          ->withHeader('Authorization', 'Bearer ' . $token);
    }
}
