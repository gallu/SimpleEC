<?php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controller\ControllerBase;

use App\Model\SampleTable;

class Sample extends ControllerBase
{
    // 表示だけ
    public function index(Request $request, Response $response, $routeArguments)
    {
        // 出力
        return $this->write($response, 'sample/index.twig', []);
    }
    // json出力
    public function json(Request $request, Response $response, $routeArguments)
    {
        // (使わないけど)stdinの内容まるっと取得、も書いておく：出力すればいいか
        // 出力
        return $this->write($response, 'sample/index.twig', []);
    }
    // CSV出力
    public function csv(Request $request, Response $response, $routeArguments)
    {
        // ファイル名の指定付きで
        // 出力
        return $this->write($response, 'sample/index.twig', []);
    }

    // location(内部)
    public function locationLocal(Request $request, Response $response, $routeArguments)
    {
        // setNameを使ったリダイレクト
        return $this->redirect($response, $this->urlFor('sample_index'));
    }

    // location(外部)
    public function locationGoogle(Request $request, Response $response, $routeArguments)
    {
        // URIを指定したリダイレクト
        return $this->redirect($response, 'https://www.google.com/');
    }

    // データの受取
    public function request(Request $request, Response $response, $routeArguments)
    {
        // 出力
        return $this->write($response, 'sample/request.twig', []);
    }
    // データの受取(get/post/put)
    public function requestFin(Request $request, Response $response, $routeArguments)
    {
        // 出力
        $context= [];
        $context['method'] = $request->getMethod();
        $context['text'] = $this->getParam($request, 'hoge', '');
        $context['foo'] = $this->getParam($request, 'foo', []);

        return $this->write($response, 'sample/request_out.twig', $context);
    }

    // session
    public function session(Request $request, Response $response, $routeArguments)
    {
        //
        $context = [];

        // 設定
        $name = 'rand';
        $context['rand'] = $_SESSION[$name] ?? '';
        //
        $val = mt_rand(0, 999);
        $context['rand_set'] = $val;
        $_SESSION[$name] = $val;

        // 出力
        return $this->write($response, 'sample/session.twig', $context);
    }

    // Cookie
    public function cookie(Request $request, Response $response, $routeArguments)
    {
        //
        $context = [];

        // 設定
        $name = 'rand_tmp';
        $val = mt_rand(0, 999);
        $this->container->get('cookie')->set($name, $val, ['expires' => null ]);
        $context['rand_tmp_set'] = $val;
        $context['rand_tmp'] = $this->container->get('cookie')->get('rand_tmp');
        //
        $name = 'rand';
        $val = mt_rand(0, 999);
        $this->container->get('cookie')->set($name, $val);
        $context['rand_set'] = $val;
        $context['rand'] = $this->container->get('cookie')->get('rand');

        // 出力
        return $this->write($response, 'sample/cookie.twig', $context);
    }

    /*
     * DBとの連携
     */
    // 入力画面
    public function postInput(Request $request, Response $response, $routeArguments)
    {
        //　エラー戻りとかがあったら一通り情報を入れておく
        $context = $_SESSION['flash']['sample'] ?? [];
        // データは消す
        unset($_SESSION['flash']['sample']);

        // 出力
        return $this->write($response, 'sample/post_input.twig', $context);
    }

    // データの受取、validate、insert
    public function postDo(Request $request, Response $response, $routeArguments)
    {
        // CSRFチェック入れておく: Middlewareで
        // 一旦まず、一通り情報を取得
        $cols = SampleTable::getAllColmunsWithoutPk();
        // frontから取得しないカラム情報を除去
        $col = array_values(array_diff($cols, ['created_at', 'updated_at']));

        // データ取得
        $datum = $this->getParams($request, $col);

        // データinsert
        try {
            $obj = SampleTable::insert($datum);
            //var_dump($obj, \DB::getHandle()::getError(), \DB::getHandle()::getSql()); exit;
        } catch (\SlimLittleTools\Exception\ModelValidateException $e) {
            // データをセッションに仕込んで
            $_SESSION['flash']['sample']['datum'] = $datum;
            $_SESSION['flash']['sample']['error'] = $e->getErrorObj();
            // 入力ページに突き返す
            return $this->redirect($response, $this->urlFor('post_input'));
        }

        // 完了画面に遷移
        return $this->redirect($response, $this->urlFor('post_fin'));
    }
    //
    public function postFin(Request $request, Response $response, $routeArguments)
    {
        // 出力
        return $this->write($response, 'sample/post_input_fin.twig', []);
    }

    // データの一覧
    public function list(Request $request, Response $response, $routeArguments)
    {
        // 一覧の取得
        // 簡単な一覧(Model使用)
        $data = SampleTable::findByAll(null, 'updated_at desc')->toArray();

        // もうちょっと面倒な一覧(SQL使用)
        $dbh = \DB::getHandle();
        $sql = 'SELECT * FROM sample_table ORDER BY created_at DESC;';
        $r = $dbh->preparedQuery($sql, []);
        if (false === $r) {
            $data = [];
        } else {
            $data = $r->fetchAll(\PDO::FETCH_ASSOC);
        }

        // 出力
        $context = [];
        $context['data'] = $data;
        return $this->write($response, 'sample/post_list.twig', $context);
    }

    // データの表示(URIでパラメタ受け取り)
    public function detail(Request $request, Response $response, $routeArguments)
    {
        // IDの把握
        $id = $routeArguments['id'];
        // データの検索
        $obj = SampleTable::find($id);
        if (null === $obj) {
            return $this->redirect($response, $this->urlFor('post_list'));
        }
        // データの把握
        $context = [];
        $context['datum'] = $obj->toArray();

        // 出力
        return $this->write($response, 'sample/post_detail.twig', $context);
    }

    // middleware付き(middlewareは空でよいかなぁ...)
    public function middle(Request $request, Response $response, $routeArguments)
    {
        // 出力
        return $this->write($response, 'sample/index.twig', []);
    }

    // Model/Detail の確認
    public function model_detail(Request $request, Response $response, $routeArguments)
    {
        // 確認用なのでテンプレート無しで
        echo "<pre>\n";
        echo "getAllColmunsWithComment\n";
        var_dump(SampleTable::getAllColmunsWithComment());        

        echo "\ngetAllColmunsWithComment('(')\n";
        var_dump(SampleTable::getAllColmunsWithComment('('));        

        echo "\ngetAllColmuns\n";
        var_dump(SampleTable::getAllColmuns());        

        echo "\ngetAllColmunsWithCommentWithoutPk\n";
        var_dump(SampleTable::getAllColmunsWithCommentWithoutPk());        

        echo "\ngetAllColmunsWithoutPk\n";
        var_dump(SampleTable::getAllColmunsWithoutPk());        

        echo "\nisColumnTypeDate\n";
        foreach(SampleTable::getAllColmuns() as $col) {
            echo "{$col} is ", intval(SampleTable::isColumnTypeDate($col)), "\n";
        }

    }

}
