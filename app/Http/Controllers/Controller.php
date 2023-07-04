<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="REST API Book Catalog",
 *     version="1.0.9",
 *     description="This Open API for Book Catalog provides a comprehensive set of functionalities to manage book data. You can utilize this API to perform various operations such as retrieving book information, adding new book entries, searching for specific books, and more. The API offers a convenient way to interact with the book catalog, enabling seamless integration with your applications or systems.To access the book catalog data, you can make HTTP requests to the designated endpoints provided by the API. Here are some of the key features and endpoints offered by this API:
 * 1. Get Book Data: You can retrieve book information by making a GET request to the `/books` endpoint. This endpoint allows you to fetch the entire book catalog or filter books based on specific criteria such as title, author, genre, or publication year.
 * 2. Add New Book: To add a new book entry to the catalog, you can send a POST request to the `/books` endpoint. Include the necessary details of the book, such as title, author, genre, publication year, and any additional information required.
 * 3. Update Book Details: If you need to modify the information of an existing book, you can make a PUT or PATCH request to the `/books/{id}` endpoint, where `{id}` represents the unique identifier of the book. Provide the updated details for the book in the request payload.
 * 4. Delete Book: To remove a book from the catalog, send a DELETE request to the `/books/{id}` endpoint, where `{id}` corresponds to the identifier of the book you wish to delete.",
 *     @OA\Contact(
 *         email="riezkanaprianda@gmail.com"
 *     ),
 *     @OA\License(
 *         name="My Open API License",
 *         url="http://rizkandev.com"
 *     )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     * )
     */
}
