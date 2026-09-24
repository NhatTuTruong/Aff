<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Livewire temp path + tên file tiếng Việt dài có thể vượt VARCHAR(255).
     */
    public function up(): void
    {
        if (! Schema::hasTable('imports')) {
            return;
        }

        DB::statement('ALTER TABLE imports MODIFY file_name VARCHAR(512) NOT NULL');
        DB::statement('ALTER TABLE imports MODIFY file_path VARCHAR(1024) NOT NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('imports')) {
            return;
        }

        DB::statement('ALTER TABLE imports MODIFY file_name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE imports MODIFY file_path VARCHAR(255) NOT NULL');
    }
};
