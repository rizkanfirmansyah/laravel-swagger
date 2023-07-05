<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Http\Request;


/**
 * @OA\Schema(
 *     schema="Genre",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Genre Name"),
 *     @OA\Property(property="description", type="string", example="Genre Description"),
 *     @OA\Property(property="genre_id", type="integer", example=1),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category"
 *     ),
 *     @OA\Property(
 *         property="books",
 *         type="array",
 *              @OA\Items(ref="#/components/schemas/Book")
 *         ),
 * )
 *
 */

class GenreController extends Controller
{
    /**
     * Display a listing of the genres.
     *
     * @OA\Get(
     *     path="/api/genres",
     *     summary="Get all genres",
     *     tags={"Genres"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Genres retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Genre")
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        try {
            $genres = Genre::with(['category', 'books'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Genres retrieved successfully',
                'data' => $genres,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve genres',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
        $genres = Genre::with(['category', 'books'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Genres retrieved successfully',
            'data' => $genres,
        ]);
    }

    /**
     * Store a newly created genre in storage.
     *
     * @OA\Post(
     *     path="/api/genres",
     *     summary="Create a new genre",
     *     tags={"Genres"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GenreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Genre created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Genre created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Genre")
     *         )
     *     ),
     *      @OA\Response(
     *         response="403",
     *         description="Forbidden/Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden/Unauthorized"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *      @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unprocessable Entity"),
     *              @OA\Property(property="data", type="object", nullable=true),
     *              @OA\Property(property="errors", type="object", nullable=true),
     *          )
     *      )
     * )
     */
    public function store(GenreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $genre = Genre::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Genre created successfully',
                'data' => $genre,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create genre',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified genre.
     *
     * @OA\Get(
     *     path="/api/genres/{id}",
     *     summary="Get a specific genre",
     *     tags={"Genres"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Genre ID",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Genre retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Genre")
     *         )
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Genre not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Genre not found"),
     *              @OA\Property(property="data", type="string", nullable=true, example=null),
     *          )
     *      ),
     * )
     */
    public function show($id)
    {
        try {
            $genre = Genre::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Genre retrieved successfully',
                'data' => $genre,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve genre',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified genre in storage.
     *
     * @OA\Put(
     *     path="/api/genres/{id}",
     *     summary="Update an existing genre",
     *     tags={"Genres"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Genre ID",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GenreRequest")
     *     ),
     *      @OA\Response(
     *         response="403",
     *         description="Forbidden/Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden/Unauthorized"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Genre updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Genre updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Genre")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Genre not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Genre not found"),
     *             @OA\Property(property="errors", type="object", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unprocessable Entity"),
     *             @OA\Property(property="errors", type="object", nullable=true),
     *         )
     *     )
     * )
     */
    public function update(GenreRequest $request, $id)
    {

        try {
            $validatedData = $request->validated();

            $genre = Genre::findOrFail($id);
            $genre->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Genre updated successfully',
                'data' => $genre,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update genre',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        };
    }

    /**
     * Remove the specified genre from storage.
     *
     * @OA\Delete(
     *     path="/api/genres/{id}",
     *     summary="Delete a genre",
     *     tags={"Genres"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Genre ID",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Genre deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Genre deleted successfully"),
     *             @OA\Property(property="data", type="object", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Genre not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Genre not found"),
     *             @OA\Property(property="errors", type="object", nullable=true),
     *         )
     *     ),
     *      @OA\Response(
     *         response="403",
     *         description="Forbidden/Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden/Unauthorized"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="errors", type="object", nullable=true),
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete genre',
                'error' => $e->getMessage(),
            ], 500);
        };
    }
}
