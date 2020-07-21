<?php
declare(strict_types=1);

namespace App\Controller;

use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ControllerBase
{
    /*
     */
    public function __construct(Container $container)
    {
        // コンテナインスタンスの受け取り
        $this->container = $container;
    }

    /**
     * setNameからURIを取得するためのラッパー
     */
    protected function urlFor($name)
    {
        return $this->container->get('router')->getRouteParser()->urlFor($name);
    }

    /**
     * redirect用ラッパー
     */
    protected function redirect(Response $response, string $url, int $status = 302)
    {
        return $response
                   ->withHeader('Location', $url)
                   ->withStatus($status);
    }

    /**
     * requestから1項目を取得するラッパー
     *
     * XXX 何で消えたんだろう orz
     * @param  string $key     The parameter key.
     * @param  mixed  $default The default value.
     *
     * @return mixed
     */
    public function getParam(Request $request, string $key, $default = null)
    {
        $postParams = $request->getParsedBody();
        $getParams = $request->getQueryParams();
        $result = $default;
        if (is_array($postParams) && isset($postParams[$key])) {
            $result = $postParams[$key];
        } elseif (is_object($postParams) && property_exists($postParams, $key)) {
            $result = $postParams->$key;
        } elseif (isset($getParams[$key])) {
            $result = $getParams[$key];
        }

        return $result;
    }
    /**
     * @param array|null $only list the keys to retrieve.
     *
     * @return array|null
     */
    public function getParams(Request $request, array $only = null)
    {
        $params = $request->getQueryParams();
        $postParams = $request->getParsedBody();
        if ($postParams) {
            $params = array_replace($params, (array)$postParams);
        }

        if ($only) {
            $onlyParams = [];
            foreach ($only as $key) {
                if (array_key_exists($key, $params)) {
                    $onlyParams[$key] = $params[$key];
                }
            }
            return $onlyParams;
        }

        return $params;
    }

    // テンプレート用インスタンスの取得とrenderの発行
    protected function render($name, array $context = array())
    {
        // デフォで使う値を追加する
        $context['hoge'] = 'hoge'; // サンプル
        //
        return $this->container->get('renderer')->render($name, $context);
    }

    // writeのラッパー
    protected function write(Response $response, string $name, array $context = [])
    {
        $response->getBody()->write($this->render($name, $context));
        return $response;
    }

//
    protected $container;
}
