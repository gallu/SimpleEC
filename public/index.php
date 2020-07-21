<?php
declare(strict_types=1);

// 基準になるディレクトリ(最後の / はない形式で)
define('BASEPATH', realpath(__DIR__ . '/..'));

// オートローダ
require(BASEPATH . '/vendor/autoload.php');

// 初期処理(testでも同じ手順で読み込みをするので、重複しないようにまとめる)
require(BASEPATH . '/src/init.php');

// 全体に設定するmiddlewareの設定
require(BASEPATH . '/src/middleware.php');

// ルーティングの設定
require(BASEPATH . '/src/routes.php');

// sessionの設定
require(BASEPATH . '/src/session.php');

// Add Routing Middleware
$app->addRoutingMiddleware();

/* エラーの設定
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
// XXX 第二引数logErrors と 第三引数logErrorDetails は常にonに
$errorMiddleware = $app->addErrorMiddleware($container->get('settings')['displayErrorDetails'], true, true, $container->get('logger'));
// 本番想定 エラーページの編集
if (false === $container->get('settings')['displayErrorDetails']) {
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('text/html', \App\Controller\ErrorRenderer::class);
}

// Run app
$app->run();

