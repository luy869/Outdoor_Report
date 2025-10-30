<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - おでかけレポート</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: #f5f3f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            background: white;
            padding: 48px 40px;
            border-radius: 8px;
            border: 2px solid #d4c5b9;
            width: 100%;
            max-width: 420px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .app-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: #8b7355;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #5a8f7b;
        }
        .app-icon svg {
            width: 36px;
            height: 36px;
            fill: white;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #3d3d3d;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #6b6b6b;
            font-size: 14px;
        }
        .alert {
            padding: 14px 16px;
            margin-bottom: 24px;
            border-radius: 6px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fff3e0;
            color: #e65100;
            border: 2px solid #ffa726;
        }
        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 2px solid #66bb6a;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #3d3d3d;
            font-weight: 600;
            font-size: 14px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d4c5b9;
            border-radius: 6px;
            font-size: 15px;
            font-family: 'Noto Sans JP', sans-serif;
            transition: all 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #5a8f7b;
            box-shadow: 0 0 0 3px rgba(90, 143, 123, 0.1);
        }
        button {
            width: 100%;
            padding: 14px;
            background: #5a8f7b;
            color: white;
            border: 2px solid #4a7a66;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        button:hover {
            background: #4a7a66;
            transform: translateY(-2px);
        }
        .test-info {
            margin-top: 24px;
            padding: 16px;
            background-color: #e8f5e9;
            border-left: 4px solid #5a8f7b;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.6;
        }
        .test-info strong {
            color: #2e7d32;
            display: block;
            margin-bottom: 8px;
        }
        .test-credentials {
            background: white;
            padding: 12px;
            border-radius: 6px;
            margin-top: 8px;
            border: 1px solid #d4c5b9;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        .test-credentials div {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="app-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
            </div>
            <h1>おでかけレポート</h1>
            <p class="subtitle">アカウントにログインしてください</p>
        </div>
        
        <?php if (Session::get_flash('error')): ?>
            <div class="alert alert-error">
                <?php echo Session::get_flash('error'); ?>
            </div>
        <?php endif; ?>
        
        <?php if (Session::get_flash('success')): ?>
            <div class="alert alert-success">
                <?php echo Session::get_flash('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php echo Form::open(array('action' => 'auth/login', 'method' => 'post')); ?>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <?php echo Form::input('email', Input::post('email'), array(
                    'type' => 'email',
                    'id' => 'email',
                    'placeholder' => 'your@email.com',
                    'required' => 'required',
                    'autofocus' => 'autofocus'
                )); ?>
            </div>
            
            <div class="form-group">
                <label for="password">パスワード</label>
                <?php echo Form::input('password', '', array(
                    'type' => 'password',
                    'id' => 'password',
                    'placeholder' => '••••••••',
                    'required' => 'required'
                )); ?>
            </div>
            
            <?php echo Form::submit('submit', 'ログイン', array()); ?>
        <?php echo Form::close(); ?>
        
        <div class="test-info">
            <strong>🧪 テスト用アカウント</strong>
            以下の情報でログインできます:
            <div class="test-credentials">
                <div><strong>メール:</strong> test@example.com</div>
                <div><strong>パスワード:</strong> password</div>
            </div>
        </div>
    </div>
</body>
</html>
