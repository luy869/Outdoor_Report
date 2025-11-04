<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
	<style>
		* { margin: 0; padding: 0; box-sizing: border-box; }
		body { 
			font-family: 'Noto Sans JP', sans-serif;
			background: #f5f3f0;
			color: #2c3e50;
			line-height: 1.6;
			min-height: 100vh;
		}
		.app-header {
			background: white;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			position: sticky;
			top: 0;
			z-index: 100;
			border-bottom: 2px solid #d4c5b9;
		}
		.app-header .container {
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 20px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			height: 64px;
		}
		.app-logo {
			display: flex;
			align-items: center;
			gap: 10px;
			text-decoration: none;
			color: #3d3d3d;
			font-size: 20px;
			font-weight: 700;
		}
		.app-logo svg {
			width: 32px;
			height: 32px;
			fill: #5a8f7b;
		}
		.app-nav {
			display: flex;
			align-items: center;
			gap: 30px;
		}
		.app-nav a {
			text-decoration: none;
			color: #6b6b6b;
			font-weight: 500;
			transition: color 0.2s;
		}
		.app-nav a:hover {
			color: #5a8f7b;
		}
		.user-menu {
			display: flex;
			align-items: center;
			gap: 20px;
		}
		.user-info {
			display: flex;
			align-items: center;
			gap: 8px;
			color: #6b6b6b;
			font-size: 14px;
		}
		.user-avatar {
			width: 32px;
			height: 32px;
			border-radius: 50%;
			background: #8b7355;
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-weight: 600;
			border: 2px solid #5a8f7b;
		}
		.btn-logout {
			padding: 8px 16px;
			background: #c85a54;
			color: white;
			border: 2px solid #a84842;
			border-radius: 6px;
			text-decoration: none;
			font-size: 14px;
			font-weight: 600;
			transition: all 0.2s;
			cursor: pointer;
		}
		.btn-logout:hover {
			background: #a84842;
			transform: translateY(-1px);
			box-shadow: 0 2px 8px rgba(200, 90, 84, 0.3);
		}
		.btn-login {
			padding: 8px 20px;
			background: #5a8f7b;
			color: white;
			border: 2px solid #4a7a66;
			border-radius: 6px;
			text-decoration: none;
			font-size: 14px;
			font-weight: 600;
			transition: all 0.2s;
			cursor: pointer;
		}
		.btn-login:hover {
			background: #4a7a66;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(90, 143, 123, 0.3);
		}
		/* モーダルスタイル */
		.modal-overlay {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(61, 61, 61, 0.6);
			z-index: 1000;
			animation: fadeIn 0.2s;
			backdrop-filter: blur(2px);
		}
		.modal-overlay.active {
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.modal-content {
			background: #ffffff;
			padding: 48px 40px;
			border-radius: 8px;
			border: 2px solid #d4c5b9;
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
			width: 90%;
			max-width: 440px;
			position: relative;
			animation: slideUp 0.3s;
		}
		.modal-close {
			position: absolute;
			top: 16px;
			right: 16px;
			width: 36px;
			height: 36px;
			border: 2px solid #d4c5b9;
			background: #ffffff;
			border-radius: 50%;
			color: #6b6b6b;
			font-size: 22px;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.2s;
			font-weight: 600;
		}
		.modal-close:hover {
			background: #f5f3f0;
			color: #3d3d3d;
			border-color: #8b7355;
		}
		.modal-header {
			text-align: center;
			margin-bottom: 32px;
		}
		.modal-icon {
			width: 72px;
			height: 72px;
			margin: 0 auto 20px;
			background: #5a8f7b;
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			border: 3px solid #4a7a66;
		}
		.modal-icon svg {
			width: 40px;
			height: 40px;
			fill: white;
		}
		.modal-title {
			font-size: 26px;
			font-weight: 700;
			color: #3d3d3d;
			margin-bottom: 10px;
		}
		.modal-subtitle {
			color: #6b6b6b;
			font-size: 15px;
		}
		.form-group {
			margin-bottom: 24px;
		}
		.form-label {
			display: block;
			margin-bottom: 8px;
			color: #3d3d3d;
			font-weight: 600;
			font-size: 15px;
		}
		.form-input {
			width: 100%;
			padding: 12px 16px;
			border: 2px solid #d4c5b9;
			border-radius: 6px;
			font-size: 15px;
			font-family: 'Noto Sans JP', sans-serif;
			background: #ffffff;
			transition: all 0.2s;
		}
		.form-input:focus {
			outline: none;
			border-color: #5a8f7b;
			box-shadow: 0 0 0 3px rgba(90, 143, 123, 0.1);
		}
		.btn-submit {
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
		.btn-register {
			width: 100%;
			padding: 14px;
			background: #8b7355;
			color: white;
			border: 2px solid #6b5a44;
			border-radius: 6px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s;
			margin-top: 8px;
		}
		.btn-submit:hover {
			background: #4a7a66;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(90, 143, 123, 0.3);
		}
		.btn-register:hover {
			background: #6b5a44;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
		}
		.test-credentials {
			margin-top: 28px;
			padding: 16px;
			background-color: #f5f3f0;
			border-left: 4px solid #5a8f7b;
			border-radius: 6px;
			font-size: 13px;
			line-height: 1.6;
		}
		.test-credentials strong {
			color: #5a8f7b;
			display: block;
			margin-bottom: 10px;
			font-weight: 600;
		}
		.test-cred-box {
			background: white;
			padding: 12px;
			border-radius: 6px;
			margin-top: 10px;
			font-family: 'Courier New', monospace;
			font-size: 12px;
			border: 1px solid #d4c5b9;
			color: #3d3d3d;
		}
		.test-cred-box div {
			padding: 4px 0;
		}
		@keyframes fadeIn {
			from { opacity: 0; }
			to { opacity: 1; }
		}
		@keyframes slideUp {
			from { 
				opacity: 0;
				transform: translateY(20px);
			}
			to { 
				opacity: 1;
				transform: translateY(0);
			}
		}
		.main-content {
			max-width: 1200px;
			margin: 40px auto;
			padding: 0 20px;
		}
		.page-title {
			font-size: 28px;
			font-weight: 700;
			color: #1e293b;
			margin-bottom: 24px;
		}
		
		/* 統一カードスタイル */
		.card {
			background: #ffffff;
			border-radius: 8px;
			padding: 32px;
			border: 2px solid #d4c5b9;
			margin-bottom: 24px;
		}
		
		/* 統一ボタンスタイル */
		.btn {
			padding: 10px 24px;
			border: none;
			border-radius: 6px;
			cursor: pointer;
			text-decoration: none;
			display: inline-block;
			font-weight: 600;
			transition: all 0.2s;
			font-size: 15px;
		}
		.btn-primary {
			background: #5a8f7b;
			color: white;
			border: 2px solid #4a7a66;
		}
		.btn-primary:hover {
			background: #4a7a66;
			transform: translateY(-1px);
		}
		.btn-secondary {
			background: #6c757d;
			color: white;
			border: 2px solid #5a6268;
		}
		.btn-secondary:hover {
			background: #5a6268;
		}
		.btn-danger {
			background: #c85a54;
			color: white;
			border: 2px solid #a84842;
		}
		.btn-danger:hover {
			background: #a84842;
		}
		.btn-small {
			padding: 6px 12px;
			font-size: 13px;
		}
	</style>
</head>
<body>
	<!-- ヘッダー -->
	<header class="app-header">
		<div class="container">
			<a href="/report/index" class="app-logo">
				<svg viewBox="0 0 24 24" fill="currentColor">
					<path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
				</svg>
				おでかけレポート
			</a>
			<nav class="app-nav">
				<a href="/report/index">タイムライン</a>
				<?php if (Session::get('user_id')): ?>
					<a href="/user/profile">プロフィール</a>
					<a href="/report/create">新規投稿</a>
				<?php endif; ?>
			</nav>
			<div class="user-menu">
				<?php if (Session::get('user_id')): ?>
					<div class="user-info">
						<?php if (Session::get('avatar_url')): ?>
							<img src="<?php echo Session::get('avatar_url'); ?>" alt="Avatar" class="user-avatar" style="border-radius: 50%; object-fit: cover;">
						<?php else: ?>
							<div class="user-avatar"><?php echo mb_substr(Session::get('username', 'ゲスト'), 0, 1); ?></div>
						<?php endif; ?>
						<span><?php echo htmlspecialchars(Session::get('username', 'ゲスト'), ENT_QUOTES, 'UTF-8'); ?></span>
					</div>
					<a href="/auth/logout" class="btn-logout">ログアウト</a>
				<?php else: ?>
					<button onclick="openLoginModal()" class="btn-login">ログイン</button>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<!-- ログインモーダル -->
	<?php if (!Session::get('user_id')): ?>
	<div id="loginModal" class="modal-overlay">
		<div class="modal-content">
			<button class="modal-close" onclick="closeLoginModal()">×</button>
			<div class="modal-header">
				<div class="modal-icon">
					<svg viewBox="0 0 24 24">
						<path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
					</svg>
				</div>
				<h2 class="modal-title">おでかけレポート</h2>
				<p class="modal-subtitle">アカウントにログインしてください</p>
			</div>
			
			<?php if (Session::get_flash('error')): ?>
				<div style="background: #fee2e2; color: #991b1b; padding: 14px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; border: 1px solid #fecaca;">
					<?php echo Session::get_flash('error'); ?>
				</div>
			<?php endif; ?>
			
			<form action="/auth/login" method="post">
				<?php echo Form::csrf(); ?>
				<div class="form-group">
					<label class="form-label" for="email">メールアドレス</label>
					<input type="email" name="email" id="email" class="form-input" placeholder="your@email.com" required autofocus>
				</div>
				
				<div class="form-group">
					<label class="form-label" for="password">パスワード</label>
					<input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
				</div>
				
				<button type="submit" class="btn-submit">ログイン</button>
				<button type="button" onclick=openRegisterModal() class="btn-register">新規登録</button>
			</form>
			
			<div class="test-credentials">
				<strong>🧪 テスト用アカウント（全てパスワード: password）</strong>
				<div class="test-cred-box">
					<div>1. test1@example.com</div>
					<div>2. test2@example.com</div>
					<div>3. test3@example.com</div>
					<div>4. test4@example.com</div>
				</div>
			</div>
		</div>
	</div>
	
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
				<?php echo Form::csrf(); ?>
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
					<input type="password" name="password_confirm" id="password_confirm" class="form-input" placeholder="パスワード再入力" required>
				</div>
				
				<button type="submit" class="btn-submit">新規登録</button>
			</form>
		</div>
	</div>
	<?php endif; ?>

	<main class="main-content">
		<!-- フラッシュメッセージ -->
		<?php if (Session::get_flash('success')): ?>
		<div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745;">
			<strong>✓ 成功</strong><br>
			<?php echo implode('<br>', (array) Session::get_flash('success')); ?>
		</div>
		<?php endif; ?>
		
		<?php if (Session::get_flash('error')): ?>
		<div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #dc3545;">
			<strong>✗ エラー</strong><br>
			<?php echo implode('<br>', (array) Session::get_flash('error')); ?>
		</div>
		<?php endif; ?>

		<!-- ページコンテンツ -->
		<?php echo $content; ?>
	</main>

	<footer style="max-width: 1200px; margin: 60px auto 20px; padding: 20px; text-align: center; color: #94a3b8; font-size: 13px;">
		<p>© 2025 おでかけレポート - Powered by FuelPHP <?php echo e(Fuel::VERSION); ?></p>
	</footer>

	<script>
		function openLoginModal() {
			document.getElementById('loginModal').classList.add('active');
			document.body.style.overflow = 'hidden';
		}

		function closeLoginModal() {
			document.getElementById('loginModal').classList.remove('active');
			document.body.style.overflow = '';
		}

		function openRegisterModal() {
			closeLoginModal();  // ログインモーダルを閉じる
			document.getElementById('registerModal').classList.add('active');  // registernModal → registerModal
			document.body.style.overflow = 'hidden';
		}

		function closeRegisterModal() {
			document.getElementById('registerModal').classList.remove('active');
			document.body.style.overflow = '';
		}

		// モーダル外をクリックしたら閉じる
		document.addEventListener('click', function(e) {
			const loginModal = document.getElementById('loginModal');
			const registerModal = document.getElementById('registerModal');
			
			if (loginModal && e.target === loginModal) {
				closeLoginModal();
			}
			if (registerModal && e.target === registerModal) {
				closeRegisterModal();
			}
		});

		// ESCキーでモーダルを閉じる
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape') {
				closeLoginModal();
				closeRegisterModal();
			}
		});

		// ログインエラーがある場合は自動でモーダルを開く
		<?php if (!Session::get('user_id') && Session::get_flash('error')): ?>
			window.addEventListener('load', function() {
				openLoginModal();
			});
		<?php endif; ?>
	</script>
</body>
</html>
