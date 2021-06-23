<?php

use Slim\Psr7\Response;

require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './models/Produccion.php';
require_once './Funciones/Archivos.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $puesto = $parametros['puesto'];
        $estado = $parametros['estado'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->puesto = $puesto;
        $usr->estado = $estado;
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

    public function VerTarea($request, $response, $args)
    {     
      $id = $args['id'];
      var_dump($id);
      $produccion = Produccion::BuscarPedidoPorUser($id);

      $payload = json_encode(array("Tarea:" => $produccion));

      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }

    public function TerminarTarea($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $idUser = $parametros['id'];
      $timpoRealizado = $parametros['tiempoTardado'];
      
      $produccion = Produccion::BuscarPedidoPorUser($idUser);


      if($produccion != null){
      Produccion::ActualizarEstado($produccion->id,$timpoRealizado);
      $array = (array("mensaje" => "Se termino con la tarea","ID" => "$produccion->id"));
      }
      else{
        $array = (array("mensaje" => "No habia tareas pendientes"));
      }     
      $payload = json_encode($array);
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
        $array = (array("mensaje" => "Usuario logiado con exito","token" => "$token"));
        $payload = json_encode($array);
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Usuario no encontrado"));       
      }

      $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function cerrarMesa($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $mesa = $parametros['mesa'];
      $pedido = $parametros['pedido'];

      Mesa::modificarEstado($mesa,4);
      Pedido::ActualizarEstado($pedido,3);

      $payload = json_encode(array("mensaje" => "Se cerro la mesa correctamente"));           

      $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function estadoPedido($request, $response, $args)
    {

      $parametros = $request->getParsedBody();

      $idPedido = $parametros['idPedido'];

      $ped =Pedido::obtenerPedido($idPedido);
      $prod = Produccion::BuscarEnProduccionID($ped->id);

      $payload = json_encode(array("Pedido" => $ped,"Productos" => $prod));

      $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function DescargarUsuarios($request, $response, $args)
    {
      $lista = Usuario::obtenerTodos();
      $rutaArchivo = "./DescargaDeArchivos/Usuarios.csv";

      foreach($lista as $user){
        $str = $user->id .",". $user->usuario .",". $user->clave .",". $user->puesto;
        Archivos::EscribirArchivos($rutaArchivo,$str);
      }
      
      $payload = json_encode(array("mensaje" => "Se guardo correctamente"));           

      $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
