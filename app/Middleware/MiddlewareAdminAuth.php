<?php
declare(strict_types=1);
namespace App\Middleware;

//
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response; // これは「実際にレスポンスを自力で作って返す」時に使う

use Psr\Http\Server\MiddlewareInterface;
use SlimLittleTools\Libs\Container;

//
class MiddlewareAdminAuth implements MiddlewareInterface
{
    /*
     */
    //
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 前処理
        // 認可でNGの場合
        if (false === isset($_SESSION['admin'])) {
            $response = new Response();
            $response = $response
                       ->withHeader('Location', Container::getContainer()->get('router')->getRouteParser()->urlFor('admin.index'))
                       ->withStatus(302);
            return $response;
        }

        // 呼び出し
        $response = $handler->handle($request);

        // 後処理
        // XXX 特に無し
        return $response;
    }
}

