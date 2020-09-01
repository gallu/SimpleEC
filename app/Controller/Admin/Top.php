<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controller\Admin\ControllerBase;

class Top extends ControllerBase
{
    //
    public function index(Request $request, Response $response, $routeArguments)
    {
        //
        $context = $this->getFlashSessions();
        // 出力
        return $this->write($response, 'top.twig', $context);
    }
}
