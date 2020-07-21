<?php
// DIC configuration

use SlimLittleTools\Middleware\CsrfGuard;

return function (\DI\Container $container, \Slim\App $app) {
    // CSRF
    $container->set(
        'csrf' , function () use($container, $app) {
            // 引数のブラックリストはどこかで適当に保持している前提(今は空配列)
            $not_covered_list = [];
            $csrf = (new CsrfGuard($app->getResponseFactory()))->setNotCoveredList($not_covered_list);
            //
            return $csrf;
        }
    );

/*
//use Slim\Csrf\Guard;
$responseFactory = $app->getResponseFactory();
$container->set('csrf', function () use ($responseFactory) {
    //return new Guard($responseFactory);
    return new CsrfGuard($responseFactory);
});
//$app->add('csrf');
*/



    // view renderer
    $container->set(
        'renderer' ,function () use($container, $app) {
            $settings = $container->get('settings')['renderer'];
            $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader($settings['template_path']));

            //
            $csrf_obj = $container->get('csrf');
            $twig->addFunction(
                new \Twig\TwigFunction('csrf', function () use ($csrf_obj) {
                    echo <<< EOL
        <input type="hidden" id ="csrf_token_name" name="{$csrf_obj->getTokenNameKey()}" value="{$csrf_obj->getTokenName()}">
        <input type="hidden" id="csrf_token_value" name="{$csrf_obj->getTokenValueKey()}" value="{$csrf_obj->getTokenValue()}">
        EOL;
                })
            );

            //
            return $twig;
        }
    );

    // monolog
    $container->set(
        'logger' , function () use($container, $app) {
            $settings = $container->get('settings')['logger'];
            $logger = new Monolog\Logger($settings['name']);

            // 普通のログ
            if (isset($settings['Stream'])) {
                if ('off' === $settings['Stream']['rotating']) {
                    $obj = new \Monolog\Handler\StreamHandler($settings['Stream']['path'], $settings['Stream']['level']);
                } else {
                    // XXX
                    $obj = new \Monolog\Handler\RotatingFileHandler($settings['Stream']['path'], 0, $settings['Stream']['level']);
                }
                //
                $logger->pushHandler($obj);
            }

            // FingersCrossedHandler
            if (isset($settings['FingersCrossed'])) {
                if ('off' === $settings['FingersCrossed']['rotating']) {
                    $obj = new \Monolog\Handler\StreamHandler($settings['FingersCrossed']['path'], $settings['FingersCrossed']['level']);
                } else {
                    // XXX
                    $obj = new \Monolog\Handler\RotatingFileHandler($settings['FingersCrossed']['path'], 0, $settings['FingersCrossed']['level']);
                }
                //
                $logger->pushHandler($obj, new \Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy($settings['FingersCrossed']['activationStrategy_level']));
            }

            //$logger->pushProcessor(new Monolog\Processor\UidProcessor());
            return $logger;
        }
    );

    // mail
    $container->set(
        'mailer' , function () use($container, $app) {
            //XXX
            $mailer = new \App\Libs\Mailer($container->get('settings')['from']);
            return $mailer;
        }
    );

};

