# Outdoor Report - アウトドア活動記録アプリ

## 概要
Outdoor Reportは、アウトドア活動の記録を管理できるWebアプリケーションです。登山、キャンプ、釣りなどのアウトドアアクティビティの詳細を記録し、写真や経費情報とともに保存できます。

## 主な機能
- **ユーザー認証**: ログイン/新規登録/ログアウト機能
  - パスワードハッシュ化（password_hash）
  - レート制限（5回失敗で15分ロックアウト）
  - セッションローテーション
- **レポート管理**: CRUD操作（作成・閲覧・編集・削除）
- **画像アップロード**: 複数枚の写真を添付可能、プレビュー機能付き
- **タグ管理**: レポートにタグを追加して分類
- **経費記録**: 各レポートに関連する経費を記録
- **いいね機能**: 非同期（Ajax）でのいいね/いいね解除
- **リアクティブUI**: Knockout.jsによる動的フォーム検証と入力補助

## 使用技術

### バックエンド
- **PHP**: 7.3
- **FuelPHP**: 1.8（MVCフレームワーク）
- **MySQL**: 8.0
- **認証**: Session管理、CSRF保護

### フロントエンド
- **Knockout.js**: 3.5.1（MVVMライブラリ）
- **Fetch API**: 非同期通信
- **HTML5/CSS3**: レスポンシブデザイン

### インフラ
- **Docker**: コンテナ化
- **Docker Compose**: マルチコンテナ管理
- **Git/GitHub**: バージョン管理

## データベース設計

### テーブル構成
1. **users** - ユーザー情報
   - id, username, email, password, created_at

2. **reports** - レポート情報（1:n関係の親）
   - id, user_id, title, content, location, activity_date, created_at, updated_at

3. **photos** - 写真情報（1:n関係の子）
   - id, report_id, file_path, created_at

4. **tags** - タグ情報（1:n関係の子）
   - id, report_id, name, created_at

5. **expenses** - 経費情報（1:n関係の子）
   - id, report_id, category, amount, description, created_at

6. **likes** - いいね情報（1:n関係の子）
   - id, user_id, report_id, created_at

## セキュリティ対策
- **CSRF保護**: FuelPHP標準機能（自動トークン検証）
- **SQLインジェクション対策**: DBクエリビルダー使用（生SQLを使用しない）
- **パスワードハッシュ化**: password_hash() / password_verify()
- **レート制限**: ログイン試行回数制限（5回失敗で15分ロックアウト）
- **セッション管理**: Session::rotate()によるセッションID再生成
- **入力検証**: メールアドレス検証、パスワード長チェック
- **エラーログ**: Log::error()による例外処理記録

## 技術要件（インターン課題）
- ✅ FuelPHP使用
- ✅ before()メソッドでの認証チェック
- ✅ config/〜での設定管理
- ✅ Session使用
- ✅ namespace利用（Model\Report, Model\User）
- ✅ \バックスラッシュでグローバル名前空間指定
- ✅ DB::select()等のクエリビルダー使用（生SQL禁止）
- ✅ 1:nのテーブル設計（reports ← photos/tags/expenses/likes）
- ✅ CRUD操作実装
- ✅ Knockout.js使用
- ✅ Ajax等の非同期UI（いいね機能）
- ✅ GitHubでのバージョン管理

## インターン課題環境構築手順

## Dockerの基本知識
Dockerの基本的な概念については、以下のリンクを参考にしてください：
- [Docker入門（1）](https://qiita.com/Sicut_study/items/4f301d000ecee98e78c9)
- [Docker入門（2）](https://qiita.com/takusan64/items/4d622ce1858c426719c7)

## セットアップ手順

1. **リポジトリをクローン**
   ```bash
   git clone <リポジトリURL>
   ```

2. **dockerディレクトリに移動**
   ```bash
   cd docker
   ```

3. **データベース名の設定**
   `docker-compose.yml` 内の `db` サービスにある `MYSQL_DATABASE` の値を、各自任意のデータベース名に設定してください。
   
   例:
   ```yaml
   environment:
     MYSQL_ROOT_PASSWORD: root
     MYSQL_DATABASE: <your_database_name>  # 任意のデータベース名を指定
   ```

4. **Dockerイメージのビルド**
   ```bash
   docker-compose build
   ```

5. **コンテナの起動**
   ```bash
   docker-compose up -d
   ```
6. **ブラウザからlocalhostにアクセス**

## PHP周りのバージョン
- **PHP**: 7.3
- **FuelPHP**: 1.8

## ログについて
- **アクセスログ**: Dockerのコンテナのログ
- **FuelPHPのエラーログ**: /var/www/html/intern_kadai/fuel/app/logs/
  - 年月日ごとにログが管理されている
  - tail -f {見たいログファイル}でログを出力

## MySQLコンテナ設定
このプロジェクトには、MySQLを使用するDBコンテナが含まれています。設定は以下の通りです。

- **MySQLバージョン**: 8.0
- **ポート**: `3306`
- **環境変数**:
  - `MYSQL_ROOT_PASSWORD`: root
  - `MYSQL_DATABASE`: 各自設定したデータベース名

### アクセス情報
- **ホスト**: `localhost`
- **ポート**: `3306`
- **ユーザー名**: `root`
- **パスワード**: `root`
- **データベース名**: 各自設定した名前
