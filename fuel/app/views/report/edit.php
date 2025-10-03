<h2>✏️ レポート編集</h2>

<?php echo Form::open(array('action' => 'report/update/'.$report->id, 'method' => 'post')); ?>

<div class="form-group">
    <label for="title">タイトル <span class="required">*</span></label>
    <?php echo Form::input('title', Input::post('title', $report->title), array(
        'id' => 'title',
        'class' => 'form-control',
        'placeholder' => '例: 富士山登山レポート',
        'maxlength' => '32',
        'required' => 'required'
    )); ?>
    <small>最大32文字</small>
</div>

<div class="form-group">
    <label for="visit_date">訪問日 <span class="required">*</span></label>
    <?php echo Form::input('visit_date', Input::post('visit_date', $report->visit_date), array(
        'type' => 'date',
        'id' => 'visit_date',
        'class' => 'form-control',
        'required' => 'required'
    )); ?>
</div>

<div class="form-group">
    <label for="body">本文 <span class="required">*</span></label>
    <?php echo Form::textarea('body', Input::post('body', $report->body), array(
        'id' => 'body',
        'class' => 'form-control',
        'rows' => '10',
        'placeholder' => 'レポートの内容を入力してください...',
        'required' => 'required'
    )); ?>
</div>

<div class="form-group">
    <label for="privacy">公開設定</label>
    <?php echo Form::select('privacy', Input::post('privacy', $report->privacy), array(
        1 => '公開',
        0 => '非公開'
    ), array(
        'id' => 'privacy',
        'class' => 'form-control'
    )); ?>
</div>

<div class="form-actions">
    <?php echo Form::submit('submit', '更新する', array('class' => 'btn btn-primary')); ?>
    <a href="/report/view/<?php echo $report->id; ?>" class="btn btn-secondary">キャンセル</a>
</div>

<?php echo Form::close(); ?>

<style>
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }
    .required {
        color: #e74c3c;
    }
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
    }
    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    textarea.form-control {
        resize: vertical;
    }
    small {
        color: #999;
        font-size: 12px;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    .btn {
        padding: 12px 24px;
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
</style>
