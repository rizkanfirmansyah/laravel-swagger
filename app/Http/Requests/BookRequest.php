<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="BookRequest",
 *     required={"name", "description", "pages", "published_at", "author", "price", "genre_id"},
 *     @OA\Property(property="name", type="string", example="Book Name"),
 *     @OA\Property(property="description", type="string", example="Book Description"),
 *     @OA\Property(property="pages", type="integer", example=200),
 *     @OA\Property(property="published_at", type="string", format="date", example="2022-01-01"),
 *     @OA\Property(property="author", type="string", example="Book Author"),
 *     @OA\Property(property="price", type="number", format="float", example=19.99),
 *     @OA\Property(property="genre_id", type="integer", example=1),
 * )
 */

class BookRequest extends FormRequest
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
            'pages' => 'required|integer',
            'published_at' => 'required|date',
            'author' => 'required|string',
            'price' => 'required|numeric',
            'genre_id' => 'required|integer',
        ];
    }
}
