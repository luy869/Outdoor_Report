<?php

class Controller_Report extends Controller_Template
{
    public $template = 'template';

    public function before()
    {
        parent::before();

        // ログインが必要なアクション
        $login_required_actions = ['mypage', 'create', 'store', 'edit', 'update', 'delete'];
        $current_action = str_replace('action_', '', Request::active()->action);
        
        // ログインが必要なアクションでログインしていない場合
        if (in_array($current_action, $login_required_actions) && !Session::get('user_id')) {
            Session::set_flash('error', 'ログインが必要です');
            // 元のページに戻れるようにリファラーを保存
            Response::redirect('report/index');
        }
    }

    /**
     * レポート一覧（タイムライン）+ 検索機能
     */
    public function action_index()
    {
        // 検索パラメータを取得
        $keyword = Input::get('keyword', '');
        $tag = Input::get('tag', '');
        $location = Input::get('location', '');
        $date_from = Input::get('date_from', '');
        $date_to = Input::get('date_to', '');

        // クエリビルダー開始
        $query = DB::select(
                'reports.id',
                'reports.title',
                'reports.body',
                'reports.visit_date',
                'reports.created_at',
                'reports.user_id',
                'reports.location_id',
                'users.username'
            )
            ->from('reports')
            ->join('users', 'INNER')
            ->on('reports.user_id', '=', 'users.id')
            ->where('reports.privacy', 0); // 公開のみ

        // キーワード検索（タイトルまたは本文）
        if (!empty($keyword)) {
            $query->where_open()
                ->where('reports.title', 'LIKE', '%' . $keyword . '%')
                ->or_where('reports.body', 'LIKE', '%' . $keyword . '%')
                ->where_close();
        }

        // タグ検索
        if (!empty($tag)) {
            $query->join('report_tags', 'INNER')
                ->on('reports.id', '=', 'report_tags.report_id')
                ->join('tags', 'INNER')
                ->on('report_tags.tag_id', '=', 'tags.id')
                ->where('tags.name', 'LIKE', '%' . $tag . '%');
        }

        // 場所検索 - location_idがある場合のみJOIN
        if (!empty($location)) {
            $query->join('locations', 'LEFT')
                ->on('reports.location_id', '=', 'locations.id')
                ->where('locations.name', 'LIKE', '%' . $location . '%');
        }

        // 日付範囲検索
        if (!empty($date_from)) {
            $query->where('reports.visit_date', '>=', $date_from);
        }
        if (!empty($date_to)) {
            $query->where('reports.visit_date', '<=', $date_to);
        }

        $query->order_by('reports.created_at', 'DESC');

        $results = $query->execute();

        $data['reports'] = array();
        foreach ($results as $row) {
            // location情報を別途取得
            $location_name = null;
            if ($row['location_id']) {
                $loc = DB::select('name')
                    ->from('locations')
                    ->where('id', $row['location_id'])
                    ->execute()
                    ->current();
                $location_name = $loc ? $loc['name'] : null;
            }

            // 最初の写真を取得
            $first_photo = DB::select('image_url')
                ->from('photos')
                ->where('report_id', $row['id'])
                ->limit(1)
                ->execute()
                ->current();

            $data['reports'][] = array(
                'id' => (int)$row['id'],
                'title' => (string)$row['title'],
                'body' => (string)$row['body'],
                'visit_date' => (string)$row['visit_date'],
                'created_at' => (string)$row['created_at'],
                'username' => (string)$row['username'],
                'location_name' => $location_name ? (string)$location_name : '',
                'image_url' => $first_photo ? (string)$first_photo['image_url'] : null,
            );
        }

        // 検索条件も渡す
        $data['keyword'] = $keyword;
        $data['tag'] = $tag;
        $data['location'] = $location;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

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
                    'privacy' => Input::post('privacy') === '0' ? 0 : 1,  // '0'が送信されたら0(公開)、それ以外は1(非公開)
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
        $report = Model_Report::find($id);

        if (!$report) {
            Session::set_flash('error', 'レポートが見つかりません');
            Response::redirect('report');
        }

        // 自分のレポートかチェック
        if ($report->user_id != Session::get('user_id')) {
            Session::set_flash('error', '編集権限がありません');
            Response::redirect('report');
        }

        // データを安全に配列化
        $data = array();
        $data['report_id'] = (int)$report->id;
        $data['title'] = (string)$report->title;
        $data['body'] = (string)$report->body;
        $data['visit_date'] = (string)$report->visit_date;
        $data['privacy'] = (int)$report->privacy;
        $data['location_id'] = $report->location_id ? (int)$report->location_id : 0;

        // 既存の写真を取得
        $photos_result = DB::select()
            ->from('photos')
            ->where('report_id', $id)
            ->execute();
        
        $data['photos'] = array();
        if ($photos_result) {
            foreach ($photos_result as $photo) {
                $data['photos'][] = array(
                    'id' => (int)$photo['id'],
                    'image_url' => (string)$photo['image_url']
                );
            }
        }

        // 既存の費用を取得
        $expenses_result = DB::select()
            ->from('expenses')
            ->where('report_id', $id)
            ->execute();
        
        $data['expenses'] = array();
        if ($expenses_result) {
            foreach ($expenses_result as $expense) {
                $data['expenses'][] = array(
                    'item_name' => (string)$expense['item_name'],
                    'amount' => (int)$expense['amount']
                );
            }
        }

        // 既存のタグを取得
        $tags_result = DB::select('tags.name')
            ->from('tags')
            ->join('report_tags', 'LEFT')
            ->on('tags.id', '=', 'report_tags.tag_id')
            ->where('report_tags.report_id', $id)
            ->execute();
        
        $tag_names = array();
        if ($tags_result) {
            foreach ($tags_result as $tag) {
                if (!empty($tag['name'])) {
                    $tag_names[] = (string)$tag['name'];
                }
            }
        }
        $data['tags_string'] = implode(', ', $tag_names);

        // 場所情報を取得
        $data['location_name'] = '';
        if ($report->location_id) {
            $location = DB::select('name')
                ->from('locations')
                ->where('id', $report->location_id)
                ->execute()
                ->current();
            if ($location && !empty($location['name'])) {
                $data['location_name'] = (string)$location['name'];
            }
        }

        $this->template->title = 'レポート編集';
        $this->template->content = View::forge('report/edit_new', $data);
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
            try {
                // トランザクション開始
                DB::start_transaction();
                
                // 場所情報の処理
                $location_id = null;
                $location_name = Input::post('location');
                if (!empty($location_name)) {
                    $location = DB::select('id')
                        ->from('locations')
                        ->where('name', $location_name)
                        ->execute()
                        ->current();
                    
                    if ($location) {
                        $location_id = $location['id'];
                    } else {
                        $result = DB::insert('locations')
                            ->set(array(
                                'name' => $location_name,
                                'created_at' => date('Y-m-d H:i:s'),
                            ))
                            ->execute();
                        $location_id = $result[0];
                    }
                }
                
                // レポート本体を更新
                $report->title = Input::post('title');
                $report->body = Input::post('body');
                $report->visit_date = Input::post('visit_date');
                $report->privacy = Input::post('privacy') === '0' ? 0 : 1;  // '0'が送信されたら0(公開)、それ以外は1(非公開)
                $report->location_id = $location_id;

                if ($report->save()) {
                    // 既存の費用を削除して再作成
                    DB::delete('expenses')
                        ->where('report_id', $id)
                        ->execute();
                    
                    $expense_items = Input::post('expense_item', array());
                    $expense_amounts = Input::post('expense_amount', array());
                    
                    if (!empty($expense_items) && is_array($expense_items)) {
                        foreach ($expense_items as $index => $item_name) {
                            if (!empty($item_name) && !empty($expense_amounts[$index])) {
                                DB::insert('expenses')
                                    ->set(array(
                                        'report_id' => $id,
                                        'item_name' => $item_name,
                                        'amount' => (int)$expense_amounts[$index],
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ))
                                    ->execute();
                            }
                        }
                    }
                    
                    // 既存のタグ関連付けを削除
                    DB::delete('report_tags')
                        ->where('report_id', $id)
                        ->execute();
                    
                    // タグ情報の保存
                    $tags_input = Input::post('tags', '');
                    if (!empty($tags_input)) {
                        $tag_names = array_map('trim', explode(',', $tags_input));
                        
                        foreach ($tag_names as $tag_name) {
                            if (!empty($tag_name)) {
                                $tag = DB::select('id')
                                    ->from('tags')
                                    ->where('name', $tag_name)
                                    ->execute()
                                    ->current();
                                
                                if ($tag) {
                                    $tag_id = $tag['id'];
                                } else {
                                    $result = DB::insert('tags')
                                        ->set(array(
                                            'name' => $tag_name,
                                            'created_at' => date('Y-m-d H:i:s'),
                                        ))
                                        ->execute();
                                    $tag_id = $result[0];
                                }
                                
                                DB::insert('report_tags')
                                    ->set(array(
                                        'report_id' => $id,
                                        'tag_id' => $tag_id,
                                    ))
                                    ->execute();
                            }
                        }
                    }
                    
                    // 削除する既存画像の処理
                    $delete_photo_ids = Input::post('delete_photos', array());
                    if (!empty($delete_photo_ids) && is_array($delete_photo_ids)) {
                        foreach ($delete_photo_ids as $photo_id) {
                            DB::delete('photos')
                                ->where('id', $photo_id)
                                ->where('report_id', $id)
                                ->execute();
                        }
                    }
                    
                    // 新しい写真のアップロード
                    if (!empty($_FILES['photos']['name'][0])) {
                        $upload_config = array(
                            'path' => DOCROOT.'assets/uploads/photos/',
                            'randomize' => true,
                            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
                        );
                        
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
                                        'report_id' => $id,
                                        'image_url' => '/assets/uploads/photos/' . $file['saved_as'],
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ))
                                    ->execute();
                            }
                        }
                    }
                    
                    DB::commit_transaction();
                    Session::set_flash('success', 'レポートを更新しました!');
                    Response::redirect('report/view/' . $id);
                } else {
                    DB::rollback_transaction();
                    Session::set_flash('error', '更新に失敗しました');
                }
            } catch (Exception $e) {
                DB::rollback_transaction();
                Session::set_flash('error', 'エラーが発生しました: ' . $e->getMessage());
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
        // ① レポートを取得
        $report = Model_Report::find($id);

        // ② 権限チェック
        if (!$report || $report->user_id != Session::get('user_id')) {
            Session::set_flash('error', '削除権限がありません');
            Response::redirect('report');
        }

        try {
            // ③ トランザクション開始
            DB::start_transaction();
            
            // ④ 関連データを削除
            // 写真を削除
            DB::delete('photos')
                ->where('report_id', $id)
                ->execute();
            
            // 費用を削除
            DB::delete('expenses')
                ->where('report_id', $id)
                ->execute();
            
            // タグの関連付けを削除
            DB::delete('report_tags')
                ->where('report_id', $id)
                ->execute();
            
            // ⑤ レポート本体を削除
            if ($report->delete()) {
                // ⑥ トランザクションコミット
                DB::commit_transaction();
                Session::set_flash('success', 'レポートを削除しました');
            } else {
                DB::rollback_transaction();
                Session::set_flash('error', '削除に失敗しました');
            }
        } catch (Exception $e) {
            // エラー時はロールバック
            DB::rollback_transaction();
            Session::set_flash('error', '削除中にエラーが発生しました: ' . $e->getMessage());
        }

        Response::redirect('report');
    }

    /**
     * マイページ - プロフィールページにリダイレクト
     */
    public function action_mypage()
    {
        Response::redirect('user/profile');
    }
}
