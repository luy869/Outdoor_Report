<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        .test-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f8ff;
            border-left: 4px solid #667eea;
            font-size: 13px;
        }
        .test-info strong {
            color: #667eea;
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
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email" required placeholder="your@example.com">
            </div>
            
            <div class="form-group">
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
