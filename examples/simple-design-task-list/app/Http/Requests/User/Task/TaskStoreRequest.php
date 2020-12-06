<?php
declare(strict_types=1);

namespace App\Http\Requests\User\Task;

use App\Http\Requests\User\Task\Interfaces\TaskStoreRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest implements TaskStoreRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:10',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Task Name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.require' => ':attribute is required.',
            'name.max' => ':attribute must be :digits characters or less.',
        ];
    }
}
