<?php
/**
 * 認証コントローラー
 * 
 * ユーザー登録、ログイン、ログアウト機能を管理
 * セキュリティ機能：
 * - CSRFトークン検証
 * - パスワードハッシュ化（bcrypt）
 * - ログイン失敗レート制限（5回/15分）
 * - セッション固定攻撃対策（rotate）
 * 
 * @package    Outdoor_Report
 * @category   Controller
 */

class Controller_Auth extends Controller_Template
{
	/**
	 * ログインフォームを表示
	 */
	public function action_index()
	{
		// 既にログイン済みの場合はウェルカムページへリダイレクト
		if (Session::get('user_id'))
		{
			Response::redirect('welcome/index');
		}

		$data["subnav"] = array('index'=> 'active' );
		$this->template->title = 'ログイン';
		$this->template->content = View::forge('auth/index', $data);
	}

	/**
	 * ログイン処理
	 * 
	 * レート制限：5回失敗で15分間ブロック
	 * 成功時：セッション初期化、ユーザー情報保存
	 */
	public function action_login()
	{
		if (Session::get('user_id'))
		{
			Response::redirect('report/index');
		}

		if (Input::method() == 'POST')
		{
			$email = Input::post('email');
			$password = Input::post('password');

			// Rate limiting
			$login_attempts_key = 'login_attempts_' . md5($email);
			$attempts = Session::get($login_attempts_key, 0);
			$lockout_time = Session::get($login_attempts_key . '_time', 0);

			if ($attempts >= 5) {
				$time_passed = time() - $lockout_time;
				if ($time_passed < 900) {
					$minutes_left = ceil((900 - $time_passed) / 60);
					Session::set_flash('error', "ログイン試行回数が上限に達しました。{$minutes_left}分後に再試行してください。");
					Response::redirect('report/index');
				} else {
					Session::delete($login_attempts_key);
					Session::delete($login_attempts_key . '_time');
					$attempts = 0;
				}
			}

			$user = DB::select()
				->from('users')
				->where('email', $email)
				->execute()
				->current();

			if ($user && password_verify($password, $user['password']))
			{
				Session::delete($login_attempts_key);
				Session::delete($login_attempts_key . '_time');

				Session::instance()->rotate();
				Session::set('user_id', $user['id']);
				Session::set('username', $user['username']);
				Session::set('email', $user['email']);
				Session::set('avatar_url', isset($user['avatar_url']) ? $user['avatar_url'] : null);
				
				Session::set_flash('success', 'ログインに成功しました!');
				
				$referer = Input::referrer();
				if ($referer && strpos($referer, '/auth/') === false) {
					Response::redirect($referer);
				}
				Response::redirect('report/index');
			}
			else
			{
				$attempts++;
				Session::set($login_attempts_key, $attempts);
				if ($attempts >= 5) {
					Session::set($login_attempts_key . '_time', time());
				}

				Session::set_flash('error', 'メールアドレスまたはパスワードが正しくありません。');
				Response::redirect(Input::referrer('report/index'));
			}
		}

		Response::redirect('report/index');
	}

	/**
	 * ログアウト処理
	 */
	public function action_logout()
	{
		Session::destroy();
		Session::set_flash('success', 'ログアウトしました。');
		Response::redirect('report/index');
	}

	public function action_register()
	{
		if (Input::method() == 'POST')
		{
			$email = Input::post('email');
			$username = Input::post('username');
			$password = Input::post('password');
			$password_confirm = Input::post('password_confirm');
			
			// Validation
			if (empty($username)){
				Session::set_flash('error', 'ユーザーネームを入力してください');
				Response::redirect('report/index');
			}
			if (empty($email)){
				Session::set_flash('error', 'メールアドレスを入力してください');
				Response::redirect('report/index');
			}
			
			if (Fuel::$env !== Fuel::DEVELOPMENT) {
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					Session::set_flash('error', '有効なメールアドレスを入力してください');
					Response::redirect('report/index');
				}
			}
			
			if (empty($password)){
				Session::set_flash('error', 'パスワードを入力してください');
				Response::redirect('report/index');
			}
			if (strlen($password) < 8) {
				Session::set_flash('error', 'パスワードは8文字以上で入力してください');
				Response::redirect('report/index');
			}
			if ($password != $password_confirm){
				Session::set_flash('error', 'パスワードが一致しません');
				Response::redirect('report/index');
			}
			
			$existing_user = DB::select()
				->from('users')
				->where('email', $email)
				->execute()
				->current();
			
			if ($existing_user) {
				Session::set_flash('error', 'このメールアドレスは既に登録されています');
				Response::redirect('report/index');
			}
			
			$existing_username = DB::select()
				->from('users')
				->where('username', $username)
				->execute()
				->current();
			
			if ($existing_username) {
				Session::set_flash('error', 'このユーザーネームは既に使用されています');
				Response::redirect('report/index');
			}
			
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			
			$result = DB::insert('users')
				->set(array(
					'username' => $username,
					'email' => $email,
					'password' => $hashed_password,
					'created_at' => date('Y-m-d H:i:s'),
				))
				->execute();
			
			$user_id = $result[0];
			
			Session::instance()->rotate();
			Session::set('user_id', $user_id);
			Session::set('username', $username);
			Session::set('email', $email);
			Session::set('avatar_url', null);
			
			Session::set_flash('success', '登録が完了しました！');
			Response::redirect('report/index');
		}
		
		Response::redirect('report/index');
	}  
}