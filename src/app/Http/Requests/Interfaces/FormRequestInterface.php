<?php
declare(strict_types=1);

namespace App\Http\Requests\Interfaces;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Redirector;

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

    public function prepareForValidation(): void;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array;

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData();

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated();

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages();

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes();

    /**
     * Set the Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator(Validator $validator);

    /**
     * Set the Redirector instance.
     *
     * @param  \Illuminate\Routing\Redirector  $redirector
     * @return $this
     */
    public function setRedirector(Redirector $redirector);

    /**
     * Set the container implementation.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return $this
     */
    public function setContainer(Container $container);
}
