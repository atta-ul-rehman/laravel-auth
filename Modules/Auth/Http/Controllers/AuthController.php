<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Auth\Http\Requests\UserRegVal;
use Modules\Auth\Http\Requests\UserLoginVal;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\ResetPassVal;
use Modules\Auth\Http\Requests\UpdatePassVal;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/auth/login",
     * operationId="authLogin",
     * tags={"Authentication"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *    
     * @OA\Response(
     *     response=201,
     *     description="Login Successfully",
     *     @OA\JsonContent(
     *         @OA\Property(property="success", type="boolean", example=true),
     *         @OA\Property(property="data", type="object",
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *             @OA\Property(property="name", type="string", example="Florine Robel MD"),
     *         ),
     *         @OA\Property(property="message", type="string", example="User logged in successfully"),
     *         @OA\Property(property="role", type="array",
     *             @OA\Items(type="string", example="Admin"),
     *         ),
     *     ),
     *),)
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *               @OA\Property(property="token", type="string", example="Credentails does not match...")
     *       ),
     * )
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

     public function login(UserLoginVal $request)
     {
         $credentials = $request->validated();
         if (Auth::guard('web')->attempt($credentials)) {
             $user = User::where('email', $request->email)->first();
             $success['token'] = $user->createToken('Myapp')->accessToken;
             $success['name'] = $user->name;
 
             $response = [
                 'success' => true,
                 'data' => $success,
                 'message' => 'User loggined successfully',
                 'role' => $user->roles->pluck('name')
             ];
 
             return response()->json($response, 200);
         } else {
             $user = User::where('email', $credentials['email'])->first();
             if (!$user) {
                 // Email is incorrect
                 return response()->json([
                     'success' => false,
                     'message' => 'Email is incorrect',
                 ]);
             } else {
                 // Password is incorrect
                 return response()->json([
                     'success' => false,
                     'message' => 'Password is incorrect',
                 ]);
             }
         }
         return response()->json('Internal Server Error', 400);
     }
    /**
     * @OA\Get(
     *     path="/auth/user",
     *     operationId="getUser",
     *     tags={"Authentication"},
     *     summary="Get the authenticated user",
     *     description="Returns the details of the authenticated user",
     *     security={{"passport": {}}},
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         @OA\Property(property="data",   @OA\schema(ref="#/components/schemas/User"))
     *     )
     * ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated.",
     *                 description="Error message"
     *             )
     *         )
     *     ),
     * )
     */

    public function getUser(Request $request)
    {
        $user = $request->user();
        return $user;
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="User Registration",
     *     tags={"Authentication"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *             type="object",
     *             required={"email", "password" ,"name" ,"phone" ,"company_id"},
     *             @OA\Property(property="name", type="string", example="test2"),
     *             @OA\Property(property="email", type="string", format="email", example="test2@example.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(property="phone", type="string", example="+92 3441565456"),
     *             @OA\Property(property="company_id", type="string", example="company_id"),
     *         ),
     *     ),
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *                 @OA\Property(property="name", type="string", example="test2"),
     *             ),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *         ),
     *     ),
     * )
     */

    public function register(UserRegVal $request)
    {
        try {

            $input = $request->validated();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);

            $success['token'] = $user->createToken('Myapp')->accessToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User created successfully'
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];

            return response()->json($response, 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout",
     *     tags={"Authentication"},
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out"),
     *         ),
     *     ),
     * )
     */
    public function logout()
    {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/auth/forget-password",
     *     summary="Forget Password",
     *     tags={"Authentication"},
     *    @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  type="object",
     *                 required={"email"},
     *                 @OA\Property(property="email", type="string", format="email", example="test2@example.com"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="passwords.sent"),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found"),
     *             @OA\Property(property="code", type="integer", example=400),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Internal Server Error"),
     *             @OA\Property(property="code", type="integer", example=500),
     *         ),
     *     ),
     * )
     */
    public function forgetPassword(Request $request)
    {
        try {
            $input = $request->validate(['email' => 'required|email']);

            if (User::where('email', $input)->exists()) {
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                $status === Password::RESET_LINK_SENT ?
                    $response = [
                        'success' => true,
                        'message' => $status,
                        'code' => 200
                    ]
                    :
                    $response = [
                        'success' => false,
                        'message' => $status,
                        'code' => 400
                    ];

                return response()->json($response, $response['code']);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'User not found',
                ];
            }
            return response()->json($response, 400);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/auth/reset-password/{token}",
     *     summary="Reset Password",
     *     tags={"Authentication"},
     *         @OA\Parameter(
     *     name="token",
     *     in="query",
     *     description="Password resetToken",
     *     @OA\Schema(
     *         type="string"
     *     )
     * ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",    
     *                 @OA\Property(property="email", type="string", format="email", example="test2@example.com"),
     *                 @OA\Property(property="password", type="string", example="newpassword"),
     *                 @OA\Property(property="password_confirmation", type="string", example="newpassword"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password reset successfully"),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid token or validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid token or validation error"),
     *             @OA\Property(property="code", type="integer", example=400),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Internal Server Error"),
     *             @OA\Property(property="code", type="integer", example=500),
     *         ),
     *     ),
     * )
     */
    public function resetPassword(resetPassVal $request, $token)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:5|confirmed',
            ]);
        
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
                    $user->save();
        
                    event(new PasswordReset($user));
                }
            );
        
            if ($status === Password::PASSWORD_RESET) {
                $response = [
                    'success' => true,
                    'message' => "Password reset successfully",
                    'code' => 200
                ];
            } else {
                throw ValidationException::withMessages([
                    'passwords' => [__($status)],
                ])->status(400);
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
        
        return response()->json($response, $response['code']);
    }
    /**
     * @OA\Post(
     *     path="/auth/change-password",
     *     summary="Change Password",
     *     tags={"Authentication"},
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"current_password", "new_password", "new_confirm_password"},
     *               @OA\Property(property="current_password", type="string", ),
     *               @OA\Property(property="new_password", type="string" ),
     *               @OA\Property(property="new_confirm_password", type="string"),
     *            ),
     *        ),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="string", example="Password updated successfully"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Validation error or unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="data", type="string", example="Internal Server Error"),
     *         ),
     *     ),
     * )
     */
    public function changePassword(UpdatePassVal $request)
    {
        try {
            $request->validated();
            User::find(Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);
            $response = [
                'success' => true,
                'data' => "Password updated successfully",
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'data' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
