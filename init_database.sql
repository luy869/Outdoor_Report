-- データベースの初期化スクリプト

-- 既存のテーブルを削除（順序に注意）
DROP TABLE IF EXISTS report_tags;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS photos;
DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS locations;
DROP TABLE IF EXISTS authentications;
DROP TABLE IF EXISTS users;

-- usersテーブル
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_fields TEXT,
    last_login TIMESTAMP NULL DEFAULT NULL,
    login_hash VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- authenticationsテーブル
CREATE TABLE authentications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    provider VARCHAR(50) NOT NULL,
    uid VARCHAR(255) NOT NULL,
    access_token TEXT,
    secret VARCHAR(255),
    refresh_token TEXT,
    expires TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_provider_uid (provider, uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- locationsテーブル
CREATE TABLE locations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- reportsテーブル
CREATE TABLE reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    location_id INT UNSIGNED,
    title VARCHAR(32) NOT NULL,
    body TEXT NOT NULL,
    visit_date DATE NOT NULL,
    privacy TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_visit_date (visit_date),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- photosテーブル
CREATE TABLE photos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    description VARCHAR(32),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    INDEX idx_report_id (report_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- tagsテーブル
CREATE TABLE tags (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(32) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- report_tagsテーブル（多対多の中間テーブル）
CREATE TABLE report_tags (
    report_id INT UNSIGNED NOT NULL,
    tag_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (report_id, tag_id),
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- expensesテーブル
CREATE TABLE expenses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_id INT UNSIGNED NOT NULL,
    item_name VARCHAR(32) NOT NULL,
    amount INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    INDEX idx_report_id (report_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- テストユーザーの追加
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password123
('testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password123

-- テスト用の場所データ
INSERT INTO locations (name, latitude, longitude) VALUES
('鎌倉大仏殿高徳院', 35.3167, 139.5364),
('富士山', 35.3606, 138.7278);

-- テスト用のレポート
INSERT INTO reports (user_id, location_id, title, body, visit_date, privacy) VALUES
(2, 1, '鎌倉への旅行', '今日は初めの鎌倉のコーヒーショップを巡る旅に出かけました。最初の目的地はカフェ・ロコでした。このカフェは海沿いに位置しており、素晴らしい景色を眺めながらコーヒーを楽しむことができました。次に、ミニマリストなデザインの七ダンカフェ・ザ・クワイエットを訪れました。彼らのコールドブリューは、暖かい午後にぴったりのさわやかさとべたつきをおししました。最後に、ポベミアン公園国の緑の中に佇むカフェ、ピーンサンナコーヒー通りを訪ねました。核らのブァーバーハーコーヒーは特別で、ブレンドリーなバリスタとおしゃべりするのを楽しみました。それぞれのカフェには独自の魅力があり、コーヒー作りの芸術に対する情熱を感じて帰りました。', '2025-07-15', 1);

-- テスト用の費用データ
INSERT INTO expenses (report_id, item_name, amount) VALUES
(1, 'ラテ', 550),
(1, 'コールドブリュー', 600),
(1, 'フィルターオーバーコーヒー', 700);

-- テスト用のタグ
INSERT INTO tags (name) VALUES
('カフェ巡り'),
('鎌倉');

-- レポートとタグの関連付け
INSERT INTO report_tags (report_id, tag_id) VALUES
(1, 1),
(1, 2);
