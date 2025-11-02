<?php
class Controller_Welcome extends Controller {

    public function action_index() {
        // ログインチェック
        if (!Session::get('user_id')) {
            Response::redirect('auth/login');
        }

        // ログインしていればレポート一覧へ
        Response::redirect('report/index');
    }

    public function action_knockout_test() {
        // knockout.jsのテストページ
        return View::forge('welcome/knockout_test');
    }
}
