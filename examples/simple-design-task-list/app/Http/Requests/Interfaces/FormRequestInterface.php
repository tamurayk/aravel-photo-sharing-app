<?php
declare(strict_types=1);

namespace App\Http\Requests\Interfaces;

/**
 * Interface FormRequestInterface
 * @package App\Http\Requests\Interfaces
 */
interface FormRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool;

    /**
     * @return void
     */
    public function prepareForValidation(): void;

    /**
     * @return array
     */
    public function attributes(): array;

    /**
     * @return array
     */
    public function rules(): array;

    /**
     * @return array
     */
    public function messages(): array;
}
