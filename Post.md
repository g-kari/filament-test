# Post Model for Filament Demo

This document contains the model definition that will be used for the Post model in the Filament demo.

## Migration

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

## Model

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

## Filament Resource

The Post resource will be created using the following command:

```bash
php artisan make:filament-resource Post --generate
```

This will automatically generate the following:
- A resource class in `app/Filament/Resources/PostResource.php`
- A list page
- A create/edit form
- Resource relations (if any)