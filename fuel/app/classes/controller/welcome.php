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
}
