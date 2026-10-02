<?php

namespace Modules\Blogs\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Blogs\Models\Blog;

class BulkUpdateBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $model = (new Blog);
        $keyName = $model->getRouteKeyName();
        $tableName = $model->getTable();

        return [
            $tableName => 'required|array|min:1',
            $tableName.'.*' => 'required|array',
            $tableName.'.*.'.$keyName => 'required|exists:'.$tableName.','.$keyName,
            $tableName.'.*.is_enabled' => 'boolean',
            $tableName.'.*.order' => 'integer|min:1',
        ];
    }
}
