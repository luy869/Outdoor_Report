<style>
	body {
		background: #f5f3f0;
	}
	.create-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
	}
	.btn-cancel {
		color: #6b6b6b;
		text-decoration: none;
		font-weight: 500;
		padding: 8px 16px;
	}
	.btn-save {
		background: #5a8f7b;
		color: white;
		border: 2px solid #4a7a66;
		padding: 10px 24px;
		border-radius: 6px;
		font-size: 15px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
	}
	.btn-save:hover {
		background: #4a7a66;
		transform: translateY(-1px);
	}
	.form-container {
		background: white;
		border-radius: 8px;
		padding: 32px;
		border: 2px solid #d4c5b9;
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
		color: #3d3d3d;
		margin-bottom: 16px;
	}
	.photo-upload-area {
		border: 2px dashed #d4c5b9;
		border-radius: 8px;
		padding: 48px;
		text-align: center;
		background: #f5f3f0;
		cursor: pointer;
		transition: all 0.2s;
	}
	.photo-upload-area:hover {
		border-color: #5a8f7b;
		background: #e8f5e9;
	}
	.upload-icon {
		width: 48px;
		height: 48px;
		margin: 0 auto 16px;
		color: #5a8f7b;
	}
	.upload-text {
		font-size: 16px;
		font-weight: 600;
		color: #3d3d3d;
		margin-bottom: 8px;
	}
	.upload-hint {
		font-size: 13px;
		color: #6b6b6b;
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
		background: #5a8f7b;
		color: white;
		border: 2px solid #4a7a66;
		border-radius: 6px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
		margin-bottom: 16px;
	}
	.btn-add-more-photos:hover {
		background: #4a7a66;
	}
	.photo-preview-item {
		position: relative;
		aspect-ratio: 1;
		border-radius: 6px;
		overflow: hidden;
		background: #f5f3f0;
		border: 2px solid #d4c5b9;
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
		background: rgba(230, 81, 0, 0.9);
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
		background: rgba(216, 67, 21, 1);
		transform: scale(1.1);
	}
	.form-group {
		margin-bottom: 20px;
	}
	.form-label {
		display: block;
		font-size: 14px;
		font-weight: 600;
		color: #3d3d3d;
		margin-bottom: 8px;
	}
	.form-input {
		width: 100%;
		padding: 12px 16px;
		border: 2px solid #d4c5b9;
		border-radius: 6px;
		font-size: 15px;
		font-family: 'Noto Sans JP', sans-serif;
		transition: all 0.2s;
	}
	.form-input:focus {
		outline: none;
		border-color: #5a8f7b;
		box-shadow: 0 0 0 3px rgba(90, 143, 123, 0.1);
	}
	.form-input::placeholder {
		color: #d4c5b9;
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
		background: #f5f3f0;
		color: #5a8f7b;
		border: 2px solid #d4c5b9;
		border-radius: 6px;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.2s;
	}
	.btn-add-expense:hover {
		background: #e8f5e9;
	}
	.toggle-container {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 16px;
		background: #f5f3f0;
		border-radius: 6px;
		border: 1px solid #d4c5b9;
	}
	.toggle-label {
		font-size: 15px;
		font-weight: 600;
		color: #3d3d3d;
	}
	.toggle-hint {
		font-size: 13px;
		color: #6b6b6b;
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
		background-color: #d4c5b9;
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
		background-color: #5a8f7b;
	}
	.toggle-switch input:checked + .toggle-slider:before {
		transform: translateX(24px);
	}
</style>

<div class="create-header">
	<a href="/report" class="btn-cancel">キャンセル</a>
	<h1 class="page-title" style="margin: 0;">新規レポート</h1>
	<button type="submit" form="report-form" class="btn-save">保存</button>
</div>

<div class="form-container">
	<?php echo Form::open(array('action' => 'report/store', 'method' => 'post', 'id' => 'report-form', 'enctype' => 'multipart/form-data')); ?>

	<!-- 写真セクション -->
	<div class="form-section">
		<h2 class="section-title">写真</h2>
		
		<!-- 画像追加ボタン（プレビューがある場合に表示） -->
		<button type="button" id="add-more-btn" class="btn-add-more-photos" style="display: none;" onclick="document.getElementById('photo-input').click()">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
			</svg>
			写真を追加
		</button>
		
		<div class="photo-upload-area" id="upload-area" onclick="document.getElementById('photo-input').click()">
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
			<?php echo Form::input('title', Input::post('title'), array(
				'class' => 'form-input',
				'placeholder' => '例: 山への旅行',
				'maxlength' => '32',
				'required' => 'required'
			)); ?>
		</div>

		<div class="form-row">
			<div class="form-group">
				<label class="form-label">日付</label>
				<?php echo Form::input('visit_date', Input::post('visit_date', date('Y-m-d')), array(
					'type' => 'date',
					'class' => 'form-input',
					'required' => 'required'
				)); ?>
			</div>
			
			<div class="form-group">
				<label class="form-label">場所</label>
				<?php echo Form::input('location', Input::post('location'), array(
					'class' => 'form-input',
					'placeholder' => '場所を検索'
				)); ?>
			</div>
		</div>

		<div class="form-group">
			<label class="form-label">説明</label>
			<?php echo Form::textarea('body', Input::post('body'), array(
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
			<div class="expense-item">
				<input type="text" class="form-input" placeholder="例: ランチ" name="expense_item[]">
				<input type="number" class="form-input" placeholder="例: 550" name="expense_amount[]" min="0">
			</div>
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
			<?php echo Form::input('tags', Input::post('tags'), array(
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
				<?php echo Form::checkbox('privacy', '0', Input::post('privacy', true), array('id' => 'privacy-toggle')); ?>
				<span class="toggle-slider"></span>
			</label>
		</div>
	</div>

	<?php echo Form::close(); ?>
</div>

<script>
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
