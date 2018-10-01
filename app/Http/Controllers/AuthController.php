<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Validator;
use Cookie;

use App\Http\Resources\User as UserResource;


/**
 * @SWG\Swagger(
 *     basePath="",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="This is API",
 *         description="Api for wine",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="contact@mysite.com"
 *         ),
 *         @SWG\License(
 *             name="Private License",
 *             url="URL to the license"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about my website",
 *         url="http..."
 *     )
 * )
 */
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @SWG\Post(
     *   path="/api/auth/signup/owner",
     *   tags={"Auth"},
     *   summary="Register new user. Where role = admin, owner or user",
     *   operationId="register",
     *   @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     description="User name.",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     description="Email",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     description="Password",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="c_password",
     *     in="query",
     *     description="Copy passwors",
     *     required=true,
     *     type="string"
     *   ),               
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    /**
     * @SWG\Post(
     *   path="/api/auth/signup/user",
     *   tags={"Auth"},
     *   summary="Register new user. Where role = admin, owner or user",
     *   operationId="register",
     *   @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     description="User name.",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     description="Email",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     description="Password",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="c_password",
     *     in="query",
     *     description="Copy passwors",
     *     required=true,
     *     type="string"
     *   ),               
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */    
	public function register(Request $request, $role)
	{        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }



	    $user = new User;
	    $user->email = $request->email;
	    $user->name = $request->name;
	    $user->password = bcrypt($request->password);
	    $user->save();

        $user
           ->roles()
           ->attach(Role::where('name', $role)->first());

	    return response([
	        'status' => 'success',
	        'data' => new UserResource($user),
	       ], 200);
	}

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @SWG\Post(
     *   path="/api/auth/login",
     *   tags={"Auth"},
     *   summary="Sign in",
     *   operationId="login",
     *   @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     description="User email.",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     description="Password",
     *     required=true,
     *     type="string"
     *   ), 
     *   @SWG\Parameter(
     *     name="role",
     *     in="query",
     *     description="admin or owner or user",
     *     required=true,
     *     type="string"
     *   ),     
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:255',
            'role' => 'required'
        ]);

        $availableGroup = ['admin', 'owner', 'user']; 
         
        if (!(in_array($request->get('role'), $availableGroup))) {
            return response()->json(['error'=>'Incorrect role'], 400); 
        }

        $credentials = $request->only('email', 'password');

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }

        if ($token = $this->guard()->attempt($credentials)) {

            $correct_role = false;

            foreach (auth()->user()->roles as $key => $value) {
                if($value->name == $request->role){
                    $correct_role = true;
                }
            }

            if($correct_role){
                return $this->respondWithToken($token);
            } else {
                return response()->json(['error'=>'Access denied'], 400);
            }            
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @SWG\Get(
     *   path="/api/auth/me",
     *   tags={"Auth"},
     *   summary="About user",
     *   operationId="me",
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),     
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */    
    public function me(Request $request)
    {
        return new UserResource($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @SWG\Post(
     *   path="/api/auth/logout",
     *   tags={"Auth"},
     *   summary="Logout user",
     *   operationId="logout",
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),     
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */       
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @SWG\Post(
     *   path="/api/auth/refresh",
     *   tags={"Auth"},
     *   summary="Refresh token",
     *   operationId="refresh",
     *   @SWG\Parameter(
     *     name="refresh_token",
     *     in="query",
     *     description="The refresh_token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    public function refresh(Request $request)
    {
        if($user_id = Cache::get('refresh_token.'.$request->refresh_token)){

            $user = User::findOrFail($user_id);
            $this->guard()->login($user);

            Cache::forget('refresh_token.'.$request->refresh_token);

            return $this->respondWithToken($this->guard()->refresh());

        };
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        do {
            $refresh_token = bcrypt(str_random(60));
        } while (Cache::has('refresh_token.'.$refresh_token));

        Cache::put('refresh_token.'.$refresh_token, auth()->id(), config('jwt.refresh_ttl'));

        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refresh_token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user()),
        ]);
    }	

    public function guard()
    {
        return Auth::guard();
    }    
}
