<h2>✏️ 新規レポート作成</h2>

<div id="report-form">
    <?php echo Form::open(array('action' => 'report/store', 'method' => 'post')); ?>
    <?php echo Form::csrf(); ?>

    <div class="form-group">
        <label for="title">タイトル <span class="required">*</span></label>
        <input type="text" name="title" id="title" class="form-control" 
               placeholder="例: 富士山登山レポート" maxlength="32" required
               data-bind="value: title, valueUpdate: 'input'">
        <small>
            <span data-bind="text: titleCharCount"></span> / 32文字
            <span data-bind="visible: titleRemaining() < 10" style="color: #e74c3c; margin-left: 10px;">
                残り<span data-bind="text: titleRemaining"></span>文字
            </span>
        </small>
    </div>

    <div class="form-group">
        <label for="visit_date">訪問日 <span class="required">*</span></label>
        <input type="date" name="visit_date" id="visit_date" class="form-control" 
               value="<?php echo date('Y-m-d'); ?>" required
               data-bind="value: visitDate">
    </div>

    <div class="form-group">
        <label for="body">本文 <span class="required">*</span></label>
        <textarea name="body" id="body" class="form-control" rows="10" 
                  placeholder="レポートの内容を入力してください..." required
                  data-bind="value: body, valueUpdate: 'input'"></textarea>
        <small>
            <span data-bind="text: bodyCharCount"></span>文字
        </small>
    </div>

    <!-- タグ管理機能 -->
    <div class="form-group">
        <label>タグ</label>
        <div class="tag-input-container">
            <input type="text" id="tag-input" class="form-control" 
                   placeholder="タグを入力してEnter" 
                   data-bind="value: newTag, valueUpdate: 'input', event: { keypress: addTagOnEnter }">
            <button type="button" class="btn btn-add-tag" data-bind="click: addTag, enable: canAddTag">
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

    <div class="form-group">
        <label for="privacy">公開設定</label>
        <select name="privacy" id="privacy" class="form-control" data-bind="value: privacy">
            <option value="1">公開</option>
            <option value="0">非公開</option>
        </select>
    </div>

    <!-- プレビュー -->
    <div class="preview-section" data-bind="visible: showPreview">
        <h3>プレビュー</h3>
        <div class="preview-card">
            <h4 data-bind="text: title() || '(タイトル未入力)'"></h4>
            <p class="preview-date" data-bind="text: visitDate"></p>
            <div class="preview-tags" data-bind="foreach: tags">
                <span class="preview-tag" data-bind="text: $data"></span>
            </div>
            <p class="preview-body" data-bind="text: body() || '(本文未入力)'"></p>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="btn btn-preview" data-bind="click: togglePreview">
            <span data-bind="text: showPreview() ? 'プレビューを隠す' : 'プレビューを表示'"></span>
        </button>
        <button type="submit" class="btn btn-primary" data-bind="enable: isValid">
            投稿する
        </button>
        <a href="/report" class="btn btn-secondary">キャンセル</a>
    </div>

    <?php echo Form::close(); ?>
</div>

<!-- Knockout.js -->
<script src="https://unpkg.com/knockout@3.5.1/build/output/knockout-latest.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ViewModel
    function ReportViewModel() {
        var self = this;
        
        // Observable properties
        self.title = ko.observable('');
        self.visitDate = ko.observable('<?php echo date('Y-m-d'); ?>');
        self.body = ko.observable('');
        self.privacy = ko.observable('1');
        self.tags = ko.observableArray([]);
        self.newTag = ko.observable('');
        self.showPreview = ko.observable(false);
        
        // Computed: 文字数カウント
        self.titleCharCount = ko.computed(function() {
            return self.title().length;
        });
        
        self.titleRemaining = ko.computed(function() {
            return 32 - self.title().length;
        });
        
        self.bodyCharCount = ko.computed(function() {
            return self.body().length;
        });
        
        // Computed: タグを追加可能か
        self.canAddTag = ko.computed(function() {
            var tag = self.newTag().trim();
            return tag.length > 0 && self.tags().indexOf(tag) === -1;
        });
        
        // Computed: フォームが有効か
        self.isValid = ko.computed(function() {
            return self.title().trim().length > 0 && 
                   self.body().trim().length > 0 &&
                   self.visitDate().length > 0;
        });
        
        // Computed: タグを文字列に変換（hidden inputに設定）
        self.tagsAsString = ko.computed(function() {
            return self.tags().join(',');
        });
        
        // タグを追加
        self.addTag = function() {
            var tag = self.newTag().trim();
            if (tag && self.tags().indexOf(tag) === -1) {
                self.tags.push(tag);
                self.newTag('');
            }
        };
        
        // Enterキーでタグを追加
        self.addTagOnEnter = function(data, event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                self.addTag();
                return false;
            }
            return true;
        };
        
        // タグを削除
        self.removeTag = function(tag) {
            self.tags.remove(tag);
        };
        
        // プレビュー表示切り替え
        self.togglePreview = function() {
            self.showPreview(!self.showPreview());
        };
    }
    
    // ViewModelをバインド
    var viewModel = new ReportViewModel();
    ko.applyBindings(viewModel, document.getElementById('report-form'));
});
</script>

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
    
    /* タグ関連のスタイル */
    .tag-input-container {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    .tag-input-container .form-control {
        flex: 1;
    }
    .btn-add-tag {
        padding: 12px 20px;
        background: #5a8f7b;
        color: white;
        border: 2px solid #4a7a66;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-add-tag:hover:not(:disabled) {
        background: #4a7a66;
        transform: translateY(-1px);
    }
    .btn-add-tag:disabled {
        background: #d4c5b9;
        border-color: #d4c5b9;
        cursor: not-allowed;
        color: #ffffff;
    }
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    .tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #e8f5f0;
        color: #5a8f7b;
        border-radius: 16px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #d4c5b9;
    }
    .tag-remove {
        background: none;
        border: none;
        color: #5a8f7b;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    .tag-remove:hover {
        color: #c85a54;
    }
    
    /* プレビュー関連のスタイル */
    .preview-section {
        margin-top: 32px;
        padding-top: 32px;
        border-top: 2px solid #d4c5b9;
    }
    .preview-section h3 {
        color: #3d3d3d;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 16px;
    }
    .preview-card {
        background: #f5f3f0;
        padding: 24px;
        border-radius: 8px;
        border: 2px solid #d4c5b9;
    }
    .preview-card h4 {
        color: #3d3d3d;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .preview-date {
        color: #6b6b6b;
        font-size: 14px;
        margin-bottom: 12px;
    }
    .preview-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }
    .preview-tag {
        padding: 4px 12px;
        background: #5a8f7b;
        color: white;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    .preview-body {
        color: #555;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #d4c5b9;
    }
    .btn-preview {
        background: #8b7355;
        color: white;
        border: 2px solid #6b5a44;
    }
    .btn-preview:hover {
        background: #6b5a44;
    }
</style>
