<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="rizkan"),
 *     @OA\Property(property="email", type="string", example="tester@gmail.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-07-04T05:09:19.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-04T05:09:19.000000Z"),
 *     @OA\Property(property="token", type="string", example="4|iiKlCYbsKJWp1m7m6o5NqQOuGUdUk3u3PMtEKTI1"),
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="User",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="John Doe"),
     *     @OA\Property(property="email", type="string", example="john@example.com"),
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Endpoint to login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login Successful"),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="JWT Token"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login Unsuccessful"),
     *             @OA\Property(property="data", type="null"),
     *         )
     *     ),
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = User::where('email', $request->email)->get();
                $token = $user[0]->createToken('userTokenApps');
                $user[0]['token'] = $token->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'data' => null,
                ], 401);
            }
        } catch (ValidationException $e) {
            $errors = $e->errors();

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $errors,
            ], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register",
     *     description="Endpoint to register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="confirm_password",
     *                     type="string",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Successful Register",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Register Successful"),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *         )
     *     ),
     *     @OA\Response(
     *     response="400",
     *     description="Bad Request",
     *     @OA\JsonContent(
     *         @OA\Property(property="success", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Registration unsuccessful"),
     *         @OA\Property(property="data", type="null"),
     *         @OA\Property(
     *             property="errors",
     *             type="object",
     *             @OA\Property(property="name", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="email", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="password", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="confirm_password", type="array", @OA\Items(type="string")),
     *         ),
     *     ),
     * )
     * )
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|string|same:password',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Create a new user
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => $user,
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();

            return response()->json([
                'success' => false,
                'message' => 'Registration unsuccessful',
                'data' => null,
                'errors' => $errors,
            ], 400);
        }
    }
}
