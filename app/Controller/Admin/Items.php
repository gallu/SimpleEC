<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controller\Admin\ControllerBase;
use App\Model\Items as ItemsModel;
use SlimLittleTools\Exception\ModelValidateException;

class Items extends ControllerBase
{
    // 一覧
    public function list(Request $request, Response $response, $routeArguments)
    {
        //
        $context = $this->getFlashSessions();

        //
        $items = ItemsModel::findByAll([], 'item_name')->toArray();
        $context['items'] = $items;

        // 出力
        return $this->write($response, 'item/list.twig', $context);
    }
    // 詳細
    public function detail(Request $request, Response $response, $routeArguments)
    {
        $model = ItemsModel::find($routeArguments['id']);
        if (null === $model) {
            return $this->redirect($response, $this->urlFor('admin.item.read.list'));
        }
var_dump($model); exit;
    }

    // 登録画面出力
    public function createPrint(Request $request, Response $response, $routeArguments)
    {
        //
        $context = $this->getFlashSessions();
//var_dump($context); exit;
        // 出力
        return $this->write($response, 'item/create.twig', $context);
    }
    // 登録本体
    public function create(Request $request, Response $response, $routeArguments)
    {
        $cols = array_flip(ItemsModel::getAllColmunsWithoutPk());
        unset($cols['created_at']);
        unset($cols['updated_at']);
        $cols = array_keys($cols);
        //
        $data = [];
        foreach($cols as $c) {
            $data[$c] = $this->getParam($request, $c, null);
        }

        // insert
        try {
            $r = ItemsModel::insert($data);
            if (null === $r) {
                // XXX
                throw new \Exception('');
            }
        } catch (ModelValidateException $e) {
            $this->setFlashSessions('error', $e->getErrorObj());
            $this->setFlashSessions('data', $data);
            //
            return $this->redirect($response, $this->urlFor('admin.item.write.create_print'));
        } catch (\Throwable $e) {
            // XXX
            $this->setFlashSessions('data', $data);
            return $this->redirect($response, $this->urlFor('admin.item.write.create_print'));
        }
        //
        $this->setFlashSessions('message', '商品を登録しました');
        return $this->redirect($response, $this->urlFor('admin.item.read.list'));
    }

    //
    public function updatePrint(Request $request, Response $response, $routeArguments)
    {
    }
    //
    public function update(Request $request, Response $response, $routeArguments)
    {
    }

    //
    public function delete(Request $request, Response $response, $routeArguments)
    {
    }
}
