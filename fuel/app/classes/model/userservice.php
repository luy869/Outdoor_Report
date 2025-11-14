<?php
namespace Model;
/**
 * ユーザー関連のDB操作をまとめた静的メソッド群
 * @package    Outdoor_Report
 * @category   Model
 */
class UserService
{
    /**
     * ユーザーIDでユーザー情報を取得
     * @param int $user_id ユーザーID
     * @return array|null ユーザー情報（見つからない場合はnull）
     */
    public static function find_by_id($user_id)
    {
        $result = \DB::select()
            ->from('users')
            ->where('id', $user_id)
            ->execute()
            ->current();
        return $result ? $result : null;
    }

    /**
     * 投稿数などの統計情報を取得
     * @param int $user_id ユーザーID
     * @return array 統計情報（total_reports, public_reports, private_reports）
     */
    public static function get_statistics($user_id)
    {
        $total = \DB::select(\DB::expr('COUNT(*) as count'))
            ->from('reports')
            ->where('user_id', $user_id)
            ->execute()
            ->current();
        $public = \DB::select(\DB::expr('COUNT(*) as count'))
            ->from('reports')
            ->where('user_id', $user_id)
            ->where('privacy', 0)
            ->execute()
            ->current();
        return array(
            'total_reports' => (int)$total['count'],
            'public_reports' => (int)$public['count'],
            'private_reports' => (int)$total['count'] - (int)$public['count']
        );
    }

    /**
     * 投稿一覧を取得
     * @param int $user_id ユーザーID
     * @param bool $is_own_profile 自分のプロフィールかどうか
     * @return array 投稿一覧
     */
    public static function get_reports($user_id, $is_own_profile)
    {
        $query = \DB::select(
                'reports.*',
                \DB::expr('GROUP_CONCAT(DISTINCT tags.name SEPARATOR ", ") as tags'),
                \DB::expr('(SELECT image_url FROM photos WHERE photos.report_id = reports.id LIMIT 1) as first_image')
            )
            ->from('reports')
            ->join('report_tags', 'LEFT')
            ->on('reports.id', '=', 'report_tags.report_id')
            ->join('tags', 'LEFT')
            ->on('report_tags.tag_id', '=', 'tags.id')
            ->where('reports.user_id', $user_id)
            ->group_by('reports.id')
            ->order_by('reports.created_at', 'DESC');
        if (!$is_own_profile) {
            $query->where('reports.privacy', 0);
        }
        $reports_result = $query->execute();
        $reports = array();
        if ($reports_result) {
            foreach ($reports_result as $report) {
                $reports[] = array(
                    'id' => (int)$report['id'],
                    'title' => (string)$report['title'],
                    'body' => (string)$report['body'],
                    'visit_date' => (string)$report['visit_date'],
                    'privacy' => (int)$report['privacy'],
                    'created_at' => (string)$report['created_at'],
                    'tags' => $report['tags'] ? (string)$report['tags'] : '',
                    'first_image' => $report['first_image'] ? (string)$report['first_image'] : ''
                );
            }
        }
        return $reports;
    }

    /**
     * プロフィール更新
     * @param int $user_id ユーザーID
     * @param array $data 更新データ
     * @return int 影響を受けた行数
     */
    public static function update_profile($user_id, $data)
    {
        return \DB::update('users')
            ->set($data)
            ->where('id', $user_id)
            ->execute();
    }

    /**
     * パスワード更新
     * @param int $user_id ユーザーID
     * @param string $hashed_password ハッシュ化済みパスワード
     * @return int 影響を受けた行数
     */
    public static function update_password($user_id, $hashed_password)
    {
        return \DB::update('users')
            ->set(array('password' => $hashed_password))
            ->where('id', $user_id)
            ->execute();
    }
}
