# Filament-test

## Docker開発環境

このリポジトリには、Filament管理パネルをテストするための、PHP-FPM、Nginx、MySQL、Valkey（Redisフォーク）を含む完全なDockerベースの開発環境が含まれています。

### 前提条件

- システムにDockerとDocker Composeがインストールされていること
- Git

### 始めに

1. このリポジトリをクローン：
   ```
   git clone https://github.com/g-kari/filament-test.git
   cd filament-test
   ```

2. Dockerコンテナを開始：
   ```
   docker-compose up -d
   ```

3. ブラウザでFilament管理パネルにアクセス：
   ```
   http://localhost/admin
   ```

### 環境詳細

- **PHP-FPM**: Laravel/Filamentに必要な一般的な拡張機能を持つPHP 8.2
- **Nginx**: PHPアプリケーションを提供するように設定された最新安定版
- **MySQL**: 永続ストレージを持つバージョン8.0
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

### Laravel & Filamentセットアップ

FilamentでLaravelプロジェクトを新しくセットアップするには：

1. PHPコンテナでbashを実行：
   ```
   docker-compose exec app bash
   ```

2. 新しいLaravelプロジェクトを作成（まだ存在しない場合）：
   ```
   composer create-project laravel/laravel .
   ```

3. Filamentをインストール：
   ```
   composer require filament/filament:"^3.0-stable" -W
   ```

4. Filamentのインストールを実行：
   ```
   php artisan filament:install --panels
   ```

5. Filament用のユーザーを作成：
   ```
   php artisan make:filament-user
   ```

### トラブルシューティング

問題が発生した場合：

1. コンテナログを確認：
   ```
   docker-compose logs
   ```

2. すべてのコンテナが実行中であることを確認：
   ```
   docker-compose ps
   ```

3. すべてのコンテナを再起動：
   ```
   docker-compose restart
   ```

## フランケンPHP対応

このプロジェクトはフランケンPHP（FrankenPHP）での実行もサポートしています。フランケンPHPは、Go言語で書かれた最新のPHPアプリケーションサーバーで、従来のPHP-FPM + Nginxの組み合わせよりも高性能です。

### フランケンPHPで起動

```bash
# 開発環境で起動
./start-frankenphp.sh dev

# 本番環境で起動
./start-frankenphp.sh prod
```

### フランケンPHPの利点

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

## 本番ホスティング

本番デプロイメントの考慮事項、ホスティングサービスの推奨事項、デプロイメント戦略については、[HOSTING.md](HOSTING.md)を参照してください。