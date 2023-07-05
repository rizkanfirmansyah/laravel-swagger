<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Exception;
use Illuminate\Auth\Access\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Schema(
 *     schema="Book",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Book Name"),
 *     @OA\Property(property="description", type="string", example="Book Description"),
 *     @OA\Property(property="pages", type="integer", example=200),
 *     @OA\Property(property="published_at", type="string", format="date", example="2022-01-01"),
 *     @OA\Property(property="author", type="string", example="Book Author"),
 *     @OA\Property(property="price", type="number", format="float", example=19.99),
 *     @OA\Property(property="genre_id", type="integer", example=1),
 *     @OA\Property(
 *         property="genre",
 *         ref="#/components/schemas/Genre"
 *     )
 * )
 *
 *  @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="bearerAuth",
 *     bearerFormat="JWT",
 * )
 */

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     summary="Get all books",
     *     tags={"Books"},
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Books retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Book")
     *             ),
     *         ),
     *      @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null),
     *         ),
     *     ),
     *     )
     * )
     */
    public function index()
    {
        try {
            $books = Book::all();

            return response()->json([
                'success' => true,
                'message' => 'Books retrieved successfully',
                'data' => $books,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve books',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     summary="Get a book by ID",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book ID",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Book retrieved successfully",
     *         @OA\JsonContent(
     *          @OA\Property(property="success", type="boolean", example=true),
     *          @OA\Property(property="message", type="string", example="Book retrieved successfully"),
     *          @OA\Property(
     *              property="data",
     *              ref="#/components/schemas/Book"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Book not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Book not found"),
     *              @OA\Property(property="data", type="string", nullable=true, example=null),
     *          )
     *      ),
     * )
     */

    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Books retrieved successfully',
                'data' => $book,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve book',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     summary="Create a new book",
     *     tags={"Books"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BookRequest")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbidden/Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden/Unauthorized"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Book created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Book created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     *
     */
    public function store(BookRequest $request)
    {
        try {
            $book = Book::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Book created successfully',
                'data' => $book,
            ], 201);
        } catch (ValidationException $e) {
            $errors = $e->errors();

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $errors,
            ], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     summary="Update a book",
     *     tags={"Books"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BookRequest")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbidden/Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden/Unauthorized"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Book updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Book updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Book")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Book not found"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function update(BookRequest $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully',
                'data' => $book,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found',
                'data' => null,
            ], 404);
        } catch (ValidationException $e) {
            $errors = $e->errors();

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $errors,
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     summary="Delete a book",
     *     tags={"Books"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Book deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Book deleted successfully"),
     *             @OA\Property(property="data", type="null")
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
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully',
                'data' => null,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found',
                'data' => null,
            ], 404);
        }
    }
}
