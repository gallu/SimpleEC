<?php
declare(strict_types=1);

namespace App\Controller;

class ErrorRenderer implements \Slim\Interfaces\ErrorRendererInterface
{
    public function __invoke(\Throwable $exception, bool $displayErrorDetails): string
    {
var_dump( get_class($exception) ); exit;
        return \SlimLittleTools\Libs\Container::getContainer()->get('renderer')->render('error.twig', []);
    }

}

