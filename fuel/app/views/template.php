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
			background: #f5f7fa;
			color: #2c3e50;
			line-height: 1.6;
		}
		.app-header {
			background: white;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			position: sticky;
			top: 0;
			z-index: 100;
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
			color: #2c3e50;
			font-size: 20px;
			font-weight: 700;
		}
		.app-logo svg {
			width: 32px;
			height: 32px;
			fill: #3b82f6;
		}
		.app-nav {
			display: flex;
			align-items: center;
			gap: 30px;
		}
		.app-nav a {
			text-decoration: none;
			color: #64748b;
			font-weight: 500;
			transition: color 0.2s;
		}
		.app-nav a:hover {
			color: #3b82f6;
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
			color: #64748b;
			font-size: 14px;
		}
		.user-avatar {
			width: 32px;
			height: 32px;
			border-radius: 50%;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-weight: 600;
		}
		.btn-logout {
			padding: 8px 16px;
			background: #ef4444;
			color: white;
			border: none;
			border-radius: 6px;
			text-decoration: none;
			font-size: 14px;
			font-weight: 500;
			transition: all 0.2s;
			cursor: pointer;
		}
		.btn-logout:hover {
			background: #dc2626;
			transform: translateY(-1px);
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
	</style>
</head>
<body>
	<!-- ヘッダー -->
	<?php if (Session::get('user_id')): ?>
	<header class="app-header">
		<div class="container">
			<a href="/" class="app-logo">
				<svg viewBox="0 0 24 24" fill="currentColor">
					<path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
				</svg>
				おでかけレポート
			</a>
			<nav class="app-nav">
				<a href="/report/index">タイムライン</a>
				<a href="/report/create">新規投稿</a>
			</nav>
			<div class="user-menu">
				<div class="user-info">
					<div class="user-avatar"><?php echo mb_substr(e(Session::get('username', 'ゲスト')), 0, 1); ?></div>
					<span><?php echo e(Session::get('username', 'ゲスト')); ?></span>
				</div>
				<a href="/auth/logout" class="btn-logout">ログアウト</a>
			</div>
		</div>
	</header>
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
</body>
</html>
