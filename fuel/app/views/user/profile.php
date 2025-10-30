<style>
	body {
		background: #f5f3f0;
	}

	.profile-container {
		max-width: 1200px;
		margin: 0 auto;
		padding: 32px 20px;
	}

	.profile-header {
		background: #ffffff;
		border-radius: 8px;
		padding: 32px;
		margin-bottom: 32px;
		border: 2px solid #d4c5b9;
	}

	.profile-top {
		display: flex;
		align-items: flex-start;
		gap: 32px;
		margin-bottom: 24px;
	}

	.profile-avatar {
		width: 120px;
		height: 120px;
		border-radius: 50%;
		background: #8b7355;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 48px;
		font-weight: 700;
		color: white;
		flex-shrink: 0;
		border: 4px solid #5a8f7b;
	}

	.profile-avatar img {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		object-fit: cover;
	}

	.profile-info {
		flex: 1;
	}

	.profile-username {
		font-size: 28px;
		font-weight: 700;
		color: #3d3d3d;
		margin-bottom: 8px;
	}

	.profile-email {
		color: #6b6b6b;
		font-size: 14px;
		margin-bottom: 12px;
	}

	.profile-bio {
		color: #555;
		line-height: 1.6;
		margin-bottom: 16px;
	}

	.profile-meta {
		display: flex;
		align-items: center;
		gap: 8px;
		color: #6b6b6b;
		font-size: 13px;
	}

	.profile-actions {
		display: flex;
		gap: 12px;
	}

	.btn-edit-profile {
		padding: 10px 24px;
		background: #5a8f7b;
		color: white;
		text-decoration: none;
		border-radius: 6px;
		font-weight: 600;
		transition: all 0.2s;
		display: inline-block;
		border: 2px solid #4a7a66;
	}

	.btn-edit-profile:hover {
		background: #4a7a66;
	}

	.profile-stats {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 24px;
		padding-top: 24px;
		border-top: 2px solid #d4c5b9;
	}

	.stat-item {
		text-align: center;
	}

	.stat-value {
		font-size: 32px;
		font-weight: 700;
		color: #5a8f7b;
		display: block;
		margin-bottom: 4px;
	}

	.stat-label {
		font-size: 13px;
		color: #6b6b6b;
		font-weight: 500;
	}

	.profile-reports {
		margin-top: 32px;
	}

	.section-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20px;
	}

	.section-title {
		font-size: 20px;
		font-weight: 700;
		color: #3d3d3d;
	}

	.reports-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
		gap: 24px;
	}

	.report-card {
		background: white;
		border-radius: 8px;
		overflow: hidden;
		border: 2px solid #d4c5b9;
		transition: all 0.2s;
		text-decoration: none;
		color: inherit;
		display: block;
	}

	.report-card:hover {
		border-color: #5a8f7b;
		transform: translateY(-2px);
	}

	.report-image {
		width: 100%;
		aspect-ratio: 16/9;
		background: #8b7355;
		position: relative;
		overflow: hidden;
	}

	.report-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.report-no-image {
		display: flex;
		align-items: center;
		justify-content: center;
		color: white;
		font-size: 48px;
	}

	.report-content {
		padding: 20px;
	}

	.report-header {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		margin-bottom: 12px;
	}

	.report-title {
		font-size: 18px;
		font-weight: 700;
		color: #3d3d3d;
		margin-bottom: 4px;
	}

	.report-date {
		font-size: 13px;
		color: #6b6b6b;
	}

	.privacy-badge {
		padding: 4px 10px;
		border-radius: 4px;
		font-size: 11px;
		font-weight: 600;
		flex-shrink: 0;
		border: 1px solid;
	}

	.privacy-public {
		background: #e8f5e9;
		color: #2e7d32;
		border-color: #66bb6a;
	}

	.privacy-private {
		background: #fff3e0;
		color: #e65100;
		border-color: #ffa726;
	}

	.report-body {
		color: #555;
		font-size: 14px;
		line-height: 1.6;
		margin-bottom: 12px;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}

	.report-tags {
		display: flex;
		flex-wrap: wrap;
		gap: 6px;
	}

	.tag {
		padding: 4px 10px;
		background: #e8f5e9;
		color: #2e7d32;
		border-radius: 4px;
		font-size: 12px;
		font-weight: 500;
		border: 1px solid #66bb6a;
	}

	.empty-state {
		text-align: center;
		padding: 64px 20px;
		color: #6b6b6b;
	}

	.empty-icon {
		font-size: 64px;
		margin-bottom: 16px;
	}

	.empty-text {
		font-size: 16px;
		font-weight: 600;
		margin-bottom: 8px;
	}

	.empty-hint {
		font-size: 14px;
	}

	@media (max-width: 768px) {
		.profile-top {
			flex-direction: column;
			align-items: center;
			text-align: center;
		}

		.profile-stats {
			grid-template-columns: 1fr;
			gap: 16px;
		}

		.reports-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

<div class="profile-container">
	<div class="profile-header">
		<div class="profile-top">
			<div class="profile-avatar">
				<?php if ($avatar_url): ?>
					<img src="<?php echo $avatar_url; ?>" alt="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
				<?php else: ?>
					<?php echo strtoupper(mb_substr($username, 0, 1)); ?>
				<?php endif; ?>
			</div>

			<div class="profile-info">
				<h1 class="profile-username"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
				<?php if ($is_own_profile): ?>
					<div class="profile-email"><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></div>
				<?php endif; ?>
				
				<?php if ($bio): ?>
					<p class="profile-bio"><?php echo nl2br(htmlspecialchars($bio, ENT_QUOTES, 'UTF-8')); ?></p>
				<?php endif; ?>

				<div class="profile-meta">
					<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
					</svg>
					<span><?php echo date('Y年m月', strtotime($created_at)); ?>から利用</span>
				</div>

				<?php if ($is_own_profile): ?>
				<div class="profile-actions" style="margin-top: 16px;">
					<a href="/user/edit" class="btn-edit-profile">プロフィール編集</a>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="profile-stats">
			<div class="stat-item">
				<span class="stat-value"><?php echo $total_reports; ?></span>
				<span class="stat-label">総投稿数</span>
			</div>
			<div class="stat-item">
				<span class="stat-value"><?php echo $public_reports; ?></span>
				<span class="stat-label">公開投稿</span>
			</div>
			<div class="stat-item">
				<span class="stat-value"><?php echo $private_reports; ?></span>
				<span class="stat-label">非公開投稿</span>
			</div>
		</div>
	</div>

	<div class="profile-reports">
		<div class="section-header">
			<h2 class="section-title">投稿一覧</h2>
		</div>

		<?php if (!empty($reports)): ?>
			<div class="reports-grid">
				<?php foreach ($reports as $report): ?>
					<a href="/report/view/<?php echo $report['id']; ?>" class="report-card">
						<div class="report-image">
							<?php if ($report['first_image']): ?>
								<img src="<?php echo $report['first_image']; ?>" alt="<?php echo htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8'); ?>">
							<?php else: ?>
								<div class="report-no-image">📸</div>
							<?php endif; ?>
						</div>

						<div class="report-content">
						<div class="report-header">
							<div>
								<h3 class="report-title"><?php echo htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
								<div class="report-date"><?php echo date('Y年m月d日', strtotime($report['visit_date'])); ?></div>
							</div>
							<span class="privacy-badge <?php echo $report['privacy'] == 0 ? 'privacy-public' : 'privacy-private'; ?>">
								<?php echo $report['privacy'] == 0 ? '公開' : '非公開'; ?>
							</span>
						</div>							<p class="report-body"><?php echo htmlspecialchars($report['body'], ENT_QUOTES, 'UTF-8'); ?></p>

							<?php if ($report['tags']): ?>
								<div class="report-tags">
									<?php foreach (explode(', ', $report['tags']) as $tag): ?>
										<span class="tag">#<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="empty-state">
				<div class="empty-icon">📝</div>
				<div class="empty-text">まだ投稿がありません</div>
				<?php if ($is_own_profile): ?>
					<div class="empty-hint">最初の冒険を記録してみましょう！</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
