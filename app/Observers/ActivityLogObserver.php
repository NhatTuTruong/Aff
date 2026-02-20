<?php

namespace App\Observers;

use App\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        if ($this->shouldLog($model)) {
            AuditService::logCreate($model);
        }
    }

    public function updated(Model $model): void
    {
        if ($this->shouldLog($model)) {
            AuditService::logUpdate($model, $model->getOriginal(), $model->getAttributes());
        }
    }

    public function deleted(Model $model): void
    {
        if ($this->shouldLog($model)) {
            AuditService::logDelete($model);
        }
    }

    protected function shouldLog(Model $model): bool
    {
        $skip = ['ActivityLog', 'Session', 'Job', 'FailedJob', 'PasswordResetToken', 'PersonalAccessToken'];

        return ! in_array(class_basename($model), $skip);
    }
}
