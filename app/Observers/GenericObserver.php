<?php

namespace App\Observers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class GenericObserver
{
    public function created($model)
    {
        Log::create([
            'employee_id' => auth('employee')->id(),
            'action'      => 'create',
            'model_type'  => class_basename($model),
            'model_id'    => $model->id,
            'changes'     => json_encode([
                'message' => 'New ' . class_basename($model) . ' added'
            ]),
        ]);
    }

    public function updated($model)
    {
        $changes = [];

        foreach ($model->getChanges() as $attribute => $newValue) {
            if ($attribute === 'updated_at') {
                continue;
            }

            $original = $model->getOriginal($attribute);
            if (is_string($newValue) && $this->isJson($newValue)) {
                $newValue = json_decode($newValue, true);
            }

            if (is_string($original) && $this->isJson($original)) {
                $original = json_decode($original, true);
            }
                $changes[$attribute] = [
                'old' => $original,
                'new' => $newValue,
            ];
        }

        if (!empty($changes)) {
            Log::create([
                'employee_id' => auth('employee')->id() ,
                'action'      => 'update',
                'model_type'  => class_basename($model),
                'model_id'    => $model->id,
                'changes'     => json_encode($changes),
            ]);
        }
    }

    public function deleted($model)
    {
        Log::create([
            'employee_id' => auth('employee')->id() ,
            'action'      => 'delete',
            'model_type'  =>  class_basename($model),
            'model_id'    => $model->id,
            'changes'     => json_encode(['old' => $model->getOriginal()]),
        ]);
    }
    private function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

