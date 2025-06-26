# CLAUDE.md

このファイルは、このリポジトリのコードを扱う際のClaude Code (claude.ai/code) 向けのガイダンスを提供します。

## プロジェクト概要

FilamentベースのLaravelアプリケーションです。以下の機能を含みます：
- FrankenPHPベースの高速開発環境（MySQL、Valkey/Redis）
- ロールと設定を持つユーザー管理システム
- 画像管理機能
- 監査ログ機能
- 複雑なデータ入力のためのマルチページウィザードフォーム

## アーキテクチャと技術スタック

### バックエンド
- **Laravel**: PHPフレームワーク
- **Filament v3**: 管理パネルフレームワーク
- **FrankenPHP**: 高性能PHPアプリケーションサーバー
- **MySQL 8.0**: プライマリデータベース
- **Redis/Valkey**: キャッシュとセッションストレージ
- **Docker**: コンテナ化開発環境

### 主要モデル
- `TUser`: ユーザー管理
- `MUserRole`: ロール定義
- `TUserRole`: ユーザー-ロール関係
- `TUserSetting`: ユーザー設定
- `TImage`: 画像/ファイル管理
- `LUserLog`: 監査ログ

## コーディング規約

### Laravel/PHP標準
- PSR-12コーディング標準に従う
- Laravelベストプラクティスと規約を使用
- 適切なエラーハンドリングとバリデーションを実装
- データベース操作にはEloquent ORMを使用
- 一貫した命名規則を維持（データベースはsnake_case、PHPはcamelCase）

### Filament固有のガイドライン
- Filamentのフォームビルダーコンポーネントを一貫して使用
- 別々のPagesでリソース構成を適切に実装
- リソースとコンポーネントにはFilamentの命名規則に従う
- Filamentの組み込みバリデーションとフォーム処理を使用
- Filamentのリレーションシップ管理機能を活用

### データベース規約
- テーブル名にプレフィックスを使用：`t_`（トランザクション）、`m_`（マスター）、`l_`（ログ）
- 監査フィールドを含める：`created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`
- 適切なカスケード設定で外部キー制約を使用
- 適切なインデックスでデータ整合性を維持

## 開発ガイドライン

### ファイル構造
```
app/
├── Filament/
│   ├── Exports/          # エクスポート機能
│   ├── Resources/        # Filamentリソース
│   │   └── [Model]Resource/
│   │       └── Pages/    # リソースページ
│   └── Traits/          # 共有トレイト
├── Models/              # Eloquentモデル
database/
├── migrations/          # データベースマイグレーション
```

### 維持すべき主要機能
1. **監査証跡**: 全モデルで`HasAuditFields`トレイトを使用した監査フィールドをサポート
2. **エクスポート機能**: 全リソースでExcelエクスポート機能を維持
3. **マルチページフォーム**: 複雑なデータ入力のためのウィザードスタイルフォームをサポート
4. **リレーションシップ管理**: ユーザー-ロール関係の適切な処理
5. **画像処理**: 堅牢なファイルアップロードと管理システム

## 開発環境

### FrankenPHP環境の起動
```bash
# 開発環境を開始
docker-compose -f docker-compose.frankenphp.dev.yml up -d

# 本番環境を開始
docker-compose -f docker-compose.frankenphp.yml up -d

# 簡易起動スクリプト（推奨）
./start-frankenphp.sh dev    # 開発環境
./start-frankenphp.sh prod   # 本番環境
```

### アクセス情報
- **アプリケーション**: `http://localhost`
- **Filament管理画面**: `http://localhost/admin`
- **管理者ログイン**: admin@example.com / password

### データベースアクセス
- ホスト: `localhost`（ホストから）または`db`（コンテナから）
- ポート: `3306`
- ユーザー名: `filament`
- パスワード: `secret`
- データベース: `filament`

### 主要コマンド
```bash
# FrankenPHPコンテナに入る
docker exec -it filament-frankenphp-dev bash

# マイグレーション実行
docker exec filament-frankenphp-dev php artisan migrate

# Filamentユーザー作成
docker exec filament-frankenphp-dev php artisan make:filament-user

# キャッシュクリア
docker exec filament-frankenphp-dev php artisan cache:clear
docker exec filament-frankenphp-dev php artisan config:clear
docker exec filament-frankenphp-dev php artisan view:clear

# Filament設定
docker exec filament-frankenphp-dev php artisan filament:install --panels
```

## セキュリティ考慮事項

### 認証と認可
- Filamentの組み込み認証システムを使用
- 適切なロールベースアクセス制御を実装
- 全ユーザー入力をバリデート
- LaravelのCSRF保護を使用

### データ保護
- 全データベース入力をサニタイズ
- 機密データにはLaravelの暗号化を使用
- 適切なセッション管理を実装
- OWASPセキュリティガイドラインに従う

## パフォーマンスガイドライン

### データベース最適化
- N+1クエリを防ぐためにEager Loadingを使用
- 適切なデータベースインデックスを実装
- 複雑な操作にはデータベーストランザクションを使用
- クエリパフォーマンスを監視

### キャッシュ戦略
- セッションストレージとキャッシュにRedisを使用
- 適切な場所でアプリケーションレベルキャッシュを実装
- 頻繁にアクセスされるデータをキャッシュ
- Laravelの組み込みキャッシュメカニズムを使用

## デプロイメント考慮事項

### 本番環境
- 詳細なホスティング推奨事項は[HOSTING.md](HOSTING.md)を参照
- 環境固有の設定を使用
- 適切なSSL/TLS証明書を実装
- 監視とログ設定
- 自動バックアップの設定

## AIアシスタントワークフロー

### このプロジェクトで作業する際
1. **コンテキスト理解**: 既存のコード構造と規約を確認
2. **パターンに従う**: 既存の実装パターンとの一貫性を維持
3. **変更をテスト**: Docker環境内で修正が動作することを確認
4. **ドキュメント**: 重要な変更を行う際は関連ドキュメントを更新
5. **セキュリティ**: 変更のセキュリティ影響を常に考慮

### 一般的なタスク
- 適切なCRUD操作を持つ新しいFilamentリソースの作成
- 新しいモデルのエクスポート機能実装
- 新しいモデルへの監査証跡サポート追加
- マルチステップウィザードフォームの作成
- データベースクエリとリレーションシップの最適化

### コードレビューチェックリスト
- [ ] LaravelとFilamentの規約に従っている
- [ ] 適切なバリデーションとエラーハンドリングを含む
- [ ] 適切な場所で監査証跡を実装
- [ ] 一貫した命名規則を使用
- [ ] 適切なコメントとドキュメントを含む
- [ ] テストが通り、機能が期待通りに動作する

## リソースとドキュメント

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- プロジェクト固有のドキュメント：
  - [HOSTING.md](HOSTING.md) - 本番デプロイメントガイド
  - [MULTI_PAGE_FORM.md](MULTI_PAGE_FORM.md) - マルチページウィザード実装
  - [Post.md](Post.md) - モデル実装例

## 注意事項

- このプロジェクトはFrankenPHPベースの高性能開発環境を使用
- アプリケーションは英語と日本語コンテンツの両方をサポート
- 監査ログは全主要モデルに実装済み
- 管理データのエクスポート機能が利用可能
- コードベースはFilament v3固有のパターンを持つLaravelベストプラクティスに従う
- git filemodeは無効化済み（Docker環境での権限問題を回避）

## 重要な設定

### Git設定
```bash
git config core.filemode false  # ファイル権限変更を無視
```

### 管理者アカウント
- Email: admin@example.com
- Password: password
- 管理画面: http://localhost/admin

疑問がある場合は、既存のコードパターンを参照し、確立されたアーキテクチャとの一貫性を維持してください。