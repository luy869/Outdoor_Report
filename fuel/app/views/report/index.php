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
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .report-card h3 {
        margin: 0 0 10px 0;
        color: #667eea;
    }
    .report-card h3 a {
        color: #667eea;
        text-decoration: none;
    }
    .report-card h3 a:hover {
        text-decoration: underline;
    }
    .report-body {
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    .report-meta {
        font-size: 14px;
        color: #999;
        margin-bottom: 15px;
    }
    .report-meta span {
        margin-right: 15px;
    }
    .report-actions {
        display: flex;
        gap: 10px;
    }
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }
    .btn-primary {
        background: #667eea;
        color: white;
    }
    .btn-primary:hover {
        background: #5568d3;
    }
    .btn-small {
        padding: 6px 12px;
        font-size: 13px;
        background: #6c757d;
        color: white;
    }
    .btn-danger {
        background: #e74c3c;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 8px;
        color: #999;
    }
</style>
