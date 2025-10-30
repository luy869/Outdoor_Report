<style>
	.password-container {
		max-width: 600px;
		margin: 0 auto;
		padding: 32px 20px;
	}

	.password-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
	}

	.password-title {
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

	.password-card {
		background: white;
		border-radius: 12px;
		padding: 32px;
		box-shadow: 0 2px 8px rgba(0,0,0,0.08);
	}

	.form-section {
		margin-bottom: 24px;
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

	.form-hint {
		font-size: 13px;
		color: #94a3b8;
		margin-top: 6px;
	}

	.security-notice {
		background: #eff6ff;
		border-left: 4px solid #3b82f6;
		padding: 16px;
		border-radius: 8px;
		margin-bottom: 24px;
	}

	.security-notice-title {
		font-weight: 600;
		color: #1e40af;
		margin-bottom: 8px;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.security-notice-text {
		font-size: 14px;
		color: #1e40af;
		line-height: 1.6;
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
</style>

<div class="password-container">
	<div class="password-header">
		<h1 class="password-title">パスワード変更</h1>
		<a href="/user/edit" class="btn-cancel">← 戻る</a>
	</div>

	<div class="password-card">
		<div class="security-notice">
			<div class="security-notice-title">
				🔒 セキュリティのために
			</div>
			<div class="security-notice-text">
				現在のパスワードを入力して本人確認を行います。新しいパスワードは8文字以上で設定してください。
			</div>
		</div>

		<form method="post" action="/user/update_password">
			<div class="form-section">
				<label class="form-label">現在のパスワード *</label>
				<input type="password" name="current_password" class="form-input" required>
				<p class="form-hint">本人確認のため、現在のパスワードを入力してください</p>
			</div>

			<div class="form-section">
				<label class="form-label">新しいパスワード *</label>
				<input type="password" name="new_password" class="form-input" required minlength="8">
				<p class="form-hint">8文字以上のパスワードを入力してください</p>
			</div>

			<div class="form-section">
				<label class="form-label">新しいパスワード（確認） *</label>
				<input type="password" name="new_password_confirm" class="form-input" required minlength="8">
				<p class="form-hint">確認のため、もう一度同じパスワードを入力してください</p>
			</div>

			<button type="submit" class="btn-save">パスワードを変更する</button>
		</form>
	</div>
</div>
