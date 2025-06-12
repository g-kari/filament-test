# Filament-test ホスティングサービスの検討

このドキュメントでは、Filament-testリポジトリを本番環境にデプロイする際のホスティングサービスとデプロイメント戦略について説明します。

## 推奨ホスティングサービス

### 1. クラウドプラットフォーム

#### AWS (Amazon Web Services)
- **EC2 + RDS + ElastiCache**: スケーラブルで柔軟な構成
- **ECS/Fargate**: Docker コンテナベースのデプロイメント
- **Elastic Beanstalk**: Laravel アプリケーションの簡単デプロイ
- **費用**: 中〜高（従量課金制）
- **適用場面**: エンタープライズレベル、高いカスタマイズが必要な場合

#### Google Cloud Platform (GCP)
- **Compute Engine + Cloud SQL + Memorystore**: AWS に類似した構成
- **Cloud Run**: サーバーレスコンテナプラットフォーム
- **App Engine**: Laravel 対応のPaaS
- **費用**: 中（無料枠あり）
- **適用場面**: Google サービスとの統合が必要な場合

#### Microsoft Azure
- **Virtual Machines + Azure Database + Azure Cache**: 従来型の構成
- **Container Instances**: Docker デプロイメント
- **App Service**: Laravel サポートのPaaS
- **費用**: 中〜高
- **適用場面**: Microsoft エコシステムとの統合が必要な場合

### 2. 専用 Laravel ホスティング

#### Laravel Forge + デジタルオーシャン
- **特徴**: Laravel に特化した自動化されたデプロイメント
- **費用**: 低〜中（$12/月〜）
- **適用場面**: Laravel アプリケーションの迅速なデプロイ

#### Laravel Vapor (AWS Serverless)
- **特徴**: AWS Lambda ベースのサーバーレス Laravel ホスティング
- **費用**: 中（$39/月〜 + AWS費用）
- **適用場面**: 自動スケーリングが必要な場合

### 3. VPS/専用サーバー

#### DigitalOcean
- **Droplets**: シンプルなVPS
- **App Platform**: PaaS型サービス
- **費用**: 低（$5/月〜）
- **適用場面**: 小〜中規模アプリケーション

#### Linode
- **特徴**: 高性能VPS
- **費用**: 低（$5/月〜）
- **適用場面**: パフォーマンス重視の場合

#### Vultr
- **特徴**: グローバルな展開、SSD ストレージ
- **費用**: 低（$2.50/月〜）
- **適用場面**: 国際的な展開が必要な場合

## デプロイメント戦略

### 1. Docker ベースデプロイメント

現在の `docker-compose.yml` を本番環境用に調整：

```yaml
# 本番環境用 docker-compose.prod.yml の例
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile.prod
    restart: always
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    volumes:
      - storage:/var/www/storage
      - bootstrap_cache:/var/www/bootstrap/cache

  web:
    image: nginx:1.25-alpine
    restart: always
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/prod.conf:/etc/nginx/conf.d/default.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - app

  db:
    image: mysql:8.0
    restart: always
    environment:
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:7-alpine
    restart: always
    volumes:
      - redis_data:/data

volumes:
  storage:
  bootstrap_cache:
  mysql_data:
  redis_data:
```

### 2. 従来型デプロイメント

1. **サーバー要件**:
   - PHP 8.2以上
   - MySQL 8.0以上
   - Redis 6以上
   - Nginx または Apache

2. **デプロイメントスクリプト**:
```bash
#!/bin/bash
# deploy.sh
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan queue:restart
sudo systemctl reload nginx
```

## 本番環境設定

### 1. 環境変数設定

```env
# .env.production
APP_NAME="Filament Production"
APP_ENV=production
APP_KEY= # php artisan key:generate で生成
APP_DEBUG=false
APP_URL=https://your-domain.com

# データベース設定
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Redis設定
REDIS_HOST=your-redis-host
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379

# キューとキャッシュ
QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis

# メール設定
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# ファイルストレージ
FILESYSTEM_DISK=s3  # または local
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=ap-northeast-1
AWS_BUCKET=your-bucket-name
```

### 2. Nginx 設定例

```nginx
# /etc/nginx/sites-available/filament-app
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    root /var/www/filament-app/public;

    # SSL設定
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;

    # セキュリティヘッダー
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## セキュリティ考慮事項

### 1. 基本的なセキュリティ対策

- **SSL/TLS証明書**: Let's Encrypt または有料証明書の使用
- **ファイアウォール**: 必要なポートのみ開放（80, 443, 22）
- **定期的なアップデート**: OS、PHP、Laravel、依存関係の更新
- **強力なパスワード**: データベース、Redis、管理者アカウント
- **バックアップ**: 日次の自動バックアップ設定

### 2. Laravel 固有のセキュリティ

```php
// config/app.php
'debug' => env('APP_DEBUG', false),

// .htaccess または Nginx でヘッダー設定
// X-Frame-Options: SAMEORIGIN
// X-Content-Type-Options: nosniff
// X-XSS-Protection: 1; mode=block
```

### 3. データベースセキュリティ

- 専用データベースユーザーの作成
- 最小権限の原則
- SSL接続の有効化
- 定期的なセキュリティパッチ適用

## パフォーマンス最適化

### 1. Laravel 最適化

```bash
# 本番環境デプロイ時に実行
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer install --optimize-autoloader --no-dev
```

### 2. データベース最適化

- インデックスの適切な設定
- クエリ最適化
- 読み取り専用レプリカの利用（必要に応じて）

### 3. キャッシング戦略

- **Redis**: セッション、キャッシュ、キューに使用
- **CDN**: 静的ファイルの配信（AWS CloudFront、CloudFlare）
- **OPcache**: PHP バイトコードキャッシュの有効化

## 監視とログ

### 1. アプリケーション監視

- **Laravel Telescope**: 開発・ステージング環境での詳細な監視
- **New Relic/Datadog**: 本番環境でのAPM
- **Sentry**: エラー追跡とログ集約

### 2. インフラ監視

- **Uptime監視**: Pingdom、UptimeRobot
- **サーバー監視**: Nagios、Zabbix
- **ログ管理**: ELK Stack、Graylog

## コスト比較

| サービス | 月額概算費用 | 適用規模 | 特徴 |
|---------|------------|---------|------|
| DigitalOcean Droplet | $20-40 | 小〜中 | シンプル、コスパ良 |
| AWS EC2 (t3.small) | $20-50 | 中〜大 | 高い拡張性 |
| Laravel Forge + DO | $32-52 | 中 | Laravel特化 |
| Laravel Vapor | $39 + AWS費用 | 大 | サーバーレス |
| Shared Hosting | $5-15 | 極小 | 制限多い |

## 推奨デプロイメントフロー

1. **開発環境**: Local Docker development
2. **ステージング環境**: 本番環境の縮小版
3. **本番環境**: 選択したクラウドプラットフォーム

```bash
# 推奨ワークフロー
git push origin develop           # ステージングデプロイトリガー
git push origin main             # 本番デプロイトリガー（手動承認後）
```

## まとめ

アプリケーションの規模と要件に応じて最適なホスティングサービスを選択してください：

- **小規模・予算重視**: DigitalOcean Droplet
- **Laravel特化・迅速デプロイ**: Laravel Forge
- **エンタープライズ・高スケーラビリティ**: AWS/GCP
- **サーバーレス・自動スケーリング**: Laravel Vapor

どのサービスを選択しても、セキュリティ、パフォーマンス、監視の基本原則は同じです。継続的な改善とメンテナンスを心がけ、安定したサービス提供を目指してください。