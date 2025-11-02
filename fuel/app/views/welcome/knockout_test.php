<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knockout.js テストページ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .container {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
        }
        input, button {
            padding: 8px;
            margin: 5px 0;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
        }
        .remove-btn {
            background: #dc3545;
            margin-left: 10px;
        }
        .remove-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Knockout.js テストページ</h1>
        
        <h2>データバインディングのデモ</h2>
        <div>
            <p>名前: <input data-bind="value: name, valueUpdate: 'input'" placeholder="名前を入力" /></p>
            <p>こんにちは、<strong data-bind="text: name"></strong>さん!</p>
        </div>

        <hr>

        <h2>リスト管理のデモ</h2>
        <div>
            <input data-bind="value: newItem, valueUpdate: 'input'" placeholder="新しいアイテム" />
            <button data-bind="click: addItem">追加</button>
            
            <h3>アイテムリスト</h3>
            <ul data-bind="foreach: items">
                <li>
                    <span data-bind="text: $data"></span>
                    <button class="remove-btn" data-bind="click: $parent.removeItem">削除</button>
                </li>
            </ul>
            <p>合計: <strong data-bind="text: items().length"></strong>個</p>
        </div>

        <hr>

        <h2>計算プロパティのデモ</h2>
        <div>
            <p>価格: <input data-bind="value: price, valueUpdate: 'input'" type="number" /></p>
            <p>数量: <input data-bind="value: quantity, valueUpdate: 'input'" type="number" /></p>
            <p>合計金額: <strong data-bind="text: total"></strong>円</p>
        </div>
    </div>

    <!-- Knockout.js - unpkg CDN (安定版) -->
    <script src="https://unpkg.com/knockout@3.5.1/build/output/knockout-latest.js"></script>
    
    <script>
        // ページ読み込み後に実行
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ko !== 'undefined') {
                console.log('Knockout.js loaded successfully');
                
                function ViewModel() {
                    var self = this;
                    
                    // データバインディング用
                    self.name = ko.observable("ゲスト");
                    
                    // リスト管理用
                    self.items = ko.observableArray(["サンプル1", "サンプル2", "サンプル3"]);
                    self.newItem = ko.observable("");
                    
                    self.addItem = function() {
                        if (self.newItem() !== "") {
                            self.items.push(self.newItem());
                            self.newItem("");
                        }
                    };
                    
                    self.removeItem = function(item) {
                        self.items.remove(item);
                    };
                    
                    // 計算プロパティ用
                    self.price = ko.observable(1000);
                    self.quantity = ko.observable(1);
                    
                    self.total = ko.computed(function() {
                        return self.price() * self.quantity();
                    });
                }
                
                // ViewModelを適用
                ko.applyBindings(new ViewModel());
            } else {
                console.error('Knockout.js failed to load');
                alert('Knockout.jsの読み込みに失敗しました。');
            }
        });
    </script>
</body>
</html>
