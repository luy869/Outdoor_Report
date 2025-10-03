<?php

class Controller_Report extends Controller_Template
{
    public $template = 'template';

    public function before()
    {
        parent::before();

        // ログインチェック
        if (!Session::get('user_id')) {
            Response::redirect('auth/login');
        }
    }

    /**
     * レポート一覧
     */
    public function action_index()
    {
        // 公開レポートを新しい順に取得
        $data['reports'] = Model_Report::query()
            ->where('privacy', 1)
            ->order_by('created_at', 'desc')
            ->get();

        // ユーザー情報も取得
        foreach ($data['reports'] as $report) {
            $user = DB::select('username', 'email')
                ->from('users')
                ->where('id', $report->user_id)
                ->execute()
                ->current();
            $report->username = $user ? $user['username'] : '不明';
        }

        $this->template->title = 'レポート一覧';
        $this->template->content = View::forge('report/index', $data);
    }

    /**
     * レポート詳細
     */
    public function action_view($id = null)
    {
        $data['report'] = Model_Report::find($id);

        if (!$data['report']) {
            Session::set_flash('error', 'レポートが見つかりません');
            Response::redirect('report');
        }

        // ユーザー情報を取得
        $user = DB::select('username', 'email')
            ->from('users')
            ->where('id', $data['report']->user_id)
            ->execute()
            ->current();
        $data['report']->username = $user ? $user['username'] : '不明';

        $this->template->title = $data['report']->title;
        $this->template->content = View::forge('report/view', $data);
    }

    /**
     * レポート新規作成フォーム
     */
    public function action_create()
    {
        $this->template->title = '新規レポート作成';
        $this->template->content = View::forge('report/create');
    }

    /**
     * レポート保存
     */
    public function action_store()
    {
        if (Input::method() !== 'POST') {
            Response::redirect('report/create');
        }

        $val = Model_Report::validate('create');

        if ($val->run()) {
            $report = Model_Report::forge(array(
                'user_id' => Session::get('user_id'),
                'title' => Input::post('title'),
                'body' => Input::post('body'),
                'visit_date' => Input::post('visit_date'),
                'privacy' => Input::post('privacy', 1),
            ));

            if ($report->save()) {
                Session::set_flash('success', 'レポートを投稿しました!');
                Response::redirect('report');
            } else {
                Session::set_flash('error', '保存に失敗しました');
            }
        } else {
            Session::set_flash('error', $val->error());
        }

        Response::redirect('report/create');
    }

    /**
     * レポート編集フォーム
     */
    public function action_edit($id = null)
    {
        $data['report'] = Model_Report::find($id);

        if (!$data['report']) {
            Session::set_flash('error', 'レポートが見つかりません');
            Response::redirect('report');
        }

        // 自分のレポートかチェック
        if ($data['report']->user_id != Session::get('user_id')) {
            Session::set_flash('error', '編集権限がありません');
            Response::redirect('report');
        }

        $this->template->title = 'レポート編集';
        $this->template->content = View::forge('report/edit', $data);
    }

    /**
     * レポート更新
     */
    public function action_update($id = null)
    {
        if (Input::method() !== 'POST') {
            Response::redirect('report');
        }

        $report = Model_Report::find($id);

        if (!$report || $report->user_id != Session::get('user_id')) {
            Session::set_flash('error', '編集権限がありません');
            Response::redirect('report');
        }

        $val = Model_Report::validate('update');

        if ($val->run()) {
            $report->title = Input::post('title');
            $report->body = Input::post('body');
            $report->visit_date = Input::post('visit_date');
            $report->privacy = Input::post('privacy', 1);

            if ($report->save()) {
                Session::set_flash('success', 'レポートを更新しました!');
                Response::redirect('report/view/' . $id);
            } else {
                Session::set_flash('error', '更新に失敗しました');
            }
        } else {
            Session::set_flash('error', $val->error());
        }

        Response::redirect('report/edit/' . $id);
    }

    /**
     * レポート削除
     */
    public function action_delete($id = null)
    {
        $report = Model_Report::find($id);

        if (!$report || $report->user_id != Session::get('user_id')) {
            Session::set_flash('error', '削除権限がありません');
            Response::redirect('report');
        }

        if ($report->delete()) {
            Session::set_flash('success', 'レポートを削除しました');
        } else {
            Session::set_flash('error', '削除に失敗しました');
        }

        Response::redirect('report');
    }
}
