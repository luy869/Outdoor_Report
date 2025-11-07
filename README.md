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

### ER図概要
```
users (1) ──< (N) reports (N) >── (1) locations
              │                        
              ├─< (N) photos           
              ├─< (N) expenses         
              ├─< (N) likes >─ (1) users
              └─< (N) report_tags >─ (1) tags
```

### テーブル詳細

#### 1. users（ユーザー）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | ユーザーID |
| username | VARCHAR(50) | ユーザー名（一意） |
| email | VARCHAR(100) | メールアドレス（一意） |
| password | VARCHAR(255) | パスワード（bcryptハッシュ化） |
| avatar_url | VARCHAR(255) | アバター画像URL |
| bio | TEXT | 自己紹介文 |
| created_at | TIMESTAMP | 登録日時 |
| updated_at | TIMESTAMP | 更新日時 |

**インデックス:** username, email

---

#### 2. reports（レポート）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | レポートID |
| user_id | INT (FK) | 投稿者ID |
| location_id | INT (FK) | 場所ID（NULL可） |
| title | VARCHAR(200) | タイトル |
| body | TEXT | 本文 |
| visit_date | DATE | 訪問日 |
| privacy | TINYINT(1) | 公開設定（0:公開, 1:非公開） |
| created_at | TIMESTAMP | 投稿日時 |
| updated_at | TIMESTAMP | 更新日時 |

**外部キー:** 
- user_id → users(id) ON DELETE CASCADE
- location_id → locations(id) ON DELETE SET NULL

**インデックス:** user_id, location_id, privacy, visit_date, created_at

---

#### 3. photos（写真）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | 写真ID |
| report_id | INT (FK) | レポートID |
| image_url | VARCHAR(255) | 画像URL |
| sort_order | INT | 表示順序 |
| created_at | TIMESTAMP | 登録日時 |

**外部キー:** report_id → reports(id) ON DELETE CASCADE

**インデックス:** report_id, sort_order

**制約:** 1レポートあたり最大4枚

---

#### 4. locations（場所）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | 場所ID |
| name | VARCHAR(100) | 場所名（一意） |
| created_at | TIMESTAMP | 登録日時 |

**インデックス:** name

---

#### 5. tags（タグ）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | タグID |
| name | VARCHAR(50) | タグ名（一意） |
| created_at | TIMESTAMP | 登録日時 |

**インデックス:** name

---

#### 6. report_tags（レポート-タグ関連）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | 関連ID |
| report_id | INT (FK) | レポートID |
| tag_id | INT (FK) | タグID |

**外部キー:** 
- report_id → reports(id) ON DELETE CASCADE
- tag_id → tags(id) ON DELETE CASCADE

**インデックス:** report_id, tag_id

**ユニーク制約:** (report_id, tag_id)

---

#### 7. likes（いいね）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | いいねID |
| user_id | INT (FK) | いいねしたユーザーID |
| report_id | INT (FK) | いいね対象レポートID |
| created_at | TIMESTAMP | いいね日時 |

**外部キー:** 
- user_id → users(id) ON DELETE CASCADE
- report_id → reports(id) ON DELETE CASCADE

**インデックス:** user_id, report_id

**ユニーク制約:** (user_id, report_id)

---

#### 8. expenses（費用）
| カラム名 | 型 | 説明 |
|---------|-----|------|
| id | INT (PK) | 費用ID |
| report_id | INT (FK) | レポートID |
| item_name | VARCHAR(100) | 費用項目名 |
| amount | INT | 金額 |
| created_at | TIMESTAMP | 登録日時 |

**外部キー:** report_id → reports(id) ON DELETE CASCADE

**インデックス:** report_id

---

### データベースの特徴

- **文字コード:** utf8mb4（絵文字対応）
- **照合順序:** utf8mb4_unicode_ci
- **ストレージエンジン:** InnoDB（トランザクション、外部キー対応）
- **カスケード削除:** ユーザー削除時に関連データを自動削除
- **参照整合性:** 外部キーで関連性を保証


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

# テーブル作成
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



