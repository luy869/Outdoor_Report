<style>
	.search-container {
		background: white;
		border-radius: 12px;
		padding: 24px;
		margin-bottom: 24px;
		box-shadow: 0 1px 3px rgba(0,0,0,0.08);
	}
	.search-title {
		font-size: 18px;
		font-weight: 700;
		color: #1e293b;
		margin: 0 0 16px 0;
	}
	.search-form {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: 12px;
		margin-bottom: 16px;
	}
	.search-input {
		padding: 10px 14px;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		font-size: 14px;
		transition: all 0.2s;
	}
	.search-input:focus {
		outline: none;
		border-color: #667eea;
		box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
	}
	.search-buttons {
		display: flex;
		gap: 8px;
	}
	.search-btn {
		padding: 10px 24px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		border: none;
		border-radius: 8px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
	}
	.search-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
	}
	.clear-btn {
		padding: 10px 20px;
		background: white;
		color: #64748b;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
	}
	.clear-btn:hover {
		background: #f8fafc;
	}
	.reports-timeline {
		display: flex;
		flex-direction: column;
		gap: 24px;
	}
	.report-card {
		background: white;
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 1px 3px rgba(0,0,0,0.08);
		transition: all 0.2s;
		display: flex;
		gap: 20px;
		padding: 20px;
		cursor: pointer;
	}
	.report-card:hover {
		box-shadow: 0 8px 24px rgba(0,0,0,0.12);
		transform: translateY(-2px);
	}
	.report-image {
		width: 280px;
		height: 180px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 8px;
		flex-shrink: 0;
		object-fit: cover;
	}
	.report-content {
		flex: 1;
		display: flex;
		flex-direction: column;
		gap: 12px;
	}
	.report-date {
		font-size: 13px;
		color: #94a3b8;
		font-weight: 500;
	}
	.report-title {
		font-size: 22px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
	}
	.report-location {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 14px;
		color: #64748b;
	}
	.report-body-preview {
		color: #475569;
		line-height: 1.6;
		font-size: 14px;
	}
	.report-meta {
		display: flex;
		gap: 16px;
		margin-top: auto;
		font-size: 13px;
		color: #94a3b8;
	}
	.report-tags {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
		margin-top: 12px;
	}
	.tag {
		padding: 4px 12px;
		background: #dbeafe;
		color: #3b82f6;
		border-radius: 12px;
		font-size: 12px;
		font-weight: 500;
	}
	.fab-button {
		position: fixed;
		bottom: 32px;
		right: 32px;
		width: 64px;
		height: 64px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 50%;
		border: none;
		color: white;
		font-size: 32px;
		cursor: pointer;
		box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
		transition: all 0.2s;
		display: flex;
		align-items: center;
		justify-content: center;
		text-decoration: none;
	}
	.fab-button:hover {
		transform: scale(1.1);
		box-shadow: 0 12px 32px rgba(102, 126, 234, 0.6);
	}
	.empty-state {
		text-align: center;
		padding: 80px 20px;
		color: #94a3b8;
	}
	.empty-state svg {
		width: 120px;
		height: 120px;
		margin-bottom: 24px;
		opacity: 0.3;
	}
	.empty-state h3 {
		font-size: 20px;
		margin-bottom: 12px;
		color: #64748b;
	}
</style>

<h1 class="page-title">タイムライン</h1>

<!-- 検索フォーム -->
<div class="search-container">
	<h3 class="search-title">🔍 レポートを検索</h3>
	<form action="/report/index" method="get">
		<div class="search-form">
			<input type="text" 
			       name="keyword" 
			       class="search-input" 
			       placeholder="キーワード（タイトル・本文）"
			       value="<?php echo isset($keyword) ? htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') : ''; ?>">
			
			<input type="text" 
			       name="tag" 
			       class="search-input" 
			       placeholder="タグ"
			       value="<?php echo isset($tag) ? htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') : ''; ?>">
			
			<input type="text" 
			       name="location" 
			       class="search-input" 
			       placeholder="場所"
			       value="<?php echo isset($location) ? htmlspecialchars($location, ENT_QUOTES, 'UTF-8') : ''; ?>">
		</div>
		
		<div class="search-buttons">
			<button type="submit" class="search-btn">検索</button>
			<a href="/report/index" class="clear-btn">クリア</a>
		</div>
	</form>
</div>

<?php if (isset($reports) && is_array($reports) && count($reports) > 0): ?>
	<div class="reports-timeline">
		<?php foreach ($reports as $report): ?>
			<div class="report-card" onclick="location.href='/report/view/<?php echo $report['id']; ?>'">
				<?php if (!empty($report['image_url'])): ?>
					<img src="<?php echo htmlspecialchars($report['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
					     alt="レポート画像" 
					     class="report-image">
				<?php else: ?>
					<img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400" 
					     alt="レポート画像" 
					     class="report-image">
				<?php endif; ?>
				<div class="report-content">
					<div class="report-date">
						<?php echo date('Y年m月d日', strtotime($report['visit_date'])); ?>
					</div>
					<h2 class="report-title">
						<?php echo htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8'); ?>
					</h2>
					<?php if (!empty($report['location_name'])): ?>
					<div class="report-location">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
							<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
						</svg>
						<?php echo htmlspecialchars($report['location_name'], ENT_QUOTES, 'UTF-8'); ?>
					</div>
					<?php endif; ?>
					<p class="report-body-preview">
						<?php 
						$body_preview = mb_substr($report['body'], 0, 100);
						echo nl2br(htmlspecialchars($body_preview, ENT_QUOTES, 'UTF-8')); 
						?>
						<?php if (mb_strlen($report['body']) > 100) echo '...'; ?>
					</p>
					<div class="report-meta">
						<span>投稿日: <?php echo date('Y/m/d', strtotime($report['created_at'])); ?></span>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<div class="empty-state">
		<svg viewBox="0 0 24 24" fill="currentColor">
			<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
		</svg>
		<h3>まだレポートがありません</h3>
		<p>最初のレポートを投稿しましょう！</p>
	</div>
<?php endif; ?>

<?php if (Session::get('user_id')): ?>
<a href="/report/create" class="fab-button">+</a>
<?php endif; ?>
