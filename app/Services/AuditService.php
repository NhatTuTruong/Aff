<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public static function log(string $description, ?Model $subject = null, array $properties = [], ?string $event = null): ?ActivityLog
    {
        $causer = Auth::user();

        return ActivityLog::log($description, $subject, $causer, $properties, $event);
    }

    public static function logCreate(Model $model): void
    {
        $subjectName = class_basename($model);
        $id = $model->getKey();

        self::log(
            "Tạo {$subjectName} #{$id}",
            $model,
            ['attributes' => $model->getAttributes()],
            'created'
        );
    }

    public static function logUpdate(Model $model, array $old, array $new): void
    {
        $subjectName = class_basename($model);
        $id = $model->getKey();

        $changes = [];

        foreach ($new as $key => $newValue) {
            if (!array_key_exists($key, $old)) {
                continue;
            }

            $oldValue = $old[$key];

            // Bỏ qua timestamp
            if (in_array($key, ['updated_at', 'created_at'])) {
                continue;
            }

            // Nếu là array / json
            if (is_array($newValue) || is_array($oldValue)) {
                if (json_encode($newValue) !== json_encode($oldValue)) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
                continue;
            }

            // So sánh kiểu thường
            if ((string) $newValue !== (string) $oldValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        if (empty($changes)) {
            return;
        }

        self::log(
            "Cập nhật {$subjectName} #{$id}",
            $model,
            [
                'old'     => $old,
                'new'     => $new,
                'changes' => $changes,
            ],
            'updated'
        );
    }

    public static function logDelete(Model $model): void
    {
        $subjectName = class_basename($model);
        $id = $model->getKey();

        self::log(
            "Xóa {$subjectName} #{$id}",
            $model,
            ['attributes' => $model->getAttributes()],
            'deleted'
        );
    }

    public static function logLogin(Model $user): void
    {
        self::log(
            'Đăng nhập vào Admin',
            $user,
            ['email' => $user->email ?? null],
            'login'
        );
    }

    public static function logImport(string $importer, int $rows, ?int $userId = null): void
    {
        $causer = $userId ? \App\Models\User::find($userId) : Auth::user();

        ActivityLog::log(
            "Import file: {$importer}, {$rows} dòng",
            null,
            $causer,
            ['importer' => $importer, 'rows' => $rows, 'user_id' => $userId],
            'import'
        );
    }
}
