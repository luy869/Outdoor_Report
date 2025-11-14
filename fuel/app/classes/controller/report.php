<?php
/**
 * レポートコントローラー
 * 
 * アウトドアレポートのCRUD操作、検索、いいね機能を管理
 * 
 * @package    Outdoor_Report
 * @category   Controller
 */

use Model\Report;
use Model\ReportService;

class Controller_Report extends Controller_Template
{
    public $template = 'template';

    /**
     * アクション実行前の処理
     * ログイン認証が必要なアクションをチェック
     */
    public function before()
    {
        parent::before();
        
        $current_action = str_replace('action_', '', Request::active()->action);

        // ログインが必要なアクション
        $login_required_actions = ['create', 'store', 'edit', 'update', 'delete'];
        
        // ログインが必要なアクションでログインしていない場合
        if (in_array($current_action, $login_required_actions) && !Session::get('user_id')) {
            Session::set_flash('error', 'ログインが必要です');
            Response::redirect('report/index');
        }
    }

    /**
     * レポート一覧（タイムライン）+ 検索機能
     * 
     * 公開レポートを時系列で表示
     * キーワード、タグ、場所、日付範囲での絞り込み検索に対応
     */
    public function action_index()
    {
        $params = [
            'keyword' => Input::get('keyword', ''),
            'tag' => Input::get('tag', ''),
            'location' => Input::get('location', ''),
            'date_from' => Input::get('date_from', ''),
            'date_to' => Input::get('date_to', ''),
        ];
        $user_id = Session::get('user_id');
        $data['reports'] = ReportService::get_timeline($params, $user_id);
        $data = array_merge($data, $params);
        $this->template->title = 'タイムライン';
        $this->template->content = View::forge('report/index_new', $data, false);
    }

    /**
     * レポート詳細
     */
    public function action_view($id = null)
    {
        $data['report'] = ReportService::get_detail($id);
        if (!$data['report']) {
            Session::set_flash('error', 'レポートが見つかりません');
            Response::redirect('report');
        }
        $this->template->title = $data['report']['title'];
        $this->template->content = View::forge('report/view_new', $data, false);
    }

    /**
     * レポート新規作成フォーム
     */
    public function action_create()
    {
        $this->template->title = '新規レポート作成';
        $this->template->content = View::forge('report/create_new');
    }

    /**
     * レポート保存
     */
    /**
     * POST専用: レポート新規作成
     */
    public function post_store()
    {
        $val = Report::validate('create');
        if ($val->run()) {
            $user_id = Session::get('user_id');
            $input = Input::post();
            list($success, $report_id, $message) = ReportService::create_report($input, $user_id);
            if ($success) {
                Session::set_flash('success', $message);
                Response::redirect('report/view/' . $report_id);
            } else {
                Session::set_flash('error', $message);
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
        $user_id = Session::get('user_id');
        $data = ReportService::get_edit_data($id, $user_id);
        if (isset($data['error'])) {
            Session::set_flash('error', $data['error']);
            Response::redirect('report');
        }
        $this->template->title = 'レポート編集';
        $this->template->content = View::forge('report/edit_new', $data);
    }

    /**
     * レポート更新
     */
    /**
     * POST専用: レポート更新
     */
    public function post_update($id = null)
    {
        $val = Report::validate('update');
        if ($val->run()) {
            $user_id = Session::get('user_id');
            $input = Input::post();
            list($success, $message) = ReportService::update_report($id, $input, $user_id);
            if ($success) {
                Session::set_flash('success', $message);
                Response::redirect('report/view/' . $id);
            } else {
                Session::set_flash('error', $message);
            }
        } else {
            Session::set_flash('error', $val->error());
        }
        Response::redirect('report/edit/' . $id);
    }

    /**
     * レポート削除
     */
    /**
     * POST専用: レポート削除
     */
    public function post_delete($id = null)
    {
        if ($id === null) {
            Session::set_flash('error', 'レポートIDが指定されていません');
            Response::redirect('report');
        }
        $user_id = Session::get('user_id');
        list($success, $message) = ReportService::delete_report($id, $user_id);
        // 削除後のDB状況をログ出力
        $report_count = DB::select(DB::expr('COUNT(*) as cnt'))->from('reports')->where('id', $id)->execute()->get('cnt');
        $photo_count = DB::select(DB::expr('COUNT(*) as cnt'))->from('photos')->where('report_id', $id)->execute()->get('cnt');
        $expense_count = DB::select(DB::expr('COUNT(*) as cnt'))->from('expenses')->where('report_id', $id)->execute()->get('cnt');
        $tag_count = DB::select(DB::expr('COUNT(*) as cnt'))->from('report_tags')->where('report_id', $id)->execute()->get('cnt');
        \Log::info("削除確認: reports={$report_count}, photos={$photo_count}, expenses={$expense_count}, report_tags={$tag_count}");
        Session::set_flash($success ? 'success' : 'error', $message);
        Response::redirect('report');
    }

    /**
     * いいねをトグル（Ajax用）
     */
    public function post_toggle_like($report_id)
    {
        // ログインチェック
        $user_id = Session::get('user_id');
        if (!$user_id) {
            return Response::forge(json_encode([
                'success' => false, 
                'message' => 'ログインが必要です'
            ]), 401)->set_header('Content-Type', 'application/json');
        }

        try {
            // すでにいいねしているか確認
            $existing_like = DB::select()
                ->from('likes')
                ->where('user_id', $user_id)
                ->where('report_id', $report_id)
                ->execute()
                ->as_array();

            if (count($existing_like) > 0) {
                // いいね削除
                DB::delete('likes')
                    ->where('user_id', $user_id)
                    ->where('report_id', $report_id)
                    ->execute();
                $liked = false;
            } else {
                // いいね追加
                DB::insert('likes')
                    ->set([
                        'user_id' => $user_id, 
                        'report_id' => $report_id
                    ])
                    ->execute();
                $liked = true;
            }

            // いいね数を取得
            $like_count = DB::select(DB::expr('COUNT(*) as count'))
                ->from('likes')
                ->where('report_id', $report_id)
                ->execute()
                ->get('count');

            // 新しいCSRFトークンを生成
            $new_csrf_token = \Security::fetch_token();

            return Response::forge(json_encode([
                'success' => true,
                'liked' => $liked,
                'like_count' => (int)$like_count,
                'csrf_token' => $new_csrf_token
            ]), 200)->set_header('Content-Type', 'application/json');

        } catch (\Database_Exception $e) {
            Log::error('Like toggle database error: ' . $e->getMessage(), __METHOD__);
            return Response::forge(json_encode([
                'success' => false, 
                'message' => 'データベースエラーが発生しました'
            ]), 500)->set_header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            Log::error('Like toggle error: ' . $e->getMessage(), __METHOD__);
            return Response::forge(json_encode([
                'success' => false, 
                'message' => 'エラーが発生しました'
            ]), 500)->set_header('Content-Type', 'application/json');
        }
    }
}
