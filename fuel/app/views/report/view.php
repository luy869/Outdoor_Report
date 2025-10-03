<div class="report-detail">
    <div class="report-header">
        <h1><?php echo e($report->title); ?></h1>
        <div class="report-meta">
            <span>👤 <?php echo e($report->username); ?></span>
            <span>📅 訪問日: <?php echo date('Y年m月d日', strtotime($report->visit_date)); ?></span>
            <span>🕐 投稿日時: <?php echo date('Y/m/d H:i', strtotime($report->created_at)); ?></span>
            <span><?php echo $report->privacy ? '🌐 公開' : '🔒 非公開'; ?></span>
        </div>
    </div>

    <div class="report-body">
        <?php echo e($report->body); ?>
    </div>

    <div class="report-actions">
        <a href="/report" class="btn btn-secondary">一覧に戻る</a>
        <?php if ($report->user_id == Session::get('user_id')): ?>
            <a href="/report/edit/<?php echo $report->id; ?>" class="btn btn-primary">編集</a>
            <a href="/report/delete/<?php echo $report->id; ?>" 
               onclick="return confirm('本当に削除しますか?')" 
               class="btn btn-danger">削除</a>
        <?php endif; ?>
    </div>
</div>

<style>
    .report-detail {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .report-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .report-header h1 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 28px;
    }
    .report-meta {
        font-size: 14px;
        color: #999;
    }
    .report-meta span {
        margin-right: 20px;
        display: inline-block;
    }
    .report-body {
        line-height: 1.8;
        color: #444;
        font-size: 16px;
        margin-bottom: 30px;
        min-height: 200px;
    }
    .report-actions {
        display: flex;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        transition: all 0.3s;
    }
    .btn-primary {
        background: #667eea;
        color: white;
    }
    .btn-primary:hover {
        background: #5568d3;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }
    .btn-danger {
        background: #e74c3c;
        color: white;
    }
    .btn-danger:hover {
        background: #c0392b;
    }
</style>
