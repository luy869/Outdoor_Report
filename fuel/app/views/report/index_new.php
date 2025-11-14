<?php echo Asset::css('report-index.css'); ?>

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
			
			<input type="date" 
			       name="date_from" 
			       class="search-input" 
			       placeholder="開始日"
			       value="<?php echo isset($date_from) ? htmlspecialchars($date_from, ENT_QUOTES, 'UTF-8') : ''; ?>">
			
			<input type="date" 
			       name="date_to" 
			       class="search-input" 
			       placeholder="終了日"
			       value="<?php echo isset($date_to) ? htmlspecialchars($date_to, ENT_QUOTES, 'UTF-8') : ''; ?>">
		</div>
		
		<div class="search-buttons">
			<button type="submit" class="search-btn">検索</button>
			<a href="/report/index" class="clear-btn">クリア</a>
		</div>
	</form>
</div>

<!-- CSRFトークン（Ajax用） -->
<?php if (Session::get('user_id')): ?>
	<?php echo Form::csrf(); ?>
<?php endif; ?>

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
					
					<!-- いいねボタン -->
					<div class="report-actions" onclick="event.stopPropagation();">
						<button class="btn-like" 
						        data-report-id="<?php echo $report['id']; ?>"
						        data-liked="<?php echo $report['user_liked'] ? 'true' : 'false'; ?>">
							<span class="like-icon"><?php echo $report['user_liked'] ? '❤️' : '🤍'; ?></span>
							<span class="like-count"><?php echo $report['like_count']; ?></span>
						</button>
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

<script>
// DOMが読み込まれたら実行
document.addEventListener('DOMContentLoaded', function() {
    // 全てのいいねボタンにイベントリスナーを設定
    document.querySelectorAll('.btn-like').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // カード全体のクリックイベントを止める
            const reportId = this.dataset.reportId;
            toggleLike(reportId, this);
        });
    });
});

// いいね機能（Ajax）
function toggleLike(reportId, buttonElement) {
    // CSRFトークンを取得
    const csrfToken = document.querySelector('input[name="fuel_csrf_token"]')?.value || '';
    
    fetch('/report/toggle_like/' + reportId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: 'fuel_csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().catch(() => {
                throw new Error('サーバーエラーが発生しました');
            }).then(data => {
                throw new Error(data.message || 'エラーが発生しました');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const icon = buttonElement.querySelector('.like-icon');
            const count = buttonElement.querySelector('.like-count');
            
            icon.textContent = data.liked ? '❤️' : '🤍';
            count.textContent = data.like_count;
            buttonElement.dataset.liked = data.liked;
            
            // 新しいCSRFトークンでDOMを更新
            if (data.csrf_token) {
                const csrfInput = document.querySelector('input[name="fuel_csrf_token"]');
                if (csrfInput) {
                    csrfInput.value = data.csrf_token;
                }
            }
            
            buttonElement.style.transform = 'scale(1.2)';
            setTimeout(() => {
                buttonElement.style.transform = 'scale(1)';
            }, 200);
        } else {
            alert(data.message || 'エラーが発生しました');
        }
    })
    .catch(error => {
        console.error('Like toggle error:', error);
        alert(error.message || '通信エラーが発生しました');
    });
}
</script>
