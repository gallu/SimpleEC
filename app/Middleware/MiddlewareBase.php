<?php
namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;

class MiddlewareBase implements MiddlewareInterface
{
    /*
     */
    public function __construct($container)
    {
        // コンテナインスタンスの受け取り
        $this->container = $container;
    }

//
    protected $container;
}
