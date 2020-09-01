<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Controller\Admin\ControllerBase;
use App\Model\AdminAccounts;

class Home extends ControllerBase
{
    //
    public function index(Request $request, Response $response, $routeArguments)
    {
        //
        $context = $this->getFlashSessions();
        // 出力
        return $this->write($response, 'index.twig', $context);
    }
    //
    public function login(Request $request, Response $response, $routeArguments)
    {
        // id と passwordを取得
        $id = strval($request->getParsedBody()['id'] ?? '');
        $pw = strval($request->getParsedBody()['pw'] ?? '');
//var_dump($id, $pw); exit;
        
        // validate
        if ( ('' === $id)||('' === $pw) ) {
            $this->setFlashSessions('error', 'empty');
            $this->setFlashSessions('id', $id);
            return $this->redirect($response, $this->urlFor('admin.index'));
        }
        // XXX ここまでで「idとパスワード」が取得できた

//var_dump($admin_obj);
        try {
            // DBからレコードを取得
            $admin_obj = AdminAccounts::find($id);
            if (null === $admin_obj) {
                throw new \Exception();
            }

            // ロックの確認
            if (null !== $admin_obj->lock_time) {
                // 時間の比較
                if (time() < strtotime($admin_obj->lock_time)) {
                    // XXX 「ロックタイム中なのにログインしてきたよ」 mailの射出
                    throw new \Exception();
                }
                // else
                $admin_obj->update(['lock_time' => null, 'error_num' => 0]);
            }

            // パスワードを比較
            if (false === password_verify($pw, $admin_obj->admin_account_pass)) {
                // エラーカウントをインクリメント
                $data = [];
                $data['error_num'] = $admin_obj->error_num + 1;
                // もしｎ回以上ミスってたらアカウントロック
                if (5 <= $data['error_num']) { // XXX MAGIC NUMBER
                    // XXX 「アカウントロックになったよ」 mailの射出
                    $data['error_num'] = 0;
                    $data['lock_time'] = date('Y-m-d H:i:s', time() + 30 * 60); // XXX ロックは30分 XXX MAGIC NUMBER
                }
                $admin_obj->update($data);
                // どのみちアウトなので例外投げる
                throw new \Exception();
            }
            // エラーカウントをリセット
            if (0 != $admin_obj->error_num) {
                $admin_obj->update(['error_num' => 0]);
            }
        } catch (\Exception $e) {
            $this->setFlashSessions('error', 'miss');
            $this->setFlashSessions('id', $id);
            return $this->redirect($response, $this->urlFor('admin.index'));
        }

        // XXX ここまできたら「認証成功」
        session_regenerate_id(true); // セキュリティの担保
        //
        $admin = $admin_obj->toArray();
        unset($admin['admin_account_pass']);
        unset($admin['error_num']);
        unset($admin['lock_time']);
        unset($admin['created_at']);
        unset($admin['updated_at']);
        $_SESSION['admin'] = $admin;

        //
        return $this->redirect($response, $this->urlFor('admin.top'));
    }
}
