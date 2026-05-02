<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Chuẩn hóa slug chiến dịch: từ dạng {user_code}/{part} sang một segment duy nhất, không trùng trong bảng.
     */
    public function up(): void
    {
        if (! Schema::hasTable('campaigns')) {
            return;
        }

        $rows = DB::table('campaigns')->orderBy('id')->get(['id', 'slug']);

        foreach ($rows as $row) {
            $segments = array_values(array_filter(explode('/', (string) $row->slug)));
            $base = $segments !== [] ? Str::slug(end($segments)) : 'campaign';
            if ($base === '') {
                $base = 'campaign';
            }

            $candidate = $base;
            $n = 2;
            while (DB::table('campaigns')->where('slug', $candidate)->where('id', '!=', $row->id)->exists()) {
                $candidate = $base . '-' . $n;
                $n++;
            }

            if ($candidate !== $row->slug) {
                DB::table('campaigns')->where('id', $row->id)->update(['slug' => $candidate]);
            }
        }
    }

    /**
     * Không khôi phục slug dạng cũ (mất thông tin user_code trong slug).
     */
    public function down(): void
    {
        //
    }
};
