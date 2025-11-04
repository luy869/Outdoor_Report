# Outdoor Report - アウトドア活動記録共有アプリ

アウトドア活動の思い出を記録・共有できるWebアプリケーション


## 技術スタック

### Backend
- PHP 7.3
- FuelPHP 1.8 
- MySQL 8.0

### Frontend
- Knockout.js 3.5.1 
- JavaScript ES6 
- HTML/CSS


## データベース設計

主要テーブル:
- users (ユーザー)
- reports (レポート)
- photos (写真)
- tags (タグ)
- report_tags (タグ)
- likes (いいね)
- expenses (費用)
- locations (場所)


## セットアップ

### 前提条件
- Docker & Docker Compose
- Git

### インストール手順

#### 1. リポジトリのクローン
```bash
git clone https://github.com/luy869/Outdoor_Report.git
cd Outdoor_Report
```

#### 2. Dockerコンテナの起動
```bash
cd docker
docker-compose up -d
cd ..
```

コンテナが起動したか確認:
```bash
docker-compose -f docker/docker-compose.yml ps
```

#### 3. データベースの初期化
```bash
# データベース作成
docker-compose -f docker/docker-compose.yml exec db mysql -uroot -p3556 -e "CREATE DATABASE IF NOT EXISTS outdoor_reports CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# テーブル作成
docker-compose -f docker/docker-compose.yml exec db mysql -uroot -p3556 outdoor_reports < sql_archive/00_create_tables.sql
```

#### 4. テストデータの挿入（オプション）
開発やテスト用に、サンプルアカウントとレポートデータを挿入:
```bash
docker-compose -f docker/docker-compose.yml exec -T db mysql -uroot -p3556 outdoor_reports < sql_archive/insert_test_data.sql
```

**テストアカウント:**
| ユーザー名 | メール | パスワード | レポート数 |
|-----------|--------|-----------|-----------|
| testuser1 | test1@example.com | password | 3件（公開2、非公開1） |
| testuser2 | test2@example.com | password | 3件（公開2、非公開1） |
| testuser3 | test3@example.com | password | 0件 |
| testuser4 | test4@example.com | password | 0件 |

#### 5. ブラウザでアクセス
```
http://localhost
```

テストアカウントでログインして動作確認してください。

### トラブルシューティング

**ポート競合エラー:**
- ローカルでMySQLが起動している場合、`docker-compose.yml`のポート設定を確認（デフォルト: 3307）

**データベース接続エラー:**
- `fuel/app/config/development/db.php`の設定を確認
- ホスト: `db` (コンテナ内) / `127.0.0.1` (ホストから)
- ポート: `3306` (コンテナ内) / `3307` (ホストから)
- パスワード: `3556`


## セキュリティ機能

- CSRF
- XSS対策
- SQLインジェクション対策
- パスワードハッシュ化
- レート制限（ログイン）
- アップロード検証


