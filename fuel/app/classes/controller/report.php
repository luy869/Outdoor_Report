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
        // 検索パラメータを取得
        $keyword = Input::get('keyword', '');
        $tag = Input::get('tag', '');
        $location = Input::get('location', '');
        $date_from = Input::get('date_from', '');
        $date_to = Input::get('date_to', '');

        // クエリビルダー開始（N+1問題を解消: locationもJOINで一緒に取得）
        $query = DB::select(
                'reports.id',
                'reports.title',
                'reports.body',
                'reports.visit_date',
                'reports.created_at',
                'reports.user_id',
                'reports.location_id',
                'users.username',
                array('locations.name', 'location_name')
            )
            ->from('reports')
            ->join('users', 'INNER')
            ->on('reports.user_id', '=', 'users.id')
            ->join('locations', 'LEFT')
            ->on('reports.location_id', '=', 'locations.id')
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

        $results = $query->execute()->as_array();

        $user_id = Session::get('user_id'); // ログインユーザーID

        // N+1問題を解消: レポートIDを収集
        $report_ids = array();
        foreach ($results as $row) {
            $report_ids[] = $row['id'];
        }

        // 写真を一括取得（N+1問題を解消）
        $photos_map = array();
        if (!empty($report_ids)) {
            $photos_result = DB::select('report_id', 'image_url')
                ->from('photos')
                ->where('report_id', 'IN', $report_ids)
                ->execute();
            
            foreach ($photos_result as $photo) {
                if (!isset($photos_map[$photo['report_id']])) {
                    $photos_map[$photo['report_id']] = $photo['image_url'];
                }
            }
        }

        // いいね数を一括取得（N+1問題を解消）
        $likes_map = array();
        if (!empty($report_ids)) {
            $likes_result = DB::select('report_id', DB::expr('COUNT(*) as count'))
                ->from('likes')
                ->where('report_id', 'IN', $report_ids)
                ->group_by('report_id')
                ->execute();
            
            foreach ($likes_result as $like) {
                $likes_map[$like['report_id']] = (int)$like['count'];
            }
        }

        // ユーザーのいいね状態を一括取得（N+1問題を解消）
        $user_likes_map = array();
        if ($user_id && !empty($report_ids)) {
            $user_likes_result = DB::select('report_id')
                ->from('likes')
                ->where('user_id', $user_id)
                ->where('report_id', 'IN', $report_ids)
                ->execute();
            
            foreach ($user_likes_result as $user_like) {
                $user_likes_map[$user_like['report_id']] = true;
            }
        }

        // データを組み立て
        $data['reports'] = array();
        foreach ($results as $row) {
            $report_id = (int)$row['id'];
            
            $data['reports'][] = array(
                'id' => $report_id,
                'title' => (string)$row['title'],
                'body' => (string)$row['body'],
                'visit_date' => (string)$row['visit_date'],
                'created_at' => (string)$row['created_at'],
                'username' => (string)$row['username'],
                'user_id' => (int)$row['user_id'],
                'location_name' => isset($row['location_name']) ? (string)$row['location_name'] : '',
                'image_url' => isset($photos_map[$report_id]) ? $photos_map[$report_id] : null,
                'like_count' => isset($likes_map[$report_id]) ? $likes_map[$report_id] : 0,
                'user_liked' => isset($user_likes_map[$report_id]),
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
        $report = Report::find($id);

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
    /**
     * POST専用: レポート新規作成
     */
    public function post_store()
    {
        $val = Report::validate('create');

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
                $report = Report::forge(array(
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
                    
                    // 早期リターン: 費用データがない場合はスキップ
                    if (empty($expense_items) || !is_array($expense_items)) {
                        // 何もしない（次の処理へ）
                    } else {
                        foreach ($expense_items as $index => $item_name) {
                            // 早期continue: 無効なデータはスキップ
                            if (empty($item_name) || empty($expense_amounts[$index])) {
                                continue;
                            }
                            
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
                    
                    // タグ情報の保存
                    $tags_input = Input::post('tags', '');
                    
                    // 早期リターン: タグがない場合はスキップ
                    if (empty($tags_input)) {
                        // 何もしない（次の処理へ）
                    } else {
                        // カンマ区切りで分割
                        $tag_names = array_map('trim', explode(',', $tags_input));
                        
                        foreach ($tag_names as $tag_name) {
                            // 早期continue: 空のタグはスキップ
                            if (empty($tag_name)) {
                                continue;
                            }
                            
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
                    
                    // 写真のアップロード処理
                    if (!empty($_FILES['photos']['name'][0])) {
                        $upload_config = array(
                            'path' => DOCROOT.'assets/uploads/photos/',
                            'randomize' => true,
                            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
                        );
                        
                        // アップロードディレクトリが存在しない場合は作成
                        if (!is_dir($upload_config['path'])) {
                            mkdir($upload_config['path'], 0755, true);
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
        $report = Report::find($id);

        if (!$report) {
            Session::set_flash('error', 'レポートが見つかりません');
            Response::redirect('report');
        }

        // 自分のレポートかチェック（型を明示的に変換）
        if ((int)$report->user_id !== (int)Session::get('user_id')) {
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
    /**
     * POST専用: レポート更新
     */
    public function post_update($id = null)
    {
        $report = Report::find($id);

        // 権限チェック（型を明示的に変換）
        if (!$report || (int)$report->user_id !== (int)Session::get('user_id')) {
            Session::set_flash('error', '編集権限がありません');
            Response::redirect('report');
        }

        $val = Report::validate('update');

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
                
                // チェックボックスがチェックされている場合は'0'が送信される（公開）、されていない場合はnull（非公開=1）
                $report->privacy = Input::post('privacy') === '0' ? 0 : 1;
                $report->location_id = $location_id;

                if ($report->save()) {
                    // 既存の費用を削除して再作成
                    DB::delete('expenses')
                        ->where('report_id', $id)
                        ->execute();
                    
                    $expense_items = Input::post('expense_item', array());
                    $expense_amounts = Input::post('expense_amount', array());
                    
                    // 早期リターン: 費用データがない場合はスキップ
                    if (empty($expense_items) || !is_array($expense_items)) {
                        // 何もしない（次の処理へ）
                    } else {
                        foreach ($expense_items as $index => $item_name) {
                            // 早期continue: 無効なデータはスキップ
                            if (empty($item_name) || empty($expense_amounts[$index])) {
                                continue;
                            }
                            
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
                    
                    // 既存のタグ関連付けを削除
                    DB::delete('report_tags')
                        ->where('report_id', $id)
                        ->execute();
                    
                    // タグ情報の保存
                    $tags_input = Input::post('tags', '');
                    
                    // 早期リターン: タグがない場合はスキップ
                    if (empty($tags_input)) {
                        // 何もしない（次の処理へ）
                    } else {
                        $tag_names = array_map('trim', explode(',', $tags_input));
                        
                        foreach ($tag_names as $tag_name) {
                            // 早期continue: 空のタグはスキップ
                            if (empty($tag_name)) {
                                continue;
                            }
                            
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
                            mkdir($upload_config['path'], 0755, true);
                        }
                        
                        Upload::process($upload_config);
                        
                        if (Upload::is_valid()) {
                            Upload::save();
                            $files = Upload::get_files();
                            
                            \Log::info('Upload successful: ' . count($files) . ' files');
                            
                            // sort_orderを取得（既存画像の最大値+1から）
                            $max_sort = DB::select(DB::expr('COALESCE(MAX(sort_order), 0) as max_sort'))
                                ->from('photos')
                                ->where('report_id', $id)
                                ->execute()
                                ->current();
                            $next_sort = $max_sort ? $max_sort['max_sort'] + 1 : 1;
                            
                            foreach ($files as $file) {
                                DB::insert('photos')
                                    ->set(array(
                                        'report_id' => $id,
                                        'image_url' => '/assets/uploads/photos/' . $file['saved_as'],
                                        'sort_order' => $next_sort++,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ))
                                    ->execute();
                            }
                        } else {
                            // アップロードエラーをログに記録
                            $errors = Upload::get_errors();
                            foreach ($errors as $error) {
                                \Log::error('Photo upload error: ' . $error['errors'][0]['message']);
                            }
                        }
                    } else {
                        \Log::info('No files in $_FILES or empty filename');
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
    /**
     * POST専用: レポート削除
     */
    public function post_delete($id = null)
    {
        // ① レポートを取得
        $report = Report::find($id);

        // ② 権限チェック（型を明示的に変換）
        if (!$report || (int)$report->user_id !== (int)Session::get('user_id')) {
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
