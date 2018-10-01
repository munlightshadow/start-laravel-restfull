<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\S3Helper;
use Validator;

use App\Http\Resources\UserFull as UserFullResource;
use App\Http\Resources\User as UserResource;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Get(
     *   path="/api/user/show/{id}",
     *   tags={"User"},
     *   summary="Show user",
     *   operationId="showUser",
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id user for show",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */     
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['owner', 'user']);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error'=>'User not isset'], 400);
        }
        
        return new UserFullResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Post(
     *   path="/api/user/update/{id}",
     *   tags={"User"},
     *   summary="Update user",
     *   operationId="updateUser",
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="User id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     description="User name",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     description="User email",
     *     required=false,
     *     type="string"
     *   ),      
     *   @SWG\Parameter(
     *     name="avatar",
     *     in="formData",
     *     description="Avatar picture",
     *     required=false,
     *     type="file"
     *   ),            
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */         
    public function update(Request $request, $id)
    {
        $request->user()->authorizeRoles(['owner', 'user']);

        $validator = Validator::make($request->all(), [
            'email' => 'email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error'=>'User not isset'], 400);
        }

        if ($user->id != auth()->user()->id) {
            return response()->json(['error'=>'You don\'t own this user'], 400);
        }

        $user->name = (isset($request->name) && $request->name) ? $request->name : $user->name;
        $user->email = (isset($request->email) && $request->email) ? $request->email : $user->email;

        if ($request->avatar) {
            $old_avatar = $user->avatar;
            $file_row = S3Helper::addFile($request->avatar, 'user/avatar');
            $user->avatar = $file_row->id;

            $user->save();

            if ($old_avatar) {
                S3Helper::deleteFile($old_avatar);
            }
        } else {
            $user->save();            
        }

        return response([
            'status' => 'success',
            'data' =>  new UserResource($user)
           ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
