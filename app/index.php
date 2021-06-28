<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './middlewares/MWparaAutentificar.php';
require_once './db/AccesoDatos.php';

require_once './controllers/AdminController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProduccionController.php';
require_once './Funciones/ReporteProduccion.php';


// Instantiate App
$app = AppFactory::create();
//para debug
//$app->setBasePath('/slim-php-mysql-heroku/app');
// Add error middleware
$app->addErrorMiddleware(true, true, true);


$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':TraerUserLogin');
});

$app->group('/admin', function (RouteCollectorProxy $group){  
  $group->post('/A/[/]',\AdminController::class . ':OperacionesPorSector');
  $group->post('/B/[/]',\AdminController::class . ':OperacionesPorSectorListaEmpleados');
  $group->post('/producto/A/[/]',\AdminController::class . ':ProductoMejorVendido');
  $group->post('/producto/B/[/]',\AdminController::class . ':ProductoPeorVendido');
  $group->post('/mesa/A/[/]',\AdminController::class . ':MesaMasUtilizada');
  $group->post('/mesa/B/[/]',\AdminController::class . ':MesaMenosUtilizada');
  $group->post('/mesa/C/[/]',\AdminController::class . ':MesaMasFacturo');
  $group->post('/mesa/D/[/]',\AdminController::class . ':MesaMenosFacturo');
  $group->post('/mesa/E/[/]',\AdminController::class . ':MesaConMayorImporte');
  $group->post('/mesa/F/[/]',\AdminController::class . ':MesaConMenorImporte');
  $group->post('/pedido/A/',\AdminController::class . ':PedidosCancelados');
  $group->post('/pedido/B/[/]',\AdminController::class . ':Facturacion');
  $group->post('/pedido/C/',\AdminController::class . ':BuenaCritica');
  $group->post('/pedido/D/',\AdminController::class . ':MalaCritica');
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/usuarios', function (RouteCollectorProxy $group){  
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]',\UsuarioController::class . ':CargarUno')->add(\MWparaAutentificar::class . ':VerificarUsuario');
  });

$app->group('/usuarios/tareas', function (RouteCollectorProxy $group){  
    $group->get('/{id}', \UsuarioController::class . ':VerTarea');
    $group->post('[/]', \UsuarioController::class . ':TerminarTarea');
  });

  $app->group('/encuesta', function (RouteCollectorProxy $group){  
    $group->post('[/]', \UsuarioController::class . ':Encuesta');
  });

$app->group('/descargaArchivos', function (RouteCollectorProxy $group){  
    $group->post('[/]', \UsuarioController::class . ':CargaUsuarios');
    $group->get('/a/', \UsuarioController::class . ':DescargarUsuarios');
    $group->get('/b/', \ProductoController::class . ':DescargarProductos');
    $group->get('/c/', \ReporteProduccion::class . ':GenerarPdf');
  });

$app->group('/administrador', function (RouteCollectorProxy $group){  
    $group->post('/estado', \UsuarioController::class . ':estadoPedido');
    $group->post('/cerrarPedido', \UsuarioController::class . ':cerrarMesa');
  })->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{descripcion}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
  })->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->get('/{numeroMesa}', \MesaController::class . ':TraerUno');
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(\MWparaAutentificar::class . ':VerificarUsuario');
  });

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/{codPedido}', \PedidoController::class . ':TraerUno');
    $group->post('[/]', \PedidoController::class . ':CargarUno');//->add(\MWparaAutentificar::class . ':VerificarUsuario');
  });

  $app->group('/produccion', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProduccionController::class . ':CargarUno');
  });
  

  
$app->run();
