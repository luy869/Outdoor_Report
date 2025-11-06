<link rel="stylesheet" href="/assets/css/report-view.css">

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
			<button class="btn-icon delete" title="削除" onclick="openDeleteModal()">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
					<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
				</svg>
			</button>
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
		<!-- 複数枚の画像をギャラリー表示 -->
		<div class="photo-gallery <?php echo count($report['photos']) == 1 ? 'single' : ''; ?>">
			<?php foreach ($report['photos'] as $index => $photo): ?>
				<div class="photo-gallery-item" onclick="openImageModal(<?php echo $index; ?>)">
					<img src="<?php echo htmlspecialchars($photo['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
					     alt="レポート画像 <?php echo $index + 1; ?>">
					<?php if ($index == 0 && count($report['photos']) > 1): ?>
						<div class="photo-count">+<?php echo count($report['photos']) - 1; ?> 枚</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
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

<!-- 削除確認モーダル -->
<div id="deleteModal" class="delete-modal" onclick="closeDeleteModal(event)">
	<div class="delete-modal-content" onclick="event.stopPropagation()">
		<div class="delete-modal-icon">
			<svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
				<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
			</svg>
		</div>
		<h2 class="delete-modal-title">レポートを削除しますか?</h2>
		<p class="delete-modal-text">
			この操作は取り消せません。<br>
			レポート、写真、費用などすべてのデータが削除されます。
		</p>
		<div class="delete-modal-actions">
			<button class="btn-modal btn-modal-cancel" onclick="closeDeleteModal()">キャンセル</button>
			<a href="/report/delete/<?php echo $report['id']; ?>" class="btn-modal btn-modal-delete" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">削除する</a>
		</div>
	</div>
</div>

<!-- 画像拡大モーダル -->
<div id="imageModal" class="image-modal" onclick="closeImageModal(event)">
	<button class="image-modal-close" onclick="closeImageModal(event)">×</button>
	
	<?php if (!empty($report['photos']) && count($report['photos']) > 1): ?>
	<button class="image-modal-nav prev" onclick="changeImage(-1, event)">‹</button>
	<button class="image-modal-nav next" onclick="changeImage(1, event)">›</button>
	<div class="image-modal-counter">
		<span id="currentImageIndex">1</span> / <?php echo count($report['photos']); ?>
	</div>
	<?php endif; ?>
	
	<div class="image-modal-content">
		<img id="modalImage" class="image-modal-img" src="" alt="拡大画像">
	</div>
</div>

<script>
// 削除モーダル
function openDeleteModal() {
	document.getElementById('deleteModal').classList.add('active');
	document.body.style.overflow = 'hidden';
}

function closeDeleteModal(event) {
	if (!event || event.target.id === 'deleteModal') {
		document.getElementById('deleteModal').classList.remove('active');
		document.body.style.overflow = '';
	}
}

// ESCキーで削除モーダルを閉じる
document.addEventListener('keydown', function(e) {
	const deleteModal = document.getElementById('deleteModal');
	if (deleteModal.classList.contains('active') && e.key === 'Escape') {
		closeDeleteModal();
	}
});

// 画像データをJavaScriptに渡す
const photos = <?php echo json_encode(array_map(function($photo) {
	return $photo['image_url'];
}, $report['photos'] ?? [])); ?>;

let currentPhotoIndex = 0;

// 画像モーダルを開く
function openImageModal(index) {
	currentPhotoIndex = index;
	updateModalImage();
	document.getElementById('imageModal').classList.add('active');
	document.body.style.overflow = 'hidden'; // スクロール無効化
}

// 画像モーダルを閉じる
function closeImageModal(event) {
	if (event.target.id === 'imageModal' || event.target.classList.contains('image-modal-close')) {
		document.getElementById('imageModal').classList.remove('active');
		document.body.style.overflow = ''; // スクロール有効化
	}
}

// 画像を切り替え
function changeImage(direction, event) {
	event.stopPropagation();
	currentPhotoIndex += direction;
	
	// ループ処理
	if (currentPhotoIndex < 0) {
		currentPhotoIndex = photos.length - 1;
	} else if (currentPhotoIndex >= photos.length) {
		currentPhotoIndex = 0;
	}
	
	updateModalImage();
}

// モーダル画像を更新
function updateModalImage() {
	document.getElementById('modalImage').src = photos[currentPhotoIndex];
	const counter = document.getElementById('currentImageIndex');
	if (counter) {
		counter.textContent = currentPhotoIndex + 1;
	}
}

// キーボード操作
document.addEventListener('keydown', function(e) {
	const modal = document.getElementById('imageModal');
	if (modal.classList.contains('active')) {
		if (e.key === 'Escape') {
			modal.classList.remove('active');
			document.body.style.overflow = '';
		} else if (e.key === 'ArrowLeft') {
			changeImage(-1, e);
		} else if (e.key === 'ArrowRight') {
			changeImage(1, e);
		}
	}
});
</script>
