<!-- 新規登録モーダル -->
<div id="registerModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" onclick="closeRegisterModal()">×</button>
        
        <div class="modal-header">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
            </div>
            <h2 class="modal-title">新規登録</h2>
            <p class="modal-subtitle">アカウントを作成してください</p>
        </div>

        <form action="/auth/register" method="post">
            <!-- ① メールアドレス入力 -->
            <div class="form-group">
                <label class="form-label" for="register_email">メールアドレス</label>
                <input type="email" name="email" id="register_email" class="form-input" placeholder="your@email.com" required>
            </div>
            
            <!-- ② ユーザー名入力 -->
            <div class="form-group">
                <label class="form-label" for="username">ユーザーネーム</label>
                <input type="text" name="username" id="username" class="form-input" placeholder="ユーザーネーム" required>
            </div>
            
            <!-- ③ パスワード入力 -->
            <div class="form-group">
                <label class="form-label" for="register_password">パスワード</label>
                <input type="password" name="password" id="register_password" class="form-input" placeholder="パスワード" required>
            </div>
            
            <!-- ④ パスワード確認入力 -->
            <div class="form-group">
                <label class="form-label" for="password_confirm">パスワード確認</label>
                <input type="password" name="password" id="password_confirm" class="form-input" placeholder="パスワード再入力" required>
            </div>
            
            <button type="submit" class="btn-submit">新規登録</button>
        </form>
    </div>
</div>