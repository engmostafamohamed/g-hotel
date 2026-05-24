<?php

namespace App\Http\Requests\V1\CRM\Permission;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:permissions,name,' . $this->route('id'),
        ];
    }

    public function messages(): array
    {
        return __('permission.validation');
    }
}
