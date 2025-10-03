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
        $reports = Model_Report::query()
            ->where('privacy', 1)
            ->order_by('created_at', 'desc')
            ->get();

        $data['reports'] = array();
        
        // ユーザー情報とlocation情報も取得し、配列に変換
        foreach ($reports as $report) {
            $user = DB::select('username', 'email')
                ->from('users')
                ->where('id', $report->user_id)
                ->execute()
                ->current();
            
            // location情報を取得
            $location_name = null;
            if ($report->location_id) {
                $location = DB::select('name')
                    ->from('locations')
                    ->where('id', $report->location_id)
                    ->execute()
                    ->current();
                $location_name = $location ? $location['name'] : null;
            }
            
            // 最初の写真を取得
            $first_photo = DB::select('image_url')
                ->from('photos')
                ->where('report_id', $report->id)
                ->limit(1)
                ->execute()
                ->current();
            
            // 配列に変換して安全に渡す
            $data['reports'][] = array(
                'id' => (int)$report->id,
                'title' => $report->title ? (string)$report->title : '無題',
                'body' => $report->body ? (string)$report->body : '',
                'visit_date' => (string)$report->visit_date,
                'created_at' => (string)$report->created_at,
                'username' => $user ? (string)$user['username'] : '不明',
                'location_name' => $location_name ? (string)$location_name : '',
                'image_url' => $first_photo ? (string)$first_photo['image_url'] : null,
            );
        }

        $this->template->title = 'タイムライン';
        $this->template->content = View::forge('report/index_new', $data, false);
    }

    /**
     * レポート詳細
     */
    public function action_view($id = null)
    {
        $report = Model_Report::find($id);

        if (!$report) {
            Session::set_flash('error', 'レポートが見つかりません');
            Response::redirect('report');
        }

        // ユーザー情報を取得
        $user = DB::select('username', 'email')
            ->from('users')
            ->where('id', $report->user_id)
            ->execute()
            ->current();
        
        // location情報を取得
        $location_name = null;
        if ($report->location_id) {
            $location = DB::select('name')
                ->from('locations')
                ->where('id', $report->location_id)
                ->execute()
                ->current();
            $location_name = $location ? $location['name'] : null;
        }
        
        // 費用情報を取得
        $expenses = DB::select('item_name', 'amount')
            ->from('expenses')
            ->where('report_id', $id)
            ->execute()
            ->as_array();
        
        // タグ情報を取得
        $tags = DB::select('tags.name')
            ->from('report_tags')
            ->join('tags', 'LEFT')
            ->on('report_tags.tag_id', '=', 'tags.id')
            ->where('report_tags.report_id', $id)
            ->execute()
            ->as_array();
        
        $tag_names = array();
        foreach ($tags as $tag) {
            if ($tag['name']) {
                $tag_names[] = $tag['name'];
            }
        }
        
        // 写真情報を取得
        $photos = DB::select('image_url')
            ->from('photos')
            ->where('report_id', $id)
            ->execute()
            ->as_array();
        
        // 配列に変換して安全に渡す
        $data['report'] = array(
            'id' => (int)$report->id,
            'user_id' => (int)$report->user_id,
            'title' => $report->title ? (string)$report->title : '無題',
            'body' => $report->body ? (string)$report->body : '',
            'visit_date' => (string)$report->visit_date,
            'created_at' => (string)$report->created_at,
            'username' => $user ? (string)$user['username'] : '不明',
            'location_name' => $location_name ? (string)$location_name : '',
            'expenses' => $expenses,
            'tags' => $tag_names,
            'photos' => $photos,
        );

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
    public function action_store()
    {
        if (Input::method() !== 'POST') {
            Response::redirect('report/create');
        }

        $val = Model_Report::validate('create');

        if ($val->run()) {
            try {
                // トランザクション開始
                DB::start_transaction();
                
                // 場所情報の処理
                $location_id = null;
                $location_name = Input::post('location');
                if (!empty($location_name)) {
                    // 既存の場所を検索
                    $location = DB::select('id')
                        ->from('locations')
                        ->where('name', $location_name)
                        ->execute()
                        ->current();
                    
                    if ($location) {
                        $location_id = $location['id'];
                    } else {
                        // 新しい場所を作成
                        $result = DB::insert('locations')
                            ->set(array(
                                'name' => $location_name,
                                'created_at' => date('Y-m-d H:i:s'),
                            ))
                            ->execute();
                        $location_id = $result[0];
                    }
                }
                
                // レポート作成
                $report = Model_Report::forge(array(
                    'user_id' => Session::get('user_id'),
                    'location_id' => $location_id,
                    'title' => Input::post('title'),
                    'body' => Input::post('body'),
                    'visit_date' => Input::post('visit_date'),
                    'privacy' => Input::post('privacy', 1),
                ));

                if ($report->save()) {
                    $report_id = $report->id;
                    
                    // 費用情報の保存
                    $expense_items = Input::post('expense_item', array());
                    $expense_amounts = Input::post('expense_amount', array());
                    
                    if (!empty($expense_items) && is_array($expense_items)) {
                        foreach ($expense_items as $index => $item_name) {
                            if (!empty($item_name) && !empty($expense_amounts[$index])) {
                                DB::insert('expenses')
                                    ->set(array(
                                        'report_id' => $report_id,
                                        'item_name' => $item_name,
                                        'amount' => (int)$expense_amounts[$index],
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ))
                                    ->execute();
                            }
                        }
                    }
                    
                    // タグ情報の保存
                    $tags_input = Input::post('tags', '');
                    if (!empty($tags_input)) {
                        // カンマ区切りで分割
                        $tag_names = array_map('trim', explode(',', $tags_input));
                        
                        foreach ($tag_names as $tag_name) {
                            if (!empty($tag_name)) {
                                // 既存のタグを検索
                                $tag = DB::select('id')
                                    ->from('tags')
                                    ->where('name', $tag_name)
                                    ->execute()
                                    ->current();
                                
                                if ($tag) {
                                    $tag_id = $tag['id'];
                                } else {
                                    // 新しいタグを作成
                                    $result = DB::insert('tags')
                                        ->set(array(
                                            'name' => $tag_name,
                                            'created_at' => date('Y-m-d H:i:s'),
                                        ))
                                        ->execute();
                                    $tag_id = $result[0];
                                }
                                
                                // レポートとタグの関連付け
                                DB::insert('report_tags')
                                    ->set(array(
                                        'report_id' => $report_id,
                                        'tag_id' => $tag_id,
                                    ))
                                    ->execute();
                            }
                        }
                    }
                    
                    // 写真のアップロード処理
                    if (!empty($_FILES['photos']['name'][0])) {
                        $upload_config = array(
                            'path' => DOCROOT.'assets/uploads/photos/',
                            'randomize' => true,
                            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
                        );
                        
                        // アップロードディレクトリが存在しない場合は作成
                        if (!is_dir($upload_config['path'])) {
                            mkdir($upload_config['path'], 0777, true);
                        }
                        
                        Upload::process($upload_config);
                        
                        if (Upload::is_valid()) {
                            Upload::save();
                            $files = Upload::get_files();
                            
                            foreach ($files as $file) {
                                DB::insert('photos')
                                    ->set(array(
                                        'report_id' => $report_id,
                                        'image_url' => '/assets/uploads/photos/' . $file['saved_as'],
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ))
                                    ->execute();
                            }
                        }
                    }
                    
                    // トランザクションコミット
                    DB::commit_transaction();
                    
                    Session::set_flash('success', 'レポートを投稿しました!');
                    Response::redirect('report/view/' . $report_id);
                } else {
                    DB::rollback_transaction();
                    Session::set_flash('error', '保存に失敗しました');
                }
            } catch (Exception $e) {
                DB::rollback_transaction();
                Session::set_flash('error', 'エラーが発生しました: ' . $e->getMessage());
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
