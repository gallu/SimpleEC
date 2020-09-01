<?php
declare(strict_types=1);

namespace App\Controller\Front;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controller\Front\ControllerBase;
use App\Model\Items as ItemsModel;

class Home extends ControllerBase
{
    public function index(Request $request, Response $response, $routeArguments)
    {
        // 商品一覧
        $items = ItemsModel::findByAll([], 'item_name')->toArray();
        $context['items'] = $items;

        // 出力
        return $this->write($response, 'index.twig', $context);
    }

    public function itemDetail(Request $request, Response $response, $routeArguments)
    {
        $model = ItemsModel::find($routeArguments['id']);
        if (null === $model) {
            return $this->redirect($response, $this->urlFor('front.index'));
        }

        //
        $context['item'] = $model->toArray();

        // 出力
        return $this->write($response, 'detail.twig', $context);
    }

}
