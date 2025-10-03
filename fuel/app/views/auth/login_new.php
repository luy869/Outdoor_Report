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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            background: white;
            padding: 48px 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .app-icon svg {
            width: 36px;
            height: 36px;
            fill: white;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #64748b;
            font-size: 14px;
        }
        .alert {
            padding: 14px 16px;
            margin-bottom: 24px;
            border-radius: 8px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 600;
            font-size: 14px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Noto Sans JP', sans-serif;
            transition: all 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .test-info {
            margin-top: 24px;
            padding: 16px;
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.6;
        }
        .test-info strong {
            color: #1e40af;
            display: block;
            margin-bottom: 8px;
        }
        .test-credentials {
            background: white;
            padding: 12px;
            border-radius: 6px;
            margin-top: 8px;
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
