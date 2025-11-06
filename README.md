# Outdoor Report - アウトドア活動記録共有アプリ

アウトドア活動の思い出を記録・共有できるWebアプリケーション

## 主な機能

- **ユーザー認証** - メールアドレス/パスワードでの登録・ログイン
- **レポート投稿** - タイトル、本文、訪問日、場所、タグ、費用、写真（最大4枚）
- **タイムライン** - 公開レポートの時系列表示
- **検索機能** - キーワード、タグ、場所、日付範囲での絞り込み
- **いいね機能** - レポートへのいいね・いいね解除
- **プロフィール** - ユーザー情報・投稿一覧の表示と編集
- **公開/非公開設定** - レポートの公開範囲を選択可能


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

#### 3. データベースの初期化
```bash
# データベース作成
docker-compose -f docker/docker-compose.yml exec -T db mysql -uroot -p3556 -e "CREATE DATABASE IF NOT EXISTS outdoor_reports CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# テーブル作成（すべてのテーブルとカラムを含む）
docker-compose -f docker/docker-compose.yml exec -T db mysql -uroot -p3556 outdoor_reports < sql_archive/00_create_tables.sql
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
http://localhost:8080
```

テストアカウントでログインして動作確認してください。


## セキュリティ機能

- **CSRF対策** - フォーム送信時にトークン検証
- **XSS対策** - 出力時の自動エスケープ
- **SQLインジェクション対策** - プリペアドステートメント使用
- **パスワードハッシュ化** - bcryptによる安全な保存
- **レート制限** - ログイン試行回数の制限
- **アップロード検証** - ファイル形式・サイズのチェック（最大20MB）



