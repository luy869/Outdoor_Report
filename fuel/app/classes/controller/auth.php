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
			Response::redirect('welcome/index');
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
				Response::redirect('welcome/index');
			}
			else
			{
				// ログイン失敗
				Session::set_flash('error', 'メールアドレスまたはパスワードが正しくありません。');
			}
		}

		// ログインフォームを表示
		$this->template->title = 'ログイン';
		$this->template->content = View::forge('auth/login_new');
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
		Response::redirect('auth/login');
	}

}
