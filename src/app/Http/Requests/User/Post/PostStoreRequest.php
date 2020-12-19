<?php
declare(strict_types=1);

namespace App\Http\Requests\User\Post;

use App\Http\Requests\User\Post\Interfaces\PostStoreRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest implements PostStoreRequestInterface
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
            'caption' => 'required|max:255',
            'image' => 'required|image', //note: image: jpg、png、bmp、gif、svg、webp
        ];
    }

    public function attributes(): array
    {
        return [
            'caption' => 'Caption',
            'image' => 'Image',
        ];
    }

    public function messages(): array
    {
        return [
            'caption.required' => ':attribute is required.',
            'caption.max' => ':attribute may not be greater than :max characters.',

            'image.required' => ':attribute is required.',
            'image.image' => ':attribute must be image file.',
        ];
    }
}
