<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class TaskValidator
{
    public static function validate($data)
    {
        return Validator::make($data, [
            'title' => 'required|string|unique:tasks',
            'description' => 'string|nullable',
            'status' => 'in:pending,completed',
            'due_date' => 'required|date|after:today',
        ]);
    }
}
