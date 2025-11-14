<?php
/**
 * ユーザーコントローラー
 * 
 * ユーザープロフィールの表示・編集、パ	$data['avatar_url'] = isset($user['avatar_url']) ? (string)$user['avatar_url'] : '';

	// 自分のプロフィールかどうか
	$data['is_own_profile'] = ((int)$user_id === (int)Session::get('user_id'));

	// 統計情報を取得を管理
 * 
 * @package    Outdoor_Report
 * @category   Controller
 */

use Model\User;
use Model\Report;

class Controller_User extends Controller_Template
{
	/**
	 * ユーザープロフィール表示
	 * 
	 * 自分または他のユーザーのプロフィールと投稿レポートを表示
	 */
	public function action_profile($user_id = null)
	{
		// ユーザーIDが指定されていない場合は自分のプロフィールを表示
		if (!$user_id) {
			$user_id = Session::get('user_id');
			if (!$user_id) {
				Session::set_flash('error', 'ログインが必要です');
				Response::redirect('report/index');
			}
		}

		// ユーザー情報を取得
		$user = \Model\UserService::find_by_id($user_id);

		if (!$user) {
			Session::set_flash('error', 'ユーザーが見つかりません');
			Response::redirect('report/index');
		}

		// データを安全に配列化
		$data = array();
		$data['user_id'] = (int)$user['id'];
		$data['username'] = (string)$user['username'];
		$data['email'] = (string)$user['email'];
		$data['created_at'] = (string)$user['created_at'];
		$data['bio'] = isset($user['bio']) ? (string)$user['bio'] : '';
		$data['avatar_url'] = isset($user['avatar_url']) ? (string)$user['avatar_url'] : '';

		// 自分のプロフィールかどうか
		$data['is_own_profile'] = ($user_id == Session::get('user_id'));

		// 統計情報を取得
		$stats = \Model\UserService::get_statistics($user_id);
		$data['total_reports'] = $stats['total_reports'];
		$data['public_reports'] = $stats['public_reports'];
		$data['private_reports'] = $stats['private_reports'];

		// ユーザーの投稿一覧を取得
		$data['reports'] = \Model\UserService::get_reports($user_id, $data['is_own_profile']);

		$this->template->title = $data['username'] . 'のプロフィール';
		$this->template->content = View::forge('user/profile', $data);
	}

	/**
	 * プロフィール編集フォーム
	 */
	public function action_edit()
	{
		$user_id = Session::get('user_id');
		if (!$user_id) {
			Session::set_flash('error', 'ログインが必要です');
			Response::redirect('report/index');
		}

		$user = \Model\UserService::find_by_id($user_id);

		$data = array();
		$data['username'] = (string)$user['username'];
		$data['email'] = (string)$user['email'];
		$data['bio'] = isset($user['bio']) && $user['bio'] ? (string)$user['bio'] : '';
		$data['avatar_url'] = isset($user['avatar_url']) && $user['avatar_url'] ? (string)$user['avatar_url'] : '';

		$this->template->title = 'プロフィール編集';
		$this->template->content = View::forge('user/edit', $data);
	}

	/**
	 * プロフィール更新処理
	 */
	/**
	 * POST専用: プロフィール更新
	 */
	public function post_update()
	{
		$user_id = Session::get('user_id');
		if (!$user_id) {
			Session::set_flash('error', 'ログインが必要です');
			Response::redirect('report/index');
		}

		$username = Input::post('username');
		$bio = Input::post('bio');
		$avatar_url = null;

		// バリデーション
		if (empty($username)) {
			Session::set_flash('error', 'ユーザーネームを入力してください');
			Response::redirect('user/edit');
		}

		// アバター画像のアップロード処理
		if (!empty($_FILES['avatar']['name'])) {
			$config = array(
				'path' => DOCROOT . 'assets/img/avatars',
				'randomize' => true,
				'ext_whitelist' => array('img', 'jpg', 'jpeg', 'png', 'gif'),
			);

			// ディレクトリがなければ作成
			if (!is_dir($config['path'])) {
				mkdir($config['path'], 0755, true);
			}

			Upload::process($config);

			if (Upload::is_valid()) {
				Upload::save();
				$files = Upload::get_files();
				$avatar_url = '/assets/img/avatars/' . $files[0]['saved_as'];
			}
		}

		// データベース更新
		$update_data = array(
			'username' => $username,
			'bio' => $bio,
		);

		if ($avatar_url) {
			$update_data['avatar_url'] = $avatar_url;
		}

		\Model\UserService::update_profile($user_id, $update_data);

		// セッションのユーザー名とアバターも更新
		Session::set('username', $username);
		if ($avatar_url) {
			Session::set('avatar_url', $avatar_url);
		}

		Session::set_flash('success', 'プロフィールを更新しました');
		Response::redirect('user/profile');
	}

	/**
	 * パスワード変更フォーム
	 */
	public function action_change_password()
	{
		if (!Session::get('user_id')) {
			Session::set_flash('error', 'ログインが必要です');
			Response::redirect('report/index');
		}

		$this->template->title = 'パスワード変更';
		$this->template->content = View::forge('user/change_password');
	}

	/**
	 * パスワード変更処理
	 */
	/**
	 * POST専用: パスワード更新
	 */
	public function post_update_password()
	{
		$user_id = Session::get('user_id');
		if (!$user_id) {
			Session::set_flash('error', 'ログインが必要です');
			Response::redirect('report/index');
		}

		$current_password = Input::post('current_password');
		$new_password = Input::post('new_password');
		$new_password_confirm = Input::post('new_password_confirm');

		// ユーザー情報を取得
		$user = \Model\UserService::find_by_id($user_id);

		// 現在のパスワード確認
		if (!password_verify($current_password, $user['password'])) {
			Session::set_flash('error', '現在のパスワードが正しくありません');
			Response::redirect('user/change_password');
		}

		// 新しいパスワードのバリデーション
		if (empty($new_password)) {
			Session::set_flash('error', '新しいパスワードを入力してください');
			Response::redirect('user/change_password');
		}

		if (strlen($new_password) < 8) {
			Session::set_flash('error', 'パスワードは8文字以上で入力してください');
			Response::redirect('user/change_password');
		}

		if ($new_password !== $new_password_confirm) {
			Session::set_flash('error', '新しいパスワードが一致しません');
			Response::redirect('user/change_password');
		}

		// パスワード更新
		$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
		\Model\UserService::update_password($user_id, $hashed_password);

		Session::set_flash('success', 'パスワードを変更しました');
		Response::redirect('user/profile');
	}
}
