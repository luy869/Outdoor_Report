<h2>📝 外出レポート一覧</h2>

<div style="margin-bottom: 20px;">
    <a href="/report/create" class="btn btn-primary">新規レポート作成</a>
</div>

<?php if (count($reports) > 0): ?>
    <div class="reports-list">
        <?php foreach ($reports as $report): ?>
            <div class="report-card">
                <h3><?php echo Html::anchor('report/view/'.$report->id, $report->title); ?></h3>
                <p class="report-body"><?php echo nl2br(substr($report->body, 0, 150)); ?>...</p>
                <div class="report-meta">
                    <span>👤 <?php echo $report->username; ?></span>
                    <span>📅 <?php echo date('Y年m月d日', strtotime($report->visit_date)); ?></span>
                    <span>🕐 <?php echo date('Y/m/d H:i', strtotime($report->created_at)); ?></span>
                </div>
                <div class="report-actions">
                    <?php if ($report->user_id == Session::get('user_id')): ?>
                        <a href="/report/edit/<?php echo $report->id; ?>" class="btn btn-small">編集</a>
                        <a href="/report/delete/<?php echo $report->id; ?>" 
                           onclick="return confirm('本当に削除しますか?')" 
                           class="btn btn-danger btn-small">削除</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <p>📭 まだレポートがありません</p>
        <p><a href="/report/create">最初のレポートを投稿しましょう!</a></p>
    </div>
<?php endif; ?>

<style>
    .reports-list {
        display: grid;
        gap: 20px;
    }
    .report-card {
        background: #ffffff;
        padding: 24px;
        border-radius: 8px;
        border: 2px solid #d4c5b9;
        transition: all 0.2s;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .report-card h3 {
        margin: 0 0 12px 0;
        color: #3d3d3d;
        font-size: 20px;
        font-weight: 700;
    }
    .report-card h3 a {
        color: #5a8f7b;
        text-decoration: none;
        transition: color 0.2s;
    }
    .report-card h3 a:hover {
        color: #4a7a66;
    }
    .report-body {
        color: #555;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    .report-meta {
        font-size: 13px;
        color: #6b6b6b;
        margin-bottom: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .report-actions {
        display: flex;
        gap: 10px;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #ffffff;
        border-radius: 8px;
        border: 2px solid #d4c5b9;
        color: #6b6b6b;
    }
    .empty-state p {
        margin-bottom: 16px;
    }
    .empty-state a {
        color: #5a8f7b;
        font-weight: 600;
    }
</style>
