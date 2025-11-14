<?php
namespace Model;

use \DB;
use \Session;
use Model\Report;

class ReportService
{
    /**
     * レポート削除（関連データ・画像ファイル含む）
     * @param int $id レポートID
     * @param int $user_id ユーザーID
     * @return array [success(bool), message(string)]
     */
    public static function delete_report($id, $user_id)
    {
        $report = Report::find($id);
        if (!$report || (int)$report->user_id !== (int)$user_id) {
            return [false, '削除権限がありません'];
        }
        try {
            DB::start_transaction();
            // 画像ファイル物理削除
            $photos = DB::select('image_url')->from('photos')->where('report_id', $id)->execute()->as_array();
            foreach ($photos as $photo) {
                if (!empty($photo['image_url'])) {
                    $file_path = DOCROOT . ltrim($photo['image_url'], '/');
                    if (is_file($file_path)) {
                        @unlink($file_path);
                    }
                }
            }
            // 写真削除
            DB::delete('photos')->where('report_id', $id)->execute();
            // 費用削除
            DB::delete('expenses')->where('report_id', $id)->execute();
            // タグ関連削除
            DB::delete('report_tags')->where('report_id', $id)->execute();
            // レポート本体削除
            if ($report->delete()) {
                DB::commit_transaction();
                return [true, 'レポートと画像を削除しました'];
            } else {
                DB::rollback_transaction();
                return [false, '削除に失敗しました'];
            }
        } catch (\Exception $e) {
            DB::rollback_transaction();
            return [false, '削除中にエラーが発生しました: ' . $e->getMessage()];
        }
    }

    /**
     * タイムライン一覧取得（検索・N+1解消済み）
     * @param array $params 検索条件
     * @param int|null $user_id ログインユーザーID
     * @return array
     */
    public static function get_timeline($params, $user_id = null)
    {
        $keyword = $params['keyword'] ?? '';
        $tag = $params['tag'] ?? '';
        $location = $params['location'] ?? '';
        $date_from = $params['date_from'] ?? '';
        $date_to = $params['date_to'] ?? '';

        $query = DB::select(
            'reports.id', 'reports.title', 'reports.body', 'reports.visit_date',
            'reports.created_at', 'reports.user_id', 'reports.location_id',
            'users.username', ['locations.name', 'location_name']
        )
        ->from('reports')
        ->join('users', 'INNER')->on('reports.user_id', '=', 'users.id')
        ->join('locations', 'LEFT')->on('reports.location_id', '=', 'locations.id')
        ->where('reports.privacy', 0);

        if (!empty($keyword)) {
            $query->where_open()
                ->where('reports.title', 'LIKE', '%' . $keyword . '%')
                ->or_where('reports.body', 'LIKE', '%' . $keyword . '%')
                ->where_close();
        }
        if (!empty($tag)) {
            $query->join('report_tags', 'INNER')
                ->on('reports.id', '=', 'report_tags.report_id')
                ->join('tags', 'INNER')
                ->on('report_tags.tag_id', '=', 'tags.id')
                ->where('tags.name', 'LIKE', '%' . $tag . '%');
        }
        if (!empty($location)) {
            $query->join('locations', 'LEFT')
                ->on('reports.location_id', '=', 'locations.id')
                ->where('locations.name', 'LIKE', '%' . $location . '%');
        }
        if (!empty($date_from)) {
            $query->where('reports.visit_date', '>=', $date_from);
        }
        if (!empty($date_to)) {
            $query->where('reports.visit_date', '<=', $date_to);
        }
        $query->order_by('reports.created_at', 'DESC');
        $results = $query->execute()->as_array();

        $report_ids = array_column($results, 'id');
        $photos_map = [];
        if ($report_ids) {
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
        $likes_map = [];
        if ($report_ids) {
            $likes_result = DB::select('report_id', DB::expr('COUNT(*) as count'))
                ->from('likes')
                ->where('report_id', 'IN', $report_ids)
                ->group_by('report_id')
                ->execute();
            foreach ($likes_result as $like) {
                $likes_map[$like['report_id']] = (int)$like['count'];
            }
        }
        $user_likes_map = [];
        if ($user_id && $report_ids) {
            $user_likes_result = DB::select('report_id')
                ->from('likes')
                ->where('user_id', $user_id)
                ->where('report_id', 'IN', $report_ids)
                ->execute();
            foreach ($user_likes_result as $user_like) {
                $user_likes_map[$user_like['report_id']] = true;
            }
        }
        $data = [];
        foreach ($results as $row) {
            $report_id = (int)$row['id'];
            $data[] = [
                'id' => $report_id,
                'title' => (string)$row['title'],
                'body' => (string)$row['body'],
                'visit_date' => (string)$row['visit_date'],
                'created_at' => (string)$row['created_at'],
                'username' => (string)$row['username'],
                'user_id' => (int)$row['user_id'],
                'location_name' => isset($row['location_name']) ? (string)$row['location_name'] : '',
                'image_url' => $photos_map[$report_id] ?? null,
                'like_count' => $likes_map[$report_id] ?? 0,
                'user_liked' => isset($user_likes_map[$report_id]),
            ];
        }
        return $data;
    }

    /**
     * レポート詳細データ取得
     * @param int $id レポートID
     * @return array|null
     */
    public static function get_detail($id)
    {
        $report = Report::find($id);
        if (!$report) return null;
        $user = DB::select('username', 'email')
            ->from('users')
            ->where('id', $report->user_id)
            ->execute()
            ->current();
        $location_name = null;
        if ($report->location_id) {
            $location = DB::select('name')
                ->from('locations')
                ->where('id', $report->location_id)
                ->execute()
                ->current();
            $location_name = $location ? $location['name'] : null;
        }
        $expenses = DB::select('item_name', 'amount')
            ->from('expenses')
            ->where('report_id', $id)
            ->execute()
            ->as_array();
        $tags = DB::select('tags.name')
            ->from('report_tags')
            ->join('tags', 'LEFT')
            ->on('report_tags.tag_id', '=', 'tags.id')
            ->where('report_tags.report_id', $id)
            ->execute()
            ->as_array();
        $tag_names = array();
        foreach ($tags as $tag) {
            if ($tag['name']) $tag_names[] = $tag['name'];
        }
        $photos = DB::select('image_url')
            ->from('photos')
            ->where('report_id', $id)
            ->execute()
            ->as_array();
        return [
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
        ];
    }

    /**
     * レポート新規作成（DB・ファイル操作をモデルに分離）
     */
    public static function create_report($input, $user_id)
    {
        try {
            DB::start_transaction();

            // 場所情報の処理
            $location_id = null;
            $location_name = isset($input['location']) ? $input['location'] : '';
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

            // レポート作成
            $report = Report::forge(array(
                'user_id' => $user_id,
                'location_id' => $location_id,
                'title' => $input['title'],
                'body' => $input['body'],
                'visit_date' => $input['visit_date'],
                'privacy' => isset($input['privacy']) && $input['privacy'] === '0' ? 0 : 1,
            ));

            if ($report->save()) {
                $report_id = $report->id;

                // 費用情報の保存
                $expense_items = isset($input['expense_item']) ? $input['expense_item'] : array();
                $expense_amounts = isset($input['expense_amount']) ? $input['expense_amount'] : array();
                if (!empty($expense_items) && is_array($expense_items)) {
                    foreach ($expense_items as $index => $item_name) {
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
                $tags_input = isset($input['tags']) ? $input['tags'] : '';
                if (!empty($tags_input)) {
                    $tag_names = array_map('trim', explode(',', $tags_input));
                    foreach ($tag_names as $tag_name) {
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
                    if (!is_dir($upload_config['path'])) {
                        mkdir($upload_config['path'], 0755, true);
                    }
                    \Upload::process($upload_config);
                    if (\Upload::is_valid()) {
                        \Upload::save();
                        $files = \Upload::get_files();
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

                DB::commit_transaction();
                return array(true, $report_id, 'レポートを投稿しました!');
            } else {
                DB::rollback_transaction();
                return array(false, null, '保存に失敗しました');
            }
        } catch (Exception $e) {
            DB::rollback_transaction();
            return array(false, null, 'エラーが発生しました: ' . $e->getMessage());
        }
    }

    /**
     * レポート更新（DB・ファイル操作をモデルに分離）
     */
    public static function update_report($id, $input, $user_id)
    {
        try {
            DB::start_transaction();
            $report = Report::find($id);
            if (!$report || (int)$report->user_id !== (int)$user_id) {
                DB::rollback_transaction();
                return array(false, '編集権限がありません');
            }
            // 場所情報の処理
            $location_id = null;
            $location_name = isset($input['location']) ? $input['location'] : '';
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
            $report->title = $input['title'];
            $report->body = $input['body'];
            $report->visit_date = $input['visit_date'];
            $report->privacy = isset($input['privacy']) && $input['privacy'] === '0' ? 0 : 1;
            $report->location_id = $location_id;
            if ($report->save()) {
                // 既存の費用を削除して再作成
                DB::delete('expenses')->where('report_id', $id)->execute();
                $expense_items = isset($input['expense_item']) ? $input['expense_item'] : array();
                $expense_amounts = isset($input['expense_amount']) ? $input['expense_amount'] : array();
                if (!empty($expense_items) && is_array($expense_items)) {
                    foreach ($expense_items as $index => $item_name) {
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
                DB::delete('report_tags')->where('report_id', $id)->execute();
                $tags_input = isset($input['tags']) ? $input['tags'] : '';
                if (!empty($tags_input)) {
                    $tag_names = array_map('trim', explode(',', $tags_input));
                    foreach ($tag_names as $tag_name) {
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
                $delete_photo_ids = isset($input['delete_photos']) ? $input['delete_photos'] : array();
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
                    \Upload::process($upload_config);
                    if (\Upload::is_valid()) {
                        \Upload::save();
                        $files = \Upload::get_files();
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
                        $errors = \Upload::get_errors();
                        foreach ($errors as $error) {
                            \Log::error('Photo upload error: ' . $error['errors'][0]['message']);
                        }
                    }
                } else {
                    \Log::info('No files in $_FILES or empty filename');
                }
                DB::commit_transaction();
                return array(true, 'レポートを更新しました!');
            } else {
                DB::rollback_transaction();
                return array(false, '更新に失敗しました');
            }
        } catch (Exception $e) {
            DB::rollback_transaction();
            return array(false, 'エラーが発生しました: ' . $e->getMessage());
        }
    }

    /**
     * 編集フォーム用データ取得（DB操作をモデルに分離）
     */
    public static function get_edit_data($id, $user_id)
    {
        $report = Report::find($id);
        if (!$report) {
            return array('error' => 'レポートが見つかりません');
        }
        if ((int)$report->user_id !== (int)$user_id) {
            return array('error' => '編集権限がありません');
        }
        $data = array();
        $data['report_id'] = (int)$report->id;
        $data['title'] = (string)$report->title;
        $data['body'] = (string)$report->body;
        $data['visit_date'] = (string)$report->visit_date;
        $data['privacy'] = (int)$report->privacy;
        $data['location_id'] = $report->location_id ? (int)$report->location_id : 0;
        // 既存の写真
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
        // 既存の費用
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
        // 既存のタグ
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
        // 場所情報
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
        return $data;
    }
}
