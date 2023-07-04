<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



/**
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     required={"name", "description"},
 *     @OA\Property(property="name", type="string", example="Category Name"),
 *     @OA\Property(property="description", type="string", example="Category Description"),
 * )
 */

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
