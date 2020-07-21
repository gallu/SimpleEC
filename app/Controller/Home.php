<?php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controller\ControllerBase;

class Home extends ControllerBase
{
    public function index(Request $request, Response $response, $routeArguments)
    {
        // 出力
        return $this->write($response, 'index.twig', []);
    }
}
