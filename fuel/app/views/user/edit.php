<style>
	.edit-container {
		max-width: 800px;
		margin: 0 auto;
		padding: 32px 20px;
	}

	.edit-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
	}

	.edit-title {
		font-size: 28px;
		font-weight: 700;
		color: #1e293b;
	}

	.btn-cancel {
		color: #64748b;
		text-decoration: none;
		font-weight: 500;
		padding: 8px 16px;
	}

	.edit-card {
		background: white;
		border-radius: 12px;
		padding: 32px;
		box-shadow: 0 2px 8px rgba(0,0,0,0.08);
	}

	.form-section {
		margin-bottom: 32px;
	}

	.form-section:last-child {
		margin-bottom: 0;
	}

	.section-title {
		font-size: 18px;
		font-weight: 700;
		color: #1e293b;
		margin-bottom: 16px;
	}

	.avatar-upload {
		display: flex;
		align-items: center;
		gap: 24px;
		margin-bottom: 24px;
	}

	.current-avatar {
		width: 100px;
		height: 100px;
		border-radius: 50%;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 40px;
		font-weight: 700;
		color: white;
		flex-shrink: 0;
	}

	.current-avatar img {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		object-fit: cover;
	}

	.avatar-info {
		flex: 1;
	}

	.form-label {
		display: block;
		margin-bottom: 8px;
		color: #334155;
		font-weight: 600;
		font-size: 14px;
	}

	.form-input {
		width: 100%;
		padding: 12px 16px;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		font-size: 15px;
		font-family: 'Noto Sans JP', sans-serif;
		transition: all 0.2s;
	}

	.form-input:focus {
		outline: none;
		border-color: #3b82f6;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
	}

	.form-textarea {
		min-height: 120px;
		resize: vertical;
	}

	.form-hint {
		font-size: 13px;
		color: #94a3b8;
		margin-top: 6px;
	}

	.btn-save {
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
	}

	.btn-save:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
	}

	.link-section {
		margin-top: 24px;
		padding-top: 24px;
		border-top: 1px solid #e2e8f0;
	}

	.link-button {
		display: inline-block;
		color: #3b82f6;
		text-decoration: none;
		font-weight: 500;
		font-size: 14px;
	}

	.link-button:hover {
		text-decoration: underline;
	}
</style>

<div class="edit-container">
	<div class="edit-header">
		<h1 class="edit-title">プロフィール編集</h1>
		<a href="/user/profile" class="btn-cancel">キャンセル</a>
	</div>

	<div class="edit-card">
		<form method="post" action="/user/update" enctype="multipart/form-data">
			<!-- アバター画像 -->
			<div class="form-section">
				<h2 class="section-title">プロフィール画像</h2>
				<div class="avatar-upload">
					<div class="current-avatar">
						<?php if ($avatar_url): ?>
							<img src="<?php echo $avatar_url; ?>" alt="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
						<?php else: ?>
							<?php echo strtoupper(mb_substr($username, 0, 1)); ?>
						<?php endif; ?>
					</div>
					<div class="avatar-info">
						<label class="form-label">新しい画像を選択</label>
						<input type="file" name="avatar" accept="image/*" class="form-input">
						<p class="form-hint">JPG, PNG, GIF形式の画像をアップロードできます</p>
					</div>
				</div>
			</div>

			<!-- ユーザー名 -->
			<div class="form-section">
				<label class="form-label">ユーザー名 *</label>
				<input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required maxlength="50">
			</div>

			<!-- メールアドレス（表示のみ） -->
			<div class="form-section">
				<label class="form-label">メールアドレス</label>
				<input type="email" class="form-input" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" disabled style="background: #f1f5f9; cursor: not-allowed;">
				<p class="form-hint">メールアドレスは変更できません</p>
			</div>

			<!-- 自己紹介 -->
			<div class="form-section">
				<label class="form-label">自己紹介</label>
				<textarea name="bio" class="form-input form-textarea" placeholder="あなたについて教えてください..."><?php echo htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'); ?></textarea>
				<p class="form-hint">自己紹介や趣味などを自由に記入してください</p>
			</div>

			<button type="submit" class="btn-save">保存する</button>

			<div class="link-section">
				<a href="/user/change_password" class="link-button">→ パスワードを変更する</a>
			</div>
		</form>
	</div>
</div>
