<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    public function created(Model $model)
    {
        $this->logEvent($model, 'created', null, $model->getAttributes());
    }

    public function updated(Model $model)
    {
        $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
        $newValues = $model->getChanges();

        unset($oldValues['password'], $newValues['password']);

        if (!empty($newValues)) {
            $this->logEvent($model, 'updated', $oldValues, $newValues);
        }
    }

    public function deleted(Model $model)
    {
        $this->logEvent($model, 'deleted', $model->getOriginal(), null);
    }

    protected $dirtyFields = ['password', 'remember_token', 'updated_at'];

    private function logEvent(Model $model, string $event, ?array $oldValues, ?array $newValues)
    {
        if ($oldValues) {
            foreach ($this->dirtyFields as $field) { unset($oldValues[$field]); }
        }
        if ($newValues) {
            foreach ($this->dirtyFields as $field) { unset($newValues[$field]); }
        }

        AuditLog::create([
            'user_id'        => Auth::id(),
            'event'          => $event,
            'auditable_type' => get_class($model),
            'auditable_id'   => $model->getKey(),
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'url'            => Request::fullUrl(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);
    }
}