<?php
declare(strict_types=1);

namespace App\Controller\Front;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controller\Front\ControllerBase;
use App\Model\Items as ItemsModel;
use App\Libs\Cart as CartLib;


class Cart extends ControllerBase
{
    public function list(Request $request, Response $response, $routeArguments)
    {
        //
        $context = [];
        
        // 一覧を把握して
        list($context['cart'], $context['total']) = CartLib::list();
        
        // 出力
        return $this->write($response, 'cart_list.twig', $context);
    }

    public function add(Request $request, Response $response, $routeArguments)
    {
        // cartに追加して
        CartLib::chage($this->getParam($request, 'item_id'), $this->getParam($request, 'num'));
        
        // listに遷移
        return $this->redirect($response, $this->urlFor('front.cart.list'));
    }

    public function edit(Request $request, Response $response, $routeArguments)
    {
        // cartの情報を修正して
        CartLib::chage($this->getParam($request, 'item_id'), $this->getParam($request, 'num'));
        
        // listに遷移
        return $this->redirect($response, $this->urlFor('front.cart.list'));
    }

    public function delete(Request $request, Response $response, $routeArguments)
    {
        // cartから削除して
        CartLib::chage($this->getParam($request, 'item_id'), 0);
        
        // listに遷移
        return $this->redirect($response, $this->urlFor('front.cart.list'));
    }

    // cartの全クリア
    public function clear(Request $request, Response $response, $routeArguments)
    {
        // cartから削除して
        CartLib::clear();

        // listに遷移
        return $this->redirect($response, $this->urlFor('front.cart.list'));
    }

}

