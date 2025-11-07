<link rel="stylesheet" href="/assets/css/report-form.css">

<div class="create-header">
	<a href="/report/view/<?php echo isset($report_id) ? $report_id : ''; ?>" class="btn-cancel">キャンセル</a>
	<h1 class="page-title" style="margin: 0;">レポート編集</h1>
	<button type="submit" form="report-form" class="btn-save">更新</button>
</div>

<div class="form-container">
	<?php echo Form::open(array('action' => 'report/update/' . (isset($report_id) ? $report_id : ''), 'method' => 'post', 'id' => 'report-form', 'enctype' => 'multipart/form-data')); ?>
	<?php echo Form::csrf(); ?>

	<div class="form-section">
		<h2 class="section-title">写真</h2>
		
		<?php if (!empty($photos)): ?>
		<div id="existing-photos" class="photo-preview-container" style="margin-bottom: 16px;">
			<?php foreach ($photos as $photo): ?>
			<div class="photo-preview-item" id="existing-photo-<?php echo $photo['id']; ?>">
				<img src="<?php echo htmlspecialchars($photo['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="既存画像">
				<button type="button" class="photo-preview-remove" onclick="deleteExistingPhoto(<?php echo $photo['id']; ?>)" title="削除">×</button>
			</div>
			<?php endforeach; ?>
		</div>
		<div id="delete-flags-container">
			<?php foreach ($photos as $photo): ?>
			<input type="hidden" name="delete_photos[]" id="delete-photo-<?php echo $photo['id']; ?>" value="" disabled>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
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

	<div class="form-section">
		<h2 class="section-title">費用</h2>
		<div id="expenses-container">
			<?php if (!empty($expenses)): ?>
				<?php foreach ($expenses as $expense): ?>
				<div class="expense-item">
					<input type="text" class="form-input" placeholder="例: ランチ" name="expense_item[]" value="<?php echo htmlspecialchars($expense['item_name'], ENT_QUOTES, 'UTF-8'); ?>">
					<input type="number" class="form-input" placeholder="例: 550" name="expense_amount[]" min="0" value="<?php echo $expense['amount']; ?>">
					<button type="button" onclick="this.parentElement.remove()" class="btn-remove-expense" title="削除">×</button>
				</div>
				<?php endforeach; ?>
			<?php else: ?>
			<div class="expense-item">
				<input type="text" class="form-input" placeholder="例: ランチ" name="expense_item[]">
				<input type="number" class="form-input" placeholder="例: 550" name="expense_amount[]" min="0">
				<button type="button" onclick="this.parentElement.remove()" class="btn-remove-expense" title="削除">×</button>
			</div>
			<?php endif; ?>
		</div>
		<button type="button" class="btn-add-expense" onclick="addExpense()">
			<span>+</span> 費用を追加
		</button>
	</div>

	<div class="form-section" id="tag-section">
		<h2 class="section-title">タグ</h2>
		<div class="form-group">
			<label class="form-label">レポートを分類するためのタグを追加</label>
			<div class="tag-input-container">
				<input type="text" id="tag-input" class="form-input" 
					   placeholder="タグを入力してEnter" 
					   data-bind="value: newTag, valueUpdate: 'input', event: { keypress: addTagOnEnter }">
				<button type="button" class="btn-add-tag" data-bind="click: addTag, enable: canAddTag">
					追加
				</button>
			</div>
			<div class="tags-container" data-bind="foreach: tags">
				<span class="tag">
					<span data-bind="text: $data"></span>
					<button type="button" class="tag-remove" data-bind="click: $parent.removeTag">×</button>
				</span>
			</div>
			<input type="hidden" name="tags" data-bind="value: tagsAsString">
		</div>
	</div>

	<div class="form-section">
		<h2 class="section-title">公開設定</h2>
		<div class="toggle-container">
			<div>
				<div class="toggle-label">このレポートを公開</div>
				<span class="toggle-hint">このレポートを他の人に見えるようにする</span>
			</div>
			<label class="toggle-switch">
				<?php echo Form::checkbox('privacy', '0', Input::post('privacy', isset($privacy) ? ($privacy === 0) : true), array('id' => 'privacy-toggle')); ?>
				<span class="toggle-slider"></span>
			</label>
		</div>
	</div>
	</div>

	<?php echo Form::close(); ?>
</div>

<script src="https://unpkg.com/knockout@3.5.1/build/output/knockout-latest.js"></script>

<script>
const MAX_IMAGES = 4;

function TagViewModel() {
	const self = this;
	
	self.tags = ko.observableArray([]);
	self.newTag = ko.observable('');
	
	self.canAddTag = ko.computed(function() {
		const tag = self.newTag().trim();
		return tag.length > 0 && self.tags().indexOf(tag) === -1;
	});
	
	self.tagsAsString = ko.computed(function() {
		return self.tags().join(',');
	});
	
	self.addTag = function() {
		const tag = self.newTag().trim();
		if (tag && self.tags().indexOf(tag) === -1) {
			self.tags.push(tag);
			self.newTag('');
		}
	};
	
	self.addTagOnEnter = function(data, event) {
		if (event.keyCode === 13) {
			event.preventDefault();
			self.addTag();
			return false;
		}
		return true;
	};
	
	self.removeTag = function(tag) {
		self.tags.remove(tag);
	};
}

const tagViewModel = new TagViewModel();

<?php if (!empty($tags_string)): ?>
const existingTags = <?php echo json_encode(explode(',', $tags_string)); ?>;
existingTags.forEach(function(tag) {
	const trimmedTag = tag.trim();
	if (trimmedTag) {
		tagViewModel.tags.push(trimmedTag);
	}
});
<?php endif; ?>

ko.applyBindings(tagViewModel, document.getElementById('tag-section'));

function countExistingPhotos() {
	return document.querySelectorAll('#existing-photos .photo-preview-item').length;
}

function deleteExistingPhoto(photoId) {
	if (confirm('この画像を削除しますか?')) {
		const photoElement = document.getElementById('existing-photo-' + photoId);
		if (photoElement) {
			photoElement.remove();
		}
		
		const deleteInput = document.getElementById('delete-photo-' + photoId);
		if (deleteInput) {
			deleteInput.value = photoId;
			deleteInput.disabled = false;
		}
		
		updateAddButtonVisibility();
	}
}

let selectedFiles = [];

function previewPhotos(event) {
	const files = Array.from(event.target.files);
	const existingCount = countExistingPhotos();
	const totalCount = existingCount + selectedFiles.length + files.length;
	
	if (totalCount > MAX_IMAGES) {
		alert(`画像は最大${MAX_IMAGES}枚までです。現在${existingCount}枚の既存画像と${selectedFiles.length}枚の新規画像があります。`);
		event.target.value = ''; 
		return;
	}
	
	selectedFiles = selectedFiles.concat(files);
	updatePhotoPreview();
}

function updatePhotoPreview() {
	const container = document.getElementById('photo-preview-container');
	const uploadArea = document.getElementById('upload-area');
	
	container.innerHTML = '';
	
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
	
	try {
		const fileInput = document.getElementById('photo-input');
		if (fileInput && selectedFiles.length > 0) {
			const dt = new DataTransfer();
			selectedFiles.forEach(file => {
				dt.items.add(file);
			});
			fileInput.files = dt.files;
		}
	} catch (error) {
	}
	
	updateAddButtonVisibility();
}

function updateAddButtonVisibility() {
	const existingCount = countExistingPhotos();
	const totalCount = existingCount + selectedFiles.length;
	const addMoreBtn = document.getElementById('add-more-btn');
	
	if (totalCount >= MAX_IMAGES) {
		addMoreBtn.style.display = 'none';
	} else {
		addMoreBtn.style.display = 'inline-flex';
	}
}

function removePhoto(index) {
	selectedFiles.splice(index, 1);
	updatePhotoPreview();
}

function addExpense() {
	const container = document.getElementById('expenses-container');
	const newItem = document.createElement('div');
	newItem.className = 'expense-item';
	newItem.innerHTML = `
		<input type="text" class="form-input" placeholder="例: ランチ" name="expense_item[]">
		<input type="number" class="form-input" placeholder="例: 550" name="expense_amount[]" min="0">
		<button type="button" onclick="this.parentElement.remove()" class="btn-remove-expense" title="削除">×</button>
	`;
	container.appendChild(newItem);
}

document.addEventListener('DOMContentLoaded', function() {
	const form = document.getElementById('report-form');
	if (form) {
		form.addEventListener('submit', function(e) {
			const photoInput = document.getElementById('photo-input');
			if (photoInput) {
				const allFileInputs = form.querySelectorAll('input[type="file"][name="photos[]"]');
				let totalFiles = 0;
				allFileInputs.forEach((input) => {
					totalFiles += input.files.length;
				});
				
				if (totalFiles === 0 && selectedFiles.length > 0) {
					alert('エラー: ファイルが正しく設定されていません。');
					e.preventDefault();
				}
			}
		});
	} else {
	}
});
</script>
