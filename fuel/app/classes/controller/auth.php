<?php

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
	 */
	public function action_login()
	{
		// 既にログイン済みの場合
		if (Session::get('user_id'))
		{
			Response::redirect('report/index');
		}

		// POSTリクエストの場合
		if (Input::method() == 'POST')
		{
			// フォームからの入力を取得
			$email = Input::post('email');
			$password = Input::post('password');

			// データベースからユーザーを検索
			$user = DB::select()
				->from('users')
				->where('email', $email)
				->execute()
				->current();

			// ユーザーが存在し、パスワードが正しいかチェック
			if ($user && password_verify($password, $user['password']))
			{
				// ログイン成功 - セッションにユーザー情報を保存
				Session::set('user_id', $user['id']);
				Session::set('username', $user['username']);
				Session::set('email', $user['email']);
				
				Session::set_flash('success', 'ログインに成功しました!');
				
				// リファラーがあればそこに戻る、なければタイムラインへ
				$referer = Input::referrer();
				if ($referer && strpos($referer, '/auth/') === false) {
					Response::redirect($referer);
				}
				Response::redirect('report/index');
			}
			else
			{
				// ログイン失敗
				Session::set_flash('error', 'メールアドレスまたはパスワードが正しくありません。');
				Response::redirect(Input::referrer('report/index'));
			}
		}

		// GETリクエストの場合はタイムラインへリダイレクト（モーダルで表示するため）
		Response::redirect('report/index');
	}

	/**
	 * ログアウト処理
	 */
	public function action_logout()
	{
		Session::delete('user_id');
		Session::delete('username');
		Session::delete('email');
		Session::set_flash('success', 'ログアウトしました。');
		Response::redirect('report/index');
	}

	public function action_register()
	{
		// POSTリクエストの場合
		if (Input::method() == 'POST')
		{
			// ① 入力取得
			$email = Input::post('email');
			$username = Input::post('username');
			$password = Input::post('password');
			$password_confirm = Input::post('password_confirm');
			
			// ② バリデーション
			if (empty($username)){
				Session::set_flash('error', 'ユーザーネームを入力してください');
				Response::redirect('report/index');
			}
			if (empty($email)){
				Session::set_flash('error', 'メールアドレスを入力してください');
				Response::redirect('report/index');
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
			
			// ③ メールアドレス重複チェック
			$existing_user = DB::select()
				->from('users')
				->where('email', $email)
				->execute()
				->current();
			
			if ($existing_user) {
				Session::set_flash('error', 'このメールアドレスは既に登録されています');
				Response::redirect('report/index');
			}
			
			// ④ パスワードのハッシュ化
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			
			// ⑤ データベースに保存
			$result = DB::insert('users')
				->set(array(
					'username' => $username,
					'email' => $email,
					'password' => $hashed_password,
					'created_at' => date('Y-m-d H:i:s'),
				))
				->execute();
			
			// 新しく作成されたユーザーIDを取得
			$user_id = $result[0];
			
			// ⑥ 自動ログイン
			Session::set('user_id', $user_id);
			Session::set('username', $username);
			Session::set('email', $email);
			
			Session::set_flash('success', '登録が完了しました！');
			Response::redirect('report/index');
		}  // ← この波括弧が重要！if (Input::method() == 'POST') の終了
		
		// GETリクエストの場合
		Response::redirect('report/index');
	}  
}