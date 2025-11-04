-- テストデータ挿入スクリプト
-- 環境構築時に実行してテストアカウントとレポートを作成

-- =====================================================
-- 1. テストユーザー作成（4アカウント）
-- =====================================================
-- パスワードはすべて "password" (bcrypt hash)
INSERT INTO users (username, email, password, created_at) VALUES
('testuser1', 'test1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('testuser2', 'test2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('testuser3', 'test3@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
('testuser4', 'test4@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());

-- =====================================================
-- 2. 場所データ作成
-- =====================================================
INSERT INTO locations (name, created_at) VALUES
('富士山', NOW()),
('高尾山', NOW()),
('屋久島', NOW()),
('北アルプス', NOW()),
('丹沢山系', NOW());

-- =====================================================
-- 3. タグデータ作成
-- =====================================================
INSERT INTO tags (name, created_at) VALUES
('登山', NOW()),
('キャンプ', NOW()),
('トレッキング', NOW()),
('初心者向け', NOW()),
('絶景', NOW()),
('温泉', NOW()),
('紅葉', NOW()),
('日帰り', NOW());

-- =====================================================
-- 4. testuser1のレポート3件（公開2件 + 非公開1件）
-- =====================================================
-- レポート1: 富士山登山レポート（公開）
INSERT INTO reports (user_id, location_id, title, body, visit_date, privacy, created_at, updated_at) VALUES
(1, 1, '初めての富士登山！絶景に感動', 
'人生初の富士登山に挑戦しました。朝4時に出発し、御来光を目指して登りました。途中、高山病で苦しい場面もありましたが、仲間の励ましで何とか登頂成功！

頂上から見る景色は言葉では表せないほど美しく、苦労して登った甲斐がありました。下山後の温泉も最高でした。', 
'2024-08-15', 0, NOW(), NOW());

-- レポート1の写真
INSERT INTO photos (report_id, image_url, sort_order, created_at) VALUES
(1, '/assets/uploads/photos/fuji1.jpg', 1, NOW()),
(1, '/assets/uploads/photos/fuji2.jpg', 2, NOW());

-- レポート1のタグ
INSERT INTO report_tags (report_id, tag_id) VALUES
(1, 1), -- 登山
(1, 5); -- 絶景

-- レポート1の費用
INSERT INTO expenses (report_id, item_name, amount, created_at) VALUES
(1, '登山道入山料', 2000, NOW()),
(1, '山小屋宿泊', 8000, NOW()),
(1, '食事代', 3000, NOW());

-- レポート2: 高尾山ハイキング（公開）
INSERT INTO reports (user_id, location_id, title, body, visit_date, privacy, created_at, updated_at) VALUES
(1, 2, '紅葉の高尾山ハイキング', 
'秋の高尾山に行ってきました。ケーブルカーを使わず、1号路を歩いて登りました。所要時間は約1時間半。

紅葉が見頃で、赤や黄色の葉が本当に美しかったです。頂上の天狗焼きとビールが最高でした！', 
'2024-11-02', 0, NOW(), NOW());

-- レポート2の写真
INSERT INTO photos (report_id, image_url, sort_order, created_at) VALUES
(2, '/assets/uploads/photos/takao1.jpg', 1, NOW());

-- レポート2のタグ
INSERT INTO report_tags (report_id, tag_id) VALUES
(2, 3), -- トレッキング
(2, 4), -- 初心者向け
(2, 7), -- 紅葉
(2, 8); -- 日帰り

-- レポート2の費用
INSERT INTO expenses (report_id, item_name, amount, created_at) VALUES
(2, '交通費', 1200, NOW()),
(2, '天狗焼き', 500, NOW()),
(2, 'ビール', 600, NOW());

-- レポート3: 屋久島トレッキング（非公開）
INSERT INTO reports (user_id, location_id, title, body, visit_date, privacy, created_at, updated_at) VALUES
(1, 3, '屋久島・縄文杉トレッキング計画', 
'来月の屋久島トレッキングの準備メモ。

持ち物リスト：
- レインウェア
- トレッキングシューズ
- ヘッドライト
- 行動食
- 水筒

ガイドさんにお願い済み。楽しみ！', 
'2024-12-15', 1, NOW(), NOW());

-- レポート3のタグ
INSERT INTO report_tags (report_id, tag_id) VALUES
(3, 1), -- 登山
(3, 3); -- トレッキング

-- =====================================================
-- 5. testuser2のレポート3件（公開2件 + 非公開1件）
-- =====================================================
-- レポート4: 北アルプステント泊（公開）
INSERT INTO reports (user_id, location_id, title, body, visit_date, privacy, created_at, updated_at) VALUES
(2, 4, '北アルプス縦走！テント泊3泊4日', 
'念願の北アルプス縦走に挑戦しました。上高地から入山し、槍ヶ岳を目指す3泊4日のテント泊登山。

天気にも恵まれ、満天の星空と朝焼けに染まる山々を堪能できました。テント場での他の登山者との交流も楽しかったです。

総重量20kgの荷物を背負っての登山はハードでしたが、達成感は格別でした！', 
'2024-09-10', 0, NOW(), NOW());

-- レポート4の写真
INSERT INTO photos (report_id, image_url, sort_order, created_at) VALUES
(4, '/assets/uploads/photos/alps1.jpg', 1, NOW()),
(4, '/assets/uploads/photos/alps2.jpg', 2, NOW()),
(4, '/assets/uploads/photos/alps3.jpg', 3, NOW());

-- レポート4のタグ
INSERT INTO report_tags (report_id, tag_id) VALUES
(4, 1), -- 登山
(4, 2), -- キャンプ
(4, 5); -- 絶景

-- レポート4の費用
INSERT INTO expenses (report_id, item_name, amount, created_at) VALUES
(4, 'バス往復', 8000, NOW()),
(4, 'テント場利用料', 3000, NOW()),
(4, '食材', 5000, NOW());

-- レポート5: 丹沢山日帰り登山（公開）
INSERT INTO reports (user_id, location_id, title, body, visit_date, privacy, created_at, updated_at) VALUES
(2, 5, '丹沢山で日帰りトレーニング', 
'久しぶりの登山トレーニングで丹沢山へ。大倉尾根ルートを選択。

「バカ尾根」の異名通り、ひたすら階段が続く厳しいコースでしたが、いい汗かけました。頂上からの富士山の眺めは最高です。

下山後は鶴巻温泉で疲れを癒しました。', 
'2024-10-20', 0, NOW(), NOW());

-- レポート5の写真
INSERT INTO photos (report_id, image_url, sort_order, created_at) VALUES
(5, '/assets/uploads/photos/tanzawa1.jpg', 1, NOW());

-- レポート5のタグ
INSERT INTO report_tags (report_id, tag_id) VALUES
(5, 1), -- 登山
(5, 6), -- 温泉
(5, 8); -- 日帰り

-- レポート5の費用
INSERT INTO expenses (report_id, item_name, amount, created_at) VALUES
(5, '交通費', 2000, NOW()),
(5, '温泉入浴料', 800, NOW()),
(5, '昼食', 1200, NOW());

-- レポート6: キャンプギア購入メモ（非公開）
INSERT INTO reports (user_id, location_id, title, body, visit_date, privacy, created_at, updated_at) VALUES
(2, NULL, '次回のキャンプ用品リスト', 
'買い物メモ：

- 新しいテント（MSR Hubba Hubba）
- シュラフ（冬用-10度）
- バーナー（SOTO レギュレーターストーブ）
- クッカーセット
- LEDランタン

予算：約8万円', 
'2024-11-01', 1, NOW(), NOW());

-- レポート6のタグ
INSERT INTO report_tags (report_id, tag_id) VALUES
(6, 2); -- キャンプ

-- =====================================================
-- 6. いいねデータ作成（相互にいいね）
-- =====================================================
-- testuser1がtestuser2のレポートにいいね
INSERT INTO likes (user_id, report_id, created_at) VALUES
(1, 4, NOW()),
(1, 5, NOW());

-- testuser2がtestuser1のレポートにいいね
INSERT INTO likes (user_id, report_id, created_at) VALUES
(2, 1, NOW()),
(2, 2, NOW());

-- testuser3がいくつかいいね
INSERT INTO likes (user_id, report_id, created_at) VALUES
(3, 1, NOW()),
(3, 4, NOW());

-- testuser4がいくつかいいね
INSERT INTO likes (user_id, report_id, created_at) VALUES
(4, 1, NOW()),
(4, 2, NOW()),
(4, 4, NOW()),
(4, 5, NOW());
