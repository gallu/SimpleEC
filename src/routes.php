<?php
// Routes

// Controllerの指定が楽なので、この名前空間にしておく
namespace App\Controller;
//
use App\Middleware\MiddlewareAdminAuth;
use Slim\Routing\RouteCollectorProxy;

//
$app->get('[/]', Front\Home::class . ':index')->setName('front.index');
// 詳細
$app->get('/detail/{id}', Front\Home::class . ':itemDetail')->setName('front.item.detail');
// カート
$app->group('/cart', function(RouteCollectorProxy $app){
    $app->get('/list', Front\Cart::class . ':list')->setName('front.cart.list');
    $app->post('/add', Front\Cart::class . ':add')->setName('front.cart.add');
    $app->post('/edit', Front\Cart::class . ':edit')->setName('front.cart.edit');
    $app->post('/delete', Front\Cart::class . ':delete')->setName('front.cart.delete');
    $app->post('/clear', Front\Cart::class . ':clear')->setName('front.cart.clear');
});


//
$app->group('/admin', function(RouteCollectorProxy $app) {
    //
    $app->get('[/]', Admin\Home::class . ':index')->setName('admin.index');
    $app->post('/login', Admin\Home::class . ':login')->setName('admin.login');

    // 以下、認可が必要な処理
    $app->group('', function(RouteCollectorProxy $app){
        //
        $app->get('/top', Admin\Top::class . ':index')->setName('admin.top');

        // 商品(CRUD＋list)
        $app->group('/item', function(RouteCollectorProxy $app) {
            // 一覧
            $app->get('', Admin\Items::class . ':list')->setName('admin.item.read.list');
            // 詳細
            $app->get('/detail/{id}', Admin\Items::class . ':detail')->setName('admin.item.read.detail');
            // 登録
            $app->get('/create', Admin\Items::class . ':createPrint')->setName('admin.item.write.create_print');
            $app->post('/create', Admin\Items::class . ':create')->setName('admin.item.write.create');
            // 編集
            $app->get('/update/{id}', Admin\Items::class . ':updatePrint')->setName('admin.item.write.update_print');
            $app->post('/update/{id}', Admin\Items::class . ':update')->setName('admin.item.write.update');
            // 削除
            $app->post('/delete/{id}', Admin\Items::class . ':delete')->setName('admin.item.write.delete');
        });
    })->add(new MiddlewareAdminAuth($app->getContainer()));
});

/* 以下、サンプル */
/*
$app->group('/sample', function(RouteCollectorProxy $app) {
    // 表示だけ
    $app->get('', Sample::class . ':index')->setName('sample_index');
    // json出力
    $app->get('/json', Sample::class . ':json');
    // CSV出力
    $app->get('/csv', Sample::class . ':csv');
    // location(内部)
    $app->get('/location', Sample::class . ':locationLocal');
    // location(外部)
    $app->get('/location/google', Sample::class . ':locationGoogle');
    // データの受取
    $app->get('/request', Sample::class . ':request');
    // データの受取(GET)
    $app->get('/request/get', Sample::class . ':requestFin');
    // データの受取(POST)
    $app->post('/request/post', Sample::class . ':requestFin');
    // データの受取(PUT)
    $app->put('/request/put', Sample::class . ':requestFin');

    // データの受取、validate、insert
    $app->get('/post', Sample::class . ':postInput')->setName('post_input');
    $app->post('/post', Sample::class . ':postDo');
    $app->get('/post_fin', Sample::class . ':postFin')->setName('post_fin');
    // データの一覧
    $app->get('/list', Sample::class . ':list')->setName('post_list');
    // データの表示(URIでパラメタ受け取り)
    $app->get('/detail/{id}', Sample::class . ':detail')->setName('post_detail');
    // データの修正
    $app->get('/edit/{id}', Sample::class . ':edit')->setName('post_edit');
    $app->post('/edit/{id}', Sample::class . ':editDo');

    // Model/Detail の確認
    $app->get('/model/detail', Sample::class . ':model_detail')->setName('model_detail');

    // session
    $app->get('/session', Sample::class . ':session');
    // Cookie
    //$app->get('/cookie', Sample::class . ':cookie');

    // middleware付き(middlewareは空でよいかなぁ...)
    $app->group('', function() use ($app) {
        //
        $app->get('/middle', Sample::class . ':middle');
    })->add(new MiddlewareSample($app->getContainer()));
});
*/