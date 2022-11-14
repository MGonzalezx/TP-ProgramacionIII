
<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
require_once './models/Usuario.php';

class LoggerMiddleware
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
        

        // verificar si nombre y clave no sean vacios y que existan.
       try {
        if ($usuario != null && $clave != null) {
            $respuestaMetodo = Usuario::verificarDatos($usuario,$clave);
            if($respuestaMetodo === "Verificado"){
                $response->getBody()->write(json_encode(array("respuesta" => "Verificado")));
                
                $response->withStatus(200);
            }elseif ($respuestaMetodo === "Credenciales invalidas") {
                $response->getBody()->write(json_encode(array("respuesta" => "Credenciales invalidas")));
                $response->withStatus(401);
            }else {
                $response->getBody()->write(json_encode(array("respuesta" => "Usuario no existe")));
                $response->withStatus(404);
            }
        } else{
            $response->getBody()->write(json_encode(array("Error" => "Faltan datos para la realizar la consulta")));
        }
        
       } catch (Exception $e) {
        echo $e->getMessage();
       }
       return $response;
    }

    
}