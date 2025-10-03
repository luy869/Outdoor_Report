<?php
class Controller_Welcome extends Controller {

    public function action_index() {
        // ログインチェック
        if (!Session::get('user_id')) {
            Response::redirect('auth/login');
        }

        $username = Session::get('username');
        $email = Session::get('email');

        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>ウェルカム</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        h1 { color: #333; }
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎉 ウェルカム!</h1>
        <div class='user-info'>
            <p><strong>ログイン成功!</strong></p>
            <p>ユーザー名: {$username}</p>
            <p>メール: {$email}</p>
        </div>
        <a href='/auth/logout' class='btn'>ログアウト</a>
    </div>
</body>
</html>";
    }
}
