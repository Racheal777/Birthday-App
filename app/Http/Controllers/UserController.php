<?php

namespace App\Http\Controllers;

use App\Events\StatusUpdate;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {

        //save user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make( $request->input('password'));
        $user->age = $request->input('age');
        $user->status = $request->input('status');

        //dd($user);
        $user->save();

        $token = $user->createToken($user->name);
        $object = $token->accessToken;

        return response()->json([
            'user' => new UserResource($user),
            'Access Token' => $object
        ]);

    }

    public function login(Request $request){
        //$user = new User();

        // $user->email = $request->input('email');
        // $user->password = $request->input('password');

        //login credentials for accessing the account
        $loginCredentials = 
        [
            'email' => $request->email,
            'password' => $request->password
        ];

        //if a users tries logging in, check their details if it matches with the 
        //one in the database then create a token for them
        if(auth()->attempt($loginCredentials)){
            //saving the authenticated user in the varable user
            $user = auth()->user();
            $token = auth()->user()->createToken($user->name);

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token->accessToken,
            ]);


        }else{
            return response()->json([
                'message' => "Invalid Credentials"
            ]);
        }


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, User $user)
    {
        //Update a users age
        //$user->update
        $user->age = $request->input('age');
        $user->save();

        if ($request->input('age')) {
            event(new StatusUpdate($user));
        }

        return $user;



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
