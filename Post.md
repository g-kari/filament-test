# Filamentデモ用Postモデル

このドキュメントには、FilamentデモでPostモデルに使用されるモデル定義が含まれています。

## マイグレーション

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

## モデル

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'status'];
}
```

## Filamentリソース

Postリソースは以下のコマンドを使用して作成されます：

```bash
php artisan make:filament-resource Post --generate
```

これにより以下が自動生成されます：
- `app/Filament/Resources/PostResource.php`のリソースクラス
- 一覧ページ
- 作成/編集フォーム
- リソースリレーション（存在する場合）