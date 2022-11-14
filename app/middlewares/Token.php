
<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Usuario.php';

class TokenMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response

    {
        $response = $handler->handle($request);
        $response = new Response();
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
    
        $datos = array('usuario' => $usuario, 'clave' => $clave);
    
        $token = AutentificadorJWT::CrearToken($datos);
        
        $payload = json_encode(array('jwt' => $token));
        
        var_dump($token);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
       /*$header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $esValido = false;

        try {
            AutentificadorJWT::verificarToken($token);
            $esValido = true;
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }

        if ($esValido) {
            $payload = json_encode(array('valid' => $esValido));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');*/


         
    }
}
