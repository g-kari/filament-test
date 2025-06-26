# Filament Laravel アプリケーション

## FrankenPHP高性能開発環境

このリポジトリには、FrankenPHP、MySQL、Valkey（Redisフォーク）を使用したFilament v3管理パネルの完全な開発環境が含まれています。FrankenPHPは従来のPHP-FPM + Nginxより高性能なPHPアプリケーションサーバーです。

### 前提条件

- システムにDockerとDocker Composeがインストールされていること
- Git

### クイックスタート

1. このリポジトリをクローン：
   ```bash
   git clone https://github.com/g-kari/filament-test.git
   cd filament-test
   ```

2. FrankenPHP開発環境を開始：
   ```bash
   ./start-frankenphp.sh dev
   ```

3. ブラウザでFilament管理パネルにアクセス：
   ```
   http://localhost/admin
   ```

4. 管理者アカウントでログイン：
   - **Email**: admin@example.com
   - **Password**: password

## アプリケーション機能

このFilamentアプリケーションには以下の機能が含まれています：

- **ユーザー管理**: ロールベースアクセス制御付きユーザー管理
- **画像管理**: ファイルアップロード、エディター、メタデータ管理
- **設定管理**: マルチページウィザードによるユーザー設定管理
- **監査ログ**: 全操作の追跡とログ記録
- **データエクスポート**: Excel形式でのデータエクスポート

### 技術スタック

- **FrankenPHP**: 高性能PHPアプリケーションサーバー（PHP 8.4）
- **Laravel**: PHPフレームワーク
- **Filament v3**: 管理パネルフレームワーク
- **MySQL 8.0**: プライマリデータベース
- **Valkey**: Redis互換のインメモリデータストア

### データベース接続

- ホスト: `localhost`（ホストから）または`db`（コンテナから）
- ポート: `3306`
- ユーザー名: `filament`
- パスワード: `secret`
- データベース: `filament`

### Valkey接続

- ホスト: `localhost`（ホストから）または`valkey`（コンテナから）
- ポート: `6379`

## 開発コマンド

### FrankenPHPコンテナでの作業

```bash
# FrankenPHPコンテナに入る
docker exec -it filament-frankenphp-dev bash

# マイグレーション実行
docker exec filament-frankenphp-dev php artisan migrate

# キャッシュクリア
docker exec filament-frankenphp-dev php artisan cache:clear
docker exec filament-frankenphp-dev php artisan config:clear

# 新しいFilamentユーザー作成
docker exec filament-frankenphp-dev php artisan make:filament-user
```

### 環境管理

```bash
# 開発環境を開始
./start-frankenphp.sh dev

# 本番環境を開始  
./start-frankenphp.sh prod

# Docker Compose直接使用
docker-compose -f docker-compose.frankenphp.dev.yml up -d  # 開発
docker-compose -f docker-compose.frankenphp.yml up -d      # 本番
```

## FrankenPHPについて

このプロジェクトはFrankenPHP（Go言語で書かれた最新のPHPアプリケーションサーバー）を使用し、従来のPHP-FPM + Nginxの組み合わせよりも高性能を実現しています。

### FrankenPHPの利点

- **高性能**: 従来のPHP-FPM + Nginxより高速
- **メモリ効率**: より少ないメモリ使用量
- **HTTP/2サポート**: ネイティブHTTP/2対応
- **Worker Mode**: 長時間実行プロセスでのパフォーマンス向上
- **統合**: WebサーバーとPHPランタイムが統合

### 設定ファイル

- `Caddyfile` - 本番環境用設定
- `Caddyfile.dev` - 開発環境用設定
- `docker-compose.frankenphp.yml` - 本番環境用Docker設定
- `docker-compose.frankenphp.dev.yml` - 開発環境用Docker設定

### トラブルシューティング

問題が発生した場合：

```bash
# コンテナログを確認
docker-compose -f docker-compose.frankenphp.dev.yml logs

# すべてのコンテナが実行中であることを確認
docker-compose -f docker-compose.frankenphp.dev.yml ps

# すべてのコンテナを再起動
docker-compose -f docker-compose.frankenphp.dev.yml restart

# キャッシュクリア
docker exec filament-frankenphp-dev php artisan cache:clear
```

## ドキュメント

- **[CLAUDE.md](CLAUDE.md)** - Claude Code向けの詳細な開発ガイダンス
- **[HOSTING.md](HOSTING.md)** - 本番デプロイメントガイド
- **[MULTI_PAGE_FORM.md](MULTI_PAGE_FORM.md)** - マルチページウィザード実装詳細
- **[Post.md](Post.md)** - モデル実装例

## ライセンス

このプロジェクトはMITライセンスの下で公開されています。