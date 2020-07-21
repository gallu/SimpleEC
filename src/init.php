<?php

/*
 * バッチとWebで共通になる処理の記述
 */

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

// 基準になるディレクトリ(最後の / はない形式で)
if (false === defined('BASEPATH')) {
    define('BASEPATH', realpath(__DIR__ . '/..'));
}


// containerの生成
$containerBuilder = new ContainerBuilder();
// 設定の読み込み: 環境個別、のほうが優先順位高い
$settings = array_merge_recursive(require(BASEPATH . '/src/settings.php'), require(BASEPATH . '/src/by_environment_settings.php') );
$containerBuilder->addDefinitions($settings);
// 生成
$container = $containerBuilder->build();

// appインスタンスの生成
AppFactory::setContainer($container);
$app = AppFactory::create();
// container への追加設定
$dependencies = require(BASEPATH . '/src/dependencies.php');
$dependencies($container, $app);

// routerインスタンスの把握
$container->set('router', $app->getRouteCollector());

// DBとConfigを使うための準備
// XXX DBとConfigだけは「どこからでも」触りたいので
\SlimLittleTools\Libs\DB::setContainer($app->getContainer());
\SlimLittleTools\Libs\Config::setContainer($app->getContainer());
\SlimLittleTools\Libs\Container::setContainer($app->getContainer());

// class のエイリアス
class_alias('\\SlimLittleTools\\Libs\\DB', 'DB');
class_alias('\\SlimLittleTools\\Libs\\Config', 'Config');
class_alias('\\SlimLittleTools\\Libs\\Container', 'Container');

