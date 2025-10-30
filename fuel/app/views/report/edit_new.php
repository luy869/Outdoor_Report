<style>
	.create-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
	}
	.btn-cancel {
		color: #64748b;
		text-decoration: none;
		font-weight: 500;
		padding: 8px 16px;
	}
	.btn-save {
		background: #3b82f6;
		color: white;
		border: none;
		padding: 10px 24px;
		border-radius: 8px;
		font-size: 15px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
	}
	.btn-save:hover {
		background: #2563eb;
		transform: translateY(-1px);
	}
	.form-container {
		background: white;
		border-radius: 12px;
		padding: 32px;
		box-shadow: 0 1px 3px rgba(0,0,0,0.08);
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
	.photo-upload-area {
		border: 2px dashed #cbd5e1;
		border-radius: 12px;
		padding: 48px;
		text-align: center;
		background: #f8fafc;
		cursor: pointer;
		transition: all 0.2s;
	}
	.photo-upload-area:hover {
		border-color: #3b82f6;
		background: #eff6ff;
	}
	.upload-icon {
		width: 48px;
		height: 48px;
		margin: 0 auto 16px;
		color: #3b82f6;
	}
	.upload-text {
		font-size: 16px;
		font-weight: 600;
		color: #1e293b;
		margin-bottom: 8px;
	}
	.upload-hint {
		font-size: 13px;
		color: #94a3b8;
	}
	.photo-preview-container {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
		gap: 12px;
		margin-top: 16px;
		max-width: 100%;
		overflow: hidden;
	}
	.photo-upload-area.hidden {
		display: none;
	}
	.btn-add-more-photos {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 20px;
		background: #3b82f6;
		color: white;
		border: none;
		border-radius: 8px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
		margin-bottom: 16px;
	}
	.btn-add-more-photos:hover {
		background: #2563eb;
	}
	.photo-preview-item {
		position: relative;
		aspect-ratio: 1;
		border-radius: 8px;
		overflow: hidden;
		background: #f1f5f9;
	}
	.photo-preview-item img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
	.photo-preview-remove {
		position: absolute;
		top: 8px;
		right: 8px;
		background: rgba(239, 68, 68, 0.9);
		color: white;
		border: none;
		width: 28px;
		height: 28px;
		border-radius: 50%;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 18px;
		line-height: 1;
		transition: all 0.2s;
	}
	.photo-preview-remove:hover {
		background: rgba(220, 38, 38, 1);
		transform: scale(1.1);
	}
	.form-group {
		margin-bottom: 20px;
	}
	.form-label {
		display: block;
		font-size: 14px;
		font-weight: 600;
		color: #334155;
		margin-bottom: 8px;
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
	.form-input::placeholder {
		color: #cbd5e1;
	}
	.form-textarea {
		min-height: 200px;
		resize: vertical;
	}
	.form-row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 16px;
	}
	.expense-item {
		display: grid;
		grid-template-columns: 2fr 1fr auto;
		gap: 12px;
		margin-bottom: 12px;
		align-items: center;
	}
	.btn-add-expense {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 16px;
		background: #f1f5f9;
		color: #3b82f6;
		border: none;
		border-radius: 6px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
	}
	.btn-add-expense:hover {
		background: #e2e8f0;
	}
	.toggle-container {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 16px;
		background: #f8fafc;
		border-radius: 8px;
	}
	.toggle-label {
		font-size: 15px;
		font-weight: 600;
		color: #1e293b;
	}
	.toggle-hint {
		font-size: 13px;
		color: #64748b;
		display: block;
		margin-top: 4px;
	}
	.toggle-switch {
		position: relative;
		width: 52px;
		height: 28px;
	}
	.toggle-switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}
	.toggle-slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #cbd5e1;
		transition: 0.3s;
		border-radius: 28px;
	}
	.toggle-slider:before {
		position: absolute;
		content: "";
		height: 20px;
		width: 20px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		transition: 0.3s;
		border-radius: 50%;
	}
	.toggle-switch input:checked + .toggle-slider {
		background-color: #3b82f6;
	}
	.toggle-switch input:checked + .toggle-slider:before {
		transform: translateX(24px);
	}
</style>

<div class="create-header">
	<a href="/report/view/<?php echo isset($report_id) ? $report_id : ''; ?>" class="btn-cancel">キャンセル</a>
	<h1 class="page-title" style="margin: 0;">レポート編集</h1>
	<button type="submit" form="report-form" class="btn-save">更新</button>
</div>

<div class="form-container">
	<?php echo Form::open(array('action' => 'report/update/' . (isset($report_id) ? $report_id : ''), 'method' => 'post', 'id' => 'report-form', 'enctype' => 'multipart/form-data')); ?>

	<!-- 写真セクション -->
	<div class="form-section">
		<h2 class="section-title">写真</h2>
		
		<!-- 既存の画像 -->
		<?php if (!empty($photos)): ?>
		<div id="existing-photos" class="photo-preview-container" style="margin-bottom: 16px;">
			<?php foreach ($photos as $photo): ?>
			<div class="photo-preview-item" id="existing-photo-<?php echo $photo['id']; ?>">
				<img src="<?php echo htmlspecialchars($photo['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="既存画像">
				<button type="button" class="photo-preview-remove" onclick="deleteExistingPhoto(<?php echo $photo['id']; ?>)" title="削除">×</button>
				<input type="hidden" name="delete_photos[]" id="delete-photo-<?php echo $photo['id']; ?>" value="" disabled>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
		<!-- 画像追加ボタン -->
		<button type="button" id="add-more-btn" class="btn-add-more-photos" onclick="document.getElementById('photo-input').click()">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
			</svg>
			写真を追加
		</button>
		
		<div class="photo-upload-area" id="upload-area" style="display: none;" onclick="document.getElementById('photo-input').click()">
			<svg class="upload-icon" viewBox="0 0 24 24" fill="currentColor">
				<path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
			</svg>
			<div class="upload-text">写真を追加</div>
			<div class="upload-hint">あなたの冒険を見せるために写真をアップロード</div>
			<input type="file" id="photo-input" name="photos[]" style="display: none;" accept="image/*" multiple onchange="previewPhotos(event)">
		</div>
		<div id="photo-preview-container" class="photo-preview-container"></div>
	</div>

	<!-- 詳細セクション -->
	<div class="form-section">
		<h2 class="section-title">詳細</h2>
		
		<div class="form-group">
			<label class="form-label">タイトル</label>
			<?php echo Form::input('title', Input::post('title', isset($title) ? $title : ''), array(
				'class' => 'form-input',
				'placeholder' => '例: 山への旅行',
				'maxlength' => '32',
				'required' => 'required'
			)); ?>
		</div>

		<div class="form-row">
			<div class="form-group">
				<label class="form-label">日付</label>
				<?php echo Form::input('visit_date', Input::post('visit_date', isset($visit_date) ? $visit_date : date('Y-m-d')), array(
					'type' => 'date',
					'class' => 'form-input',
					'required' => 'required'
				)); ?>
			</div>
			
			<div class="form-group">
				<label class="form-label">場所</label>
				<?php echo Form::input('location', Input::post('location', isset($location_name) ? $location_name : ''), array(
					'class' => 'form-input',
					'placeholder' => '場所を検索'
				)); ?>
			</div>
		</div>

		<div class="form-group">
			<label class="form-label">説明</label>
			<?php echo Form::textarea('body', Input::post('body', isset($body) ? $body : ''), array(
				'class' => 'form-input form-textarea',
				'placeholder' => 'あなたの冒険を説明してください...',
				'required' => 'required'
			)); ?>
		</div>
	</div>

	<!-- 費用セクション -->
	<div class="form-section">
		<h2 class="section-title">費用</h2>
		<div id="expenses-container">
			<?php if (!empty($expenses)): ?>
				<?php foreach ($expenses as $expense): ?>
				<div class="expense-item">
					<input type="text" class="form-input" placeholder="例: ランチ" name="expense_item[]" value="<?php echo htmlspecialchars($expense['item_name'], ENT_QUOTES, 'UTF-8'); ?>">
					<input type="number" class="form-input" placeholder="例: 550" name="expense_amount[]" min="0" value="<?php echo $expense['amount']; ?>">
					<button type="button" onclick="this.parentElement.remove()" style="background: #ef4444; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;">削除</button>
				</div>
				<?php endforeach; ?>
			<?php else: ?>
			<div class="expense-item">
				<input type="text" class="form-input" placeholder="例: ランチ" name="expense_item[]">
				<input type="number" class="form-input" placeholder="例: 550" name="expense_amount[]" min="0">
			</div>
			<?php endif; ?>
		</div>
		<button type="button" class="btn-add-expense" onclick="addExpense()">
			<span>+</span> 費用を追加
		</button>
	</div>

	<!-- タグセクション -->
	<div class="form-section">
		<h2 class="section-title">タグ</h2>
		<div class="form-group">
			<label class="form-label">レポートを分類するためのタグを追加（カンマ区切り）</label>
			<?php echo Form::input('tags', Input::post('tags', isset($tags_string) ? $tags_string : ''), array(
				'class' => 'form-input',
				'placeholder' => '例: ハイキング, 自然, 旅行'
			)); ?>
		</div>
	</div>

	<!-- 公開設定セクション -->
	<div class="form-section">
		<h2 class="section-title">公開設定</h2>
		<div class="toggle-container">
			<div>
				<div class="toggle-label">このレポートを公開</div>
				<span class="toggle-hint">このレポートを他の人に見えるようにする</span>
			</div>
			<label class="toggle-switch">
				<?php echo Form::checkbox('privacy', '0', Input::post('privacy', isset($privacy) ? ($privacy == 0) : true), array('id' => 'privacy-toggle')); ?>
				<span class="toggle-slider"></span>
			</label>
		</div>
	</div>
	</div>

	<?php echo Form::close(); ?>
</div>

<script>
// 既存画像を削除する関数
function deleteExistingPhoto(photoId) {
	if (confirm('この画像を削除しますか?')) {
		// 画像を非表示にする
		const photoElement = document.getElementById('existing-photo-' + photoId);
		photoElement.style.opacity = '0.5';
		photoElement.querySelector('.photo-preview-remove').style.display = 'none';
		
		// 削除フラグを立てる
		const deleteInput = document.getElementById('delete-photo-' + photoId);
		deleteInput.value = photoId;
		deleteInput.disabled = false;
	}
}

// 選択された写真ファイルを保持
let selectedFiles = [];

// 写真プレビュー機能
function previewPhotos(event) {
	const files = Array.from(event.target.files);
	const container = document.getElementById('photo-preview-container');
	
	// 新しいファイルを既存のリストに追加
	selectedFiles = selectedFiles.concat(files);
	
	// プレビューを更新
	updatePhotoPreview();
}

function updatePhotoPreview() {
	const container = document.getElementById('photo-preview-container');
	const uploadArea = document.getElementById('upload-area');
	const addMoreBtn = document.getElementById('add-more-btn');
	
	container.innerHTML = '';
	
	// 画像がある場合は破線枠を非表示、追加ボタンを表示
	if (selectedFiles.length > 0) {
		uploadArea.classList.add('hidden');
		addMoreBtn.style.display = 'inline-flex';
	} else {
		// 画像がない場合は破線枠を表示、追加ボタンを非表示
		uploadArea.classList.remove('hidden');
		addMoreBtn.style.display = 'none';
	}
	
	selectedFiles.forEach((file, index) => {
		const reader = new FileReader();
		
		reader.onload = function(e) {
			const previewItem = document.createElement('div');
			previewItem.className = 'photo-preview-item';
			previewItem.innerHTML = `
				<img src="${e.target.result}" alt="プレビュー">
				<button type="button" class="photo-preview-remove" onclick="removePhoto(${index})" title="削除">×</button>
			`;
			container.appendChild(previewItem);
		};
		
		reader.readAsDataURL(file);
	});
	
	// フォーム送信用にFileListを更新
	updateFileInput();
}

function removePhoto(index) {
	selectedFiles.splice(index, 1);
	updatePhotoPreview();
}

function updateFileInput() {
	// DataTransferを使って新しいFileListを作成
	const dataTransfer = new DataTransfer();
	selectedFiles.forEach(file => {
		dataTransfer.items.add(file);
	});
	document.getElementById('photo-input').files = dataTransfer.files;
}

function addExpense() {
	const container = document.getElementById('expenses-container');
	const newItem = document.createElement('div');
	newItem.className = 'expense-item';
	newItem.innerHTML = `
		<input type="text" class="form-input" placeholder="費用の項目名" name="expense_item[]">
		<input type="number" class="form-input" placeholder="金額" name="expense_amount[]" min="0">
		<button type="button" onclick="this.parentElement.remove()" style="background: #ef4444; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;">削除</button>
	`;
	container.appendChild(newItem);
}
</script>
