<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * @SWG\Post(
     *   path="/api/password/email",
     *   tags={"Auth"},
     *   summary="Forgot password",
     *   operationId="email",
     *   @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     description="email",
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
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return response([
            'status' => 'success',
            'data' => 'true'
           ], 200);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response([
            'status' => 'failure',
            'data' => 'false'
           ], 400);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }

        $availableGroup = ['admin', 'owner', 'user']; 
         
        if(!(in_array($request->get('role'), $availableGroup))){
            return response()->json(['error'=>'Incorrect role'], 400); 
        }

        $correct_role = false;
        $user = User::where('users.email', '=', $request->email)->first();
        
        if(empty($user)){
            return response()->json(['error'=>'Incorrect email'], 400);
        }

        foreach ($user->roles as $key => $value) {
            if($value->name == $request->role){
                $correct_role = true;
            }
        }

        if($correct_role){
            $this->validateEmail($request);
        } else {
            return response()->json(['error'=>'Access denied'], 400);
        }  


        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }        
}
