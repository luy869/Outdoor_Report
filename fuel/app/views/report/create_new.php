<h2>✏️ 新規レポート作成</h2>

<div id="report-form">
    <?php echo Form::open(array('action' => 'report/store', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
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
        <label for="location">場所</label>
        <input type="text" name="location" id="location" class="form-control" 
               placeholder="例: 富士山、高尾山">
        <small class="form-text">訪問した場所を入力してください</small>
    </div>

    <div class="form-group">
        <label for="photos">画像アップロード（最大4枚）</label>
        <input type="file" id="photos-input" class="form-control" accept="image/*" style="display: none;">
        <button type="button" class="btn-add-photo" onclick="document.getElementById('photos-input').click()">
            + 画像を追加
        </button>
        <small class="form-text">1枚ずつ追加できます（JPEG, PNG, GIF）</small>
        <div id="image-preview-container" class="image-preview-container"></div>
        <!-- 実際にサーバーに送信する隠しinput -->
        <input type="file" name="photos[]" id="photos-hidden" multiple accept="image/*" style="display: none;">
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
        <label>費用</label>
        <div id="expenses-container">
            <div class="expense-item">
                <input type="text" class="form-control" placeholder="例: ランチ" name="expense_item[]">
                <input type="number" class="form-control" placeholder="例: 550" name="expense_amount[]" min="0">
                <button type="button" onclick="this.parentElement.remove()" class="btn-remove-expense" title="削除">×</button>
            </div>
        </div>
        <button type="button" class="btn-add-expense" onclick="addExpense()">
            <span>+</span> 費用を追加
        </button>
    </div>

    <div class="form-group">
        <label for="privacy">公開設定</label>
        <select name="privacy" id="privacy" class="form-control" data-bind="value: privacy">
            <option value="0">公開</option>
            <option value="1">非公開</option>
        </select>
    </div>

    <div class="preview-section" data-bind="visible: showPreview">
        <h3>プレビュー</h3>
        <div class="preview-card">
            <!-- 画像ギャラリー -->
            <div class="preview-gallery" id="preview-gallery"></div>
            
            <!-- コンテンツ部分 -->
            <div class="preview-content">
                <h4 class="preview-title" data-bind="text: title() || '(タイトル未入力)'"></h4>
                
                <div class="preview-meta">
                    <div class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                        </svg>
                        <span data-bind="text: visitDate"></span>
                    </div>
                </div>
                
                <div class="preview-tags" data-bind="foreach: tags">
                    <span class="preview-tag" data-bind="text: '#' + $data"></span>
                </div>
                
                <p class="preview-body" data-bind="text: body() || '(本文未入力)'"></p>
            </div>
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

<script src="https://unpkg.com/knockout@3.5.1/build/output/knockout-latest.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 画像管理用の配列
    var selectedFiles = [];
    var MAX_IMAGES = 4;
    
    function ReportViewModel() {
        var self = this;
        
        self.title = ko.observable('');
        self.visitDate = ko.observable('<?php echo date('Y-m-d'); ?>');
        self.body = ko.observable('');
        self.privacy = ko.observable('0'); // 0 = 公開がデフォルト
        self.tags = ko.observableArray([]);
        self.newTag = ko.observable('');
        self.showPreview = ko.observable(false);
        
        self.titleCharCount = ko.computed(function() {
            return self.title().length;
        });
        
        self.titleRemaining = ko.computed(function() {
            return 32 - self.title().length;
        });
        
        self.bodyCharCount = ko.computed(function() {
            return self.body().length;
        });
        
        self.canAddTag = ko.computed(function() {
            var tag = self.newTag().trim();
            return tag.length > 0 && self.tags().indexOf(tag) === -1;
        });
        
        self.isValid = ko.computed(function() {
            return self.title().trim().length > 0 && 
                   self.body().trim().length > 0 &&
                   self.visitDate().length > 0;
        });
        
        self.tagsAsString = ko.computed(function() {
            return self.tags().join(',');
        });
        
        self.addTag = function() {
            var tag = self.newTag().trim();
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
        
        self.togglePreview = function() {
            self.showPreview(!self.showPreview());
            if (self.showPreview()) {
                updatePreviewGallery();
            }
        };
    }
    
    var viewModel = new ReportViewModel();
    ko.applyBindings(viewModel, document.getElementById('report-form'));
    
    // 画像追加処理
    document.getElementById('photos-input').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        
        if (!file.type.match('image.*')) {
            alert('画像ファイルを選択してください');
            this.value = '';
            return;
        }
        
        if (selectedFiles.length >= MAX_IMAGES) {
            alert('画像は最大' + MAX_IMAGES + '枚までです');
            this.value = '';
            return;
        }
        
        selectedFiles.push(file);
        updateImagePreview();
        updateHiddenInput();
        this.value = ''; // リセット
    });
    
    function updateImagePreview() {
        var container = document.getElementById('image-preview-container');
        container.innerHTML = '';
        
        selectedFiles.forEach(function(file, index) {
            var previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            
            var img = document.createElement('img');
            img.className = 'preview-image';
            
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-remove-image';
            removeBtn.innerHTML = '×';
            removeBtn.onclick = function() {
                removeImage(index);
            };
            
            var fileName = document.createElement('div');
            fileName.className = 'preview-filename';
            fileName.textContent = file.name;
            
            previewItem.appendChild(img);
            previewItem.appendChild(removeBtn);
            previewItem.appendChild(fileName);
            container.appendChild(previewItem);
        });
        
        // プレビューが表示されていれば更新
        if (viewModel.showPreview()) {
            updatePreviewGallery();
        }
    }
    
    function updatePreviewGallery() {
        var gallery = document.getElementById('preview-gallery');
        if (!gallery) return;
        
        gallery.innerHTML = '';
        
        if (selectedFiles.length === 0) {
            gallery.style.display = 'none';
            return;
        }
        
        gallery.style.display = 'grid';
        gallery.className = 'preview-gallery ' + (selectedFiles.length === 1 ? 'single' : '');
        
        selectedFiles.forEach(function(file, index) {
            var item = document.createElement('div');
            item.className = 'preview-gallery-item';
            
            var img = document.createElement('img');
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            if (index === 0 && selectedFiles.length > 1) {
                var count = document.createElement('div');
                count.className = 'preview-photo-count';
                count.textContent = '+' + (selectedFiles.length - 1) + ' 枚';
                item.appendChild(count);
            }
            
            item.appendChild(img);
            gallery.appendChild(item);
        });
    }
    
    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updateImagePreview();
        updateHiddenInput();
    }
    
    function updateHiddenInput() {
        var hiddenInput = document.getElementById('photos-hidden');
        var dataTransfer = new DataTransfer();
        
        selectedFiles.forEach(function(file) {
            dataTransfer.items.add(file);
        });
        
        hiddenInput.files = dataTransfer.files;
    }
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
    
    input[type="file"] {
        padding: 10px;
        border: 2px dashed #d4c5b9;
        background: #fafaf8;
        cursor: pointer;
    }
    input[type="file"]:hover {
        border-color: #5a8f7b;
        background: #f5f3f0;
    }
    .form-text {
        font-size: 12px;
        color: #888;
        margin-top: 6px;
    }
    
    .btn-add-photo {
        padding: 12px 24px;
        background: #5a8f7b;
        color: white;
        border: 2px solid #4a7a66;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        font-size: 14px;
    }
    .btn-add-photo:hover {
        background: #4a7a66;
        transform: translateY(-1px);
    }
    
    .image-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }
    .preview-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 12px;
        background: #f5f3f0;
        border: 2px solid #d4c5b9;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .preview-item:hover {
        border-color: #5a8f7b;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .preview-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 6px;
    }
    .preview-filename {
        font-size: 12px;
        color: #6b6b6b;
        text-align: center;
        word-break: break-all;
    }
    .btn-remove-image {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        background: rgba(200, 90, 84, 0.95);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .btn-remove-image:hover {
        background: rgba(200, 90, 84, 1);
        transform: scale(1.1);
    }
    
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
        background: #ffffff;
        padding: 24px;
        border-radius: 12px;
        border: 2px solid #d4c5b9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .preview-meta {
        margin-bottom: 16px;
    }
    .preview-card h4 {
        color: #3d3d3d;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
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
        padding: 6px 14px;
        background: #e8f5f0;
        color: #5a8f7b;
        border-radius: 16px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #d4c5b9;
    }
    .preview-gallery {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .preview-gallery.single {
        grid-template-columns: 1fr;
    }
    .preview-gallery-item {
        position: relative;
        aspect-ratio: 16 / 9;
        border-radius: 8px;
        overflow: hidden;
        background: #f5f3f0;
    }
    .preview-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .preview-photo-count {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
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
    .expense-item {
        display: grid;
        grid-template-columns: 2fr 1fr auto;
        gap: 12px;
        align-items: center;
    }
    .btn-add-expense {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        background: #5a8f7b;
        color: white;
        border: 2px solid #4a7a66;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 12px;
    }
    .btn-add-expense:hover {
        background: #4a7a66;
        transform: translateY(-1px);
    }
    .btn-remove-expense {
        width: 36px;
        height: 36px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-remove-expense:hover {
        background: #dc2626;
        transform: scale(1.05);
    }
</style>

<script>
function addExpense() {
    const container = document.getElementById('expenses-container');
    const newExpense = document.createElement('div');
    newExpense.className = 'expense-item';
    newExpense.innerHTML = `
        <input type="text" class="form-control" placeholder="例: ランチ" name="expense_item[]">
        <input type="number" class="form-control" placeholder="例: 550" name="expense_amount[]" min="0">
        <button type="button" onclick="this.parentElement.remove()" class="btn-remove-expense" title="削除">×</button>
    `;
    container.appendChild(newExpense);
}
</script>
