<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $cliente = $parametros['cliente'];
        $nombre_producto = $parametros['producto'];
        $codigo_pedido = $parametros['pedido'];
        $codigo_mesa = $parametros['mesa'];
        $estado = $parametros['estado'];
        $tiempo = $parametros['tiempo'];

        // Creamos el usuario
        $usr = new Pedido();
        $usr->cliente = $cliente;
        $usr->nombre_producto = $nombre_producto;
        $usr->codigo_pedido = $codigo_pedido;
        $usr->codigo_mesa = $codigo_mesa;
        $usr->estado = $estado;
        $usr->tiempo = $tiempo;
        $usr->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['cliente'];
        $usuario = Pedido::obtenerPedido($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      
        $parametros = $request->getParsedBody();
        var_dump($parametros);

        $id = $args['id'];
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];

        $usr = new Usuario();
        $usr->id = $id;
        $usr->usuario = $nombre;
        $usr->clave = $clave;
        
        //Usuario::modificarUsuario($nombre);
        $usr->modificarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        

        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        //$parametros = $request->getParsedBody();
        $usuarioId = $args['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Login($request, $response)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
    
        $datos = array('usuario' => $usuario, 'clave' => $clave);
    
        $token = AutentificadorJWT::CrearToken($datos);
        
        $payload = json_encode(array('jwt' => $token));
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
