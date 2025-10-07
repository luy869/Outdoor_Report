<style>
	.view-tabs {
		display: flex;
		gap: 16px;
		margin-bottom: 32px;
		border-bottom: 2px solid #e2e8f0;
	}
	.view-tab {
		padding: 12px 24px;
		background: none;
		border: none;
		color: #64748b;
		font-size: 16px;
		font-weight: 500;
		cursor: pointer;
		border-bottom: 2px solid transparent;
		margin-bottom: -2px;
		transition: all 0.2s;
	}
	.view-tab.active {
		color: #3b82f6;
		border-bottom-color: #3b82f6;
	}
	.view-tab:hover {
		color: #3b82f6;
	}
	.view-content {
		display: none;
	}
	.view-content.active {
		display: block;
	}
	.calendar-container {
		background: white;
		border-radius: 12px;
		padding: 24px;
		box-shadow: 0 1px 3px rgba(0,0,0,0.08);
	}
	.calendar-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 24px;
	}
	.calendar-nav {
		display: flex;
		gap: 12px;
		align-items: center;
	}
	.calendar-nav button {
		width: 36px;
		height: 36px;
		border: none;
		background: #f1f5f9;
		border-radius: 8px;
		color: #64748b;
		cursor: pointer;
		font-size: 18px;
		transition: all 0.2s;
	}
	.calendar-nav button:hover {
		background: #e2e8f0;
		color: #1e293b;
	}
	.calendar-current {
		font-size: 20px;
		font-weight: 700;
		color: #1e293b;
		min-width: 200px;
		text-align: center;
	}
	.calendar-grid {
		display: grid;
		grid-template-columns: repeat(7, 1fr);
		gap: 8px;
	}
	.calendar-day-header {
		text-align: center;
		padding: 12px 8px;
		font-size: 14px;
		font-weight: 600;
		color: #64748b;
	}
	.calendar-day {
		aspect-ratio: 1;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		padding: 8px;
		cursor: pointer;
		transition: all 0.2s;
		position: relative;
		background: white;
	}
	.calendar-day:hover {
		border-color: #3b82f6;
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
	}
	.calendar-day.other-month {
		color: #cbd5e1;
		background: #f8fafc;
	}
	.calendar-day.today {
		background: #eff6ff;
		border-color: #3b82f6;
	}
	.calendar-day-number {
		font-size: 14px;
		font-weight: 600;
		color: #1e293b;
	}
	.calendar-day.other-month .calendar-day-number {
		color: #cbd5e1;
	}
	.calendar-day-reports {
		margin-top: 4px;
		display: flex;
		flex-direction: column;
		gap: 2px;
	}
	.calendar-report-dot {
		width: 100%;
		height: 4px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		border-radius: 2px;
	}
	.calendar-report-count {
		position: absolute;
		top: 4px;
		right: 4px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		font-size: 10px;
		font-weight: 700;
		width: 18px;
		height: 18px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.reports-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
		gap: 24px;
		margin-bottom: 40px;
	}
	.report-card {
		background: white;
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 1px 3px rgba(0,0,0,0.08);
		transition: all 0.2s;
		cursor: pointer;
	}
	.report-card:hover {
		box-shadow: 0 8px 24px rgba(0,0,0,0.12);
		transform: translateY(-2px);
	}
	.report-image {
		width: 100%;
		height: 200px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		object-fit: cover;
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
	.report-date {
		font-size: 13px;
		color: #94a3b8;
		font-weight: 500;
	}
	.privacy-badge {
		padding: 4px 10px;
		border-radius: 12px;
		font-size: 11px;
		font-weight: 600;
		text-transform: uppercase;
	}
	.privacy-public {
		background: #d1fae5;
		color: #065f46;
	}
	.privacy-private {
		background: #fee2e2;
		color: #991b1b;
	}
	.report-title {
		font-size: 18px;
		font-weight: 700;
		color: #1e293b;
		margin-bottom: 8px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.report-location {
		display: flex;
		align-items: center;
		gap: 6px;
		font-size: 14px;
		color: #64748b;
		margin-bottom: 12px;
	}
	.report-body-preview {
		color: #475569;
		line-height: 1.6;
		font-size: 14px;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
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
	.empty-state p {
		margin-bottom: 24px;
	}
	.btn-create {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 24px;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		border: none;
		border-radius: 8px;
		text-decoration: none;
		font-weight: 600;
		transition: all 0.2s;
	}
	.btn-create:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
	}
	.page-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
	}
	.stats-bar {
		display: flex;
		gap: 32px;
		padding: 20px;
		background: white;
		border-radius: 12px;
		box-shadow: 0 1px 3px rgba(0,0,0,0.08);
		margin-bottom: 32px;
	}
	.stat-item {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}
	.stat-label {
		font-size: 13px;
		color: #94a3b8;
		font-weight: 500;
	}
	.stat-value {
		font-size: 28px;
		font-weight: 700;
		color: #1e293b;
	}
</style>

<div class="page-header">
	<h1 class="page-title">マイページ</h1>
	<a href="/report/create" class="btn-create">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
			<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
		</svg>
		新規投稿
	</a>
</div>

<div class="stats-bar">
	<div class="stat-item">
		<span class="stat-label">総投稿数</span>
		<span class="stat-value"><?php echo count($reports); ?></span>
	</div>
	<div class="stat-item">
		<span class="stat-label">公開</span>
		<span class="stat-value"><?php echo count(array_filter($reports, function($r) { return $r['privacy'] == 1; })); ?></span>
	</div>
	<div class="stat-item">
		<span class="stat-label">非公開</span>
		<span class="stat-value"><?php echo count(array_filter($reports, function($r) { return $r['privacy'] == 0; })); ?></span>
	</div>
</div>

<div class="view-tabs">
	<button class="view-tab active" onclick="switchView('grid')">グリッド表示</button>
	<button class="view-tab" onclick="switchView('calendar')">カレンダー表示</button>
</div>

<!-- グリッド表示 -->
<div id="gridView" class="view-content active">
<?php if (isset($reports) && is_array($reports) && count($reports) > 0): ?>
	<div class="reports-grid">
		<?php foreach ($reports as $report): ?>
			<div class="report-card" onclick="location.href='/report/view/<?php echo $report['id']; ?>'">
				<?php if (!empty($report['image_url'])): ?>
					<img src="<?php echo htmlspecialchars($report['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
					     alt="レポート画像" 
					     class="report-image">
				<?php else: ?>
					<div class="report-image"></div>
				<?php endif; ?>
				
				<div class="report-content">
					<div class="report-header">
						<span class="report-date">
							<?php 
								$date = new DateTime($report['visit_date']);
								echo $date->format('Y年n月j日');
							?>
						</span>
						<span class="privacy-badge <?php echo $report['privacy'] == 1 ? 'privacy-public' : 'privacy-private'; ?>">
							<?php echo $report['privacy'] == 1 ? '公開' : '非公開'; ?>
						</span>
					</div>
					
					<h2 class="report-title"><?php echo htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
					
					<?php if (!empty($report['location_name'])): ?>
						<div class="report-location">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
								<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
							</svg>
							<?php echo htmlspecialchars($report['location_name'], ENT_QUOTES, 'UTF-8'); ?>
						</div>
					<?php endif; ?>
					
					<?php if (!empty($report['body'])): ?>
						<div class="report-body-preview">
							<?php echo htmlspecialchars($report['body'], ENT_QUOTES, 'UTF-8'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<div class="empty-state">
		<svg viewBox="0 0 24 24" fill="currentColor">
			<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
		</svg>
		<h3>まだ投稿がありません</h3>
		<p>最初のレポートを作成してみましょう！</p>
		<a href="/report/create" class="btn-create">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
			</svg>
			新規投稿
		</a>
	</div>
<?php endif; ?>
</div>

<!-- カレンダー表示 -->
<div id="calendarView" class="view-content">
	<div class="calendar-container">
		<div class="calendar-header">
			<div class="calendar-nav">
				<button onclick="changeMonth(-1)">‹</button>
				<span class="calendar-current" id="calendarCurrent"></span>
				<button onclick="changeMonth(1)">›</button>
			</div>
			<button onclick="goToToday()" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.2s;">今月</button>
		</div>
		<div class="calendar-grid" id="calendarGrid">
			<!-- カレンダーはJavaScriptで生成 -->
		</div>
	</div>
</div>

<script>
// レポートデータをJavaScript用に準備
const reports = <?php echo json_encode($reports); ?>;
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth();

function switchView(view) {
	// タブの切り替え
	document.querySelectorAll('.view-tab').forEach(tab => tab.classList.remove('active'));
	document.querySelectorAll('.view-content').forEach(content => content.classList.remove('active'));
	
	if (view === 'grid') {
		document.querySelector('.view-tab:first-child').classList.add('active');
		document.getElementById('gridView').classList.add('active');
	} else {
		document.querySelector('.view-tab:last-child').classList.add('active');
		document.getElementById('calendarView').classList.add('active');
		renderCalendar();
	}
}

function changeMonth(delta) {
	currentMonth += delta;
	if (currentMonth > 11) {
		currentMonth = 0;
		currentYear++;
	} else if (currentMonth < 0) {
		currentMonth = 11;
		currentYear--;
	}
	renderCalendar();
}

function goToToday() {
	const today = new Date();
	currentYear = today.getFullYear();
	currentMonth = today.getMonth();
	renderCalendar();
}

function renderCalendar() {
	const firstDay = new Date(currentYear, currentMonth, 1);
	const lastDay = new Date(currentYear, currentMonth + 1, 0);
	const prevLastDay = new Date(currentYear, currentMonth, 0);
	const firstDayOfWeek = firstDay.getDay();
	const lastDate = lastDay.getDate();
	const prevLastDate = prevLastDay.getDate();
	
	// ヘッダーの更新
	document.getElementById('calendarCurrent').textContent = `${currentYear}年${currentMonth + 1}月`;
	
	// カレンダーグリッドの構築
	let html = '';
	
	// 曜日ヘッダー
	const dayNames = ['日', '月', '火', '水', '木', '金', '土'];
	dayNames.forEach(day => {
		html += `<div class="calendar-day-header">${day}</div>`;
	});
	
	// 前月の日付
	for (let i = firstDayOfWeek - 1; i >= 0; i--) {
		const day = prevLastDate - i;
		html += `<div class="calendar-day other-month">
			<div class="calendar-day-number">${day}</div>
		</div>`;
	}
	
	// 今月の日付
	const today = new Date();
	const isCurrentMonth = currentYear === today.getFullYear() && currentMonth === today.getMonth();
	
	for (let day = 1; day <= lastDate; day++) {
		const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
		const dayReports = reports.filter(r => r.visit_date === dateStr);
		const isToday = isCurrentMonth && day === today.getDate();
		
		html += `<div class="calendar-day ${isToday ? 'today' : ''}" onclick="showDayReports('${dateStr}')">
			<div class="calendar-day-number">${day}</div>`;
		
		if (dayReports.length > 0) {
			html += `<div class="calendar-report-count">${dayReports.length}</div>`;
			html += `<div class="calendar-day-reports">`;
			for (let i = 0; i < Math.min(dayReports.length, 3); i++) {
				html += `<div class="calendar-report-dot"></div>`;
			}
			html += `</div>`;
		}
		
		html += `</div>`;
	}
	
	// 次月の日付
	const remainingDays = 42 - (firstDayOfWeek + lastDate); // 6週間分
	for (let day = 1; day <= remainingDays; day++) {
		html += `<div class="calendar-day other-month">
			<div class="calendar-day-number">${day}</div>
		</div>`;
	}
	
	document.getElementById('calendarGrid').innerHTML = html;
}

function showDayReports(dateStr) {
	const dayReports = reports.filter(r => r.visit_date === dateStr);
	
	if (dayReports.length === 0) {
		return;
	}
	
	if (dayReports.length === 1) {
		window.location.href = '/report/view/' + dayReports[0].id;
	} else {
		// 複数ある場合は、最初のレポートに移動（または選択UIを表示することも可能）
		window.location.href = '/report/view/' + dayReports[0].id;
	}
}

// ページ読み込み時にカレンダーを初期化
document.addEventListener('DOMContentLoaded', function() {
	if (document.getElementById('calendarView').classList.contains('active')) {
		renderCalendar();
	}
});
</script>
