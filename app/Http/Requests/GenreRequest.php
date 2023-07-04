<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="GenreRequest",
 *     required={"name", "description", "category_id"},
 *     @OA\Property(property="name", type="string", example="Genre Name"),
 *     @OA\Property(property="description", type="string", example="Genre Description"),
 *     @OA\Property(property="category_id", type="integer", example=1),
 * )
 */

class GenreRequest extends FormRequest
{
    public function authorize()
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
