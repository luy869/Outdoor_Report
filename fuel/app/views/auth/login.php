<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <style>
        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f3f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #ffffff;
            padding: 48px 40px;
            border-radius: 8px;
            border: 2px solid #d4c5b9;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        h1 {
            text-align: center;
            color: #3d3d3d;
            margin-bottom: 32px;
            font-size: 26px;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 24px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #3d3d3d;
            font-weight: 600;
            font-size: 15px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d4c5b9;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
            background: #ffffff;
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
        }
        button:hover {
            background: #4a7a66;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(90, 143, 123, 0.3);
        }
        .alert {
            padding: 14px 16px;
            margin-bottom: 24px;
            border-radius: 6px;
            font-size: 14px;
            border: 2px solid;
        }
        .alert-error {
            background-color: #fef2f2;
            color: #c85a54;
            border-color: #f8c9c5;
        }
        .alert-success {
            background-color: #f0fdf4;
            color: #5a8f7b;
            border-color: #a7d8c0;
        }
        .test-info {
            margin-top: 24px;
            padding: 16px;
            background-color: #f5f3f0;
            border-left: 4px solid #5a8f7b;
            font-size: 13px;
            border-radius: 6px;
            line-height: 1.6;
        }
        .test-info strong {
            color: #5a8f7b;
            display: block;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔐 ログイン</h1>
        
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
    
    <form action="/auth/login" method="post">
        <?php echo Form::csrf(); ?>
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" required placeholder="your@example.com">
        </div>            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password" required placeholder="パスワードを入力">
            </div>
            
            <button type="submit">ログイン</button>
        </form>
        
        <div class="test-info">
            <strong>テスト用アカウント:</strong><br>
            メール: test@example.com<br>
            パスワード: password
        </div>
    </div>
</body>
</html>
