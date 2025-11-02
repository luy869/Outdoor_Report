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
    h2 {
        color: #3d3d3d;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #3d3d3d;
        font-size: 15px;
    }
    .required {
        color: #c85a54;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #d4c5b9;
        border-radius: 6px;
        font-size: 15px;
        font-family: inherit;
        background: #ffffff;
        transition: all 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: #5a8f7b;
        box-shadow: 0 0 0 3px rgba(90, 143, 123, 0.1);
    }
    textarea.form-control {
        resize: vertical;
        min-height: 150px;
    }
    small {
        color: #6b6b6b;
        font-size: 13px;
        display: block;
        margin-top: 6px;
    }
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #d4c5b9;
    }
</style>
