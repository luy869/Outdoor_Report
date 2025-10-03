<style>
	.detail-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
	}
	.btn-back {
		display: flex;
		align-items: center;
		gap: 8px;
		color: #64748b;
		text-decoration: none;
		font-weight: 500;
		padding: 8px 16px;
		transition: color 0.2s;
	}
	.btn-back:hover {
		color: #3b82f6;
	}
	.detail-actions {
		display: flex;
		gap: 12px;
	}
	.btn-icon {
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 8px;
		border: 1px solid #e2e8f0;
		background: white;
		color: #64748b;
		text-decoration: none;
		transition: all 0.2s;
	}
	.btn-icon:hover {
		background: #f8fafc;
		color: #3b82f6;
		border-color: #3b82f6;
	}
	.btn-icon.delete:hover {
		background: #fee;
		color: #dc2626;
		border-color: #dc2626;
	}
	.detail-container {
		background: white;
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 1px 3px rgba(0,0,0,0.08);
	}
	.detail-hero-image {
		width: 100%;
		height: 400px;
		object-fit: cover;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	}
	.detail-content {
		padding: 40px;
	}
	.detail-title {
		font-size: 32px;
		font-weight: 700;
		color: #1e293b;
		margin-bottom: 24px;
	}
	.detail-meta-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 16px;
		margin-bottom: 32px;
	}
	.meta-item {
		display: flex;
		align-items: flex-start;
		gap: 12px;
		padding: 16px;
		background: #f8fafc;
		border-radius: 8px;
	}
	.meta-icon {
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		background: #dbeafe;
		border-radius: 8px;
		color: #3b82f6;
		flex-shrink: 0;
	}
	.meta-content {
		flex: 1;
	}
	.meta-label {
		font-size: 12px;
		color: #94a3b8;
		font-weight: 600;
		text-transform: uppercase;
		margin-bottom: 4px;
	}
	.meta-value {
		font-size: 16px;
		color: #1e293b;
		font-weight: 600;
	}
	.detail-section {
		margin-bottom: 32px;
	}
	.section-title {
		font-size: 20px;
		font-weight: 700;
		color: #1e293b;
		margin-bottom: 16px;
	}
	.detail-description {
		color: #475569;
		line-height: 1.8;
		font-size: 16px;
		white-space: pre-wrap;
	}
	.detail-map {
		width: 100%;
		height: 200px;
		background: #f1f5f9;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #94a3b8;
	}
	.expenses-list {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}
	.expense-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 16px;
		background: #f8fafc;
		border-radius: 8px;
	}
	.expense-icon-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
	}
	.expense-icon {
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		background: #dbeafe;
		border-radius: 8px;
		color: #3b82f6;
	}
	.expense-name {
		font-size: 15px;
		font-weight: 600;
		color: #1e293b;
	}
	.expense-amount {
		font-size: 18px;
		font-weight: 700;
		color: #1e293b;
	}
	.tags-list {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}
	.tag {
		padding: 6px 16px;
		background: #dbeafe;
		color: #3b82f6;
		border-radius: 16px;
		font-size: 14px;
		font-weight: 600;
	}
</style>

<div class="detail-header">
	<a href="/report/index" class="btn-back">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
			<path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
		</svg>
		戻る
	</a>
	<h1 class="page-title" style="margin: 0;"><?php echo htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
	<div class="detail-actions">
		<?php if ($report['user_id'] == Session::get('user_id')): ?>
			<a href="/report/edit/<?php echo $report['id']; ?>" class="btn-icon" title="編集">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
					<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
				</svg>
			</a>
			<a href="/report/delete/<?php echo $report['id']; ?>" class="btn-icon delete" title="削除" onclick="return confirm('本当に削除しますか?')">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
					<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
				</svg>
			</a>
		<?php endif; ?>
		<a href="#" class="btn-icon" title="共有">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
			</svg>
		</a>
	</div>
</div>

<div class="detail-container">
	<?php if (!empty($report['photos']) && count($report['photos']) > 0): ?>
		<img src="<?php echo htmlspecialchars($report['photos'][0]['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
		     alt="レポート画像" 
		     class="detail-hero-image">
	<?php else: ?>
		<img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200" 
		     alt="レポート画像" 
		     class="detail-hero-image">
	<?php endif; ?>
	
	<div class="detail-content">
		<h1 class="detail-title"><?php echo htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
		
		<div class="detail-meta-grid">
			<div class="meta-item">
				<div class="meta-icon">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
						<path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
					</svg>
				</div>
				<div class="meta-content">
					<div class="meta-label">訪問日</div>
					<div class="meta-value"><?php echo date('Y年m月d日', strtotime($report['visit_date'])); ?></div>
				</div>
			</div>
			
			<?php if (!empty($report['location_name'])): ?>
			<div class="meta-item">
				<div class="meta-icon">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
						<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
					</svg>
				</div>
				<div class="meta-content">
					<div class="meta-label">場所</div>
					<div class="meta-value"><?php echo htmlspecialchars($report['location_name'], ENT_QUOTES, 'UTF-8'); ?></div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		
		<div class="detail-section">
			<h2 class="section-title">レポート</h2>
			<p class="detail-description"><?php echo nl2br(htmlspecialchars($report['body'], ENT_QUOTES, 'UTF-8')); ?></p>
		</div>
		
		<?php if (!empty($report['expenses']) && count($report['expenses']) > 0): ?>
		<div class="detail-section">
			<h2 class="section-title">かかった費用</h2>
			<div class="expenses-list">
				<?php foreach ($report['expenses'] as $expense): ?>
				<div class="expense-item">
					<div class="expense-icon-wrapper">
						<div class="expense-icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
								<path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
							</svg>
						</div>
						<span class="expense-name"><?php echo htmlspecialchars($expense['item_name'], ENT_QUOTES, 'UTF-8'); ?></span>
					</div>
					<span class="expense-amount">¥<?php echo number_format($expense['amount']); ?></span>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php if (!empty($report['tags']) && count($report['tags']) > 0): ?>
		<div class="detail-section">
			<h2 class="section-title">タグ</h2>
			<div class="tags-list">
				<?php foreach ($report['tags'] as $tag): ?>
				<span class="tag">#<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
