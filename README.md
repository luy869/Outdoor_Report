# 🏔️ Outdoor Report - アウトドア活動記録共有アプリ

アウトドア活動の思い出を記録・共有できるWebアプリケーション

## ✨ 主な機能

### 🔐 認証・セキュリティ
- **ユーザー登録・ログイン**
  - パスワードハッシュ化（bcrypt）
  - CSRF保護（トークン自動更新）
  - セッション固定攻撃対策
  - レート制限（5回失敗で15分ロック）
  - 重複メール・ユーザー名チェック

### 📝 レポート作成・管理
- **リアルタイムプレビュー**
  - Knockout.jsによる双方向データバインディング
  - 実際の投稿デザインと一致したプレビュー
  - 文字数カウント機能
- **複数画像アップロード（最大4枚）**
  - 1枚ずつ追加可能
  - ×ボタンで個別削除
  - プレビュー表示
  - ギャラリー形式表示
- **タグ管理** - 動的追加・削除、検索対応
- **費用記録** - 複数項目の費用を記録
- **公開/非公開設定** - トグルスイッチで簡単切り替え

### 💚 いいね機能
- Ajax通信でページ遷移なし
- CSRFトークン自動更新（連続クリック対応）
- リアルタイムカウント表示

### 🔍 検索・フィルタ
- キーワード検索（タイトル・本文）
- タグ検索
- 場所検索
- 日付範囲検索

### 👤 プロフィール
- アバター画像アップロード
- ヘッダーアイコン自動連動
- ユーザー情報編集
- パスワード変更

## 🛠️ 技術スタック

### Backend
- PHP 7.3
- FuelPHP 1.8 - MVCフレームワーク
- MySQL 8.0

### Frontend
- Knockout.js 3.5.1 - MVVMフレームワーク
- JavaScript ES6 - DataTransfer API, FileReader API
- HTML5/CSS3 - レスポンシブデザイン

### Infrastructure
- Docker & Docker Compose
- Git/GitHub

## 📊 データベース設計

主要テーブル:
- users (ユーザー)
- reports (レポート)
- photos (写真)
- tags (タグ)
- report_tags (レポート-タグ関連)
- likes (いいね)
- expenses (費用)
- locations (場所)

詳細は IMPLEMENTATION_GUIDE.md を参照

## 🚀 セットアップ

### 前提条件
- Docker & Docker Compose
- Git

### インストール手順

1. リポジトリのクローン
\`\`\`bash
git clone https://github.com/luy869/Outdoor_Report.git
cd Outdoor_Report
\`\`\`

2. Dockerコンテナの起動
\`\`\`bash
cd docker
docker-compose up -d
\`\`\`

3. ブラウザでアクセス
\`\`\`
http://localhost:8080
\`\`\`

詳細は SETUP.md を参照

## 🔒 セキュリティ機能

- CSRF保護（全フォーム）
- XSS対策（出力エスケープ）
- SQLインジェクション対策（プリペアドステートメント）
- パスワードハッシュ化（bcrypt）
- セッション固定攻撃対策
- レート制限（ログイン試行）

## 📝 機能チェックリスト

✅ 全30項目の機能実装完了

詳細は CHECKLIST.md を参照

## 📁 プロジェクト構造

\`\`\`
Outdoor_Report/
├── docker/              # Docker設定
├── fuel/app/            # アプリケーション本体
│   ├── classes/         # コントローラー・モデル
│   ├── views/           # ビュー
│   └── config/          # 設定ファイル
├── public/              # 公開ディレクトリ
│   └── assets/uploads/  # アップロード画像
├── sql_archive/         # SQLファイル
└── *.md                 # ドキュメント
\`\`\`

## 📄 ライセンス

MIT License

## 👤 作成者

luy869

## 🔗 関連ドキュメント

- [CHECKLIST.md](CHECKLIST.md) - 機能チェックリスト
- [IMPLEMENTATION_GUIDE.md](IMPLEMENTATION_GUIDE.md) - 実装ガイド
- [SETUP.md](SETUP.md) - セットアップ詳細
- [TESTING.md](TESTING.md) - テストガイド

---

**Outdoor Report** - あなたのアウトドアの思い出を記録・共有しよう 🏕️
