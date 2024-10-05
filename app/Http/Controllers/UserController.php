<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse(400,$validator->messages());
        }

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
        ]);
        $token=$user->createToken('myApp')->plainTextToken;

        return $this->successResponse([
            'user'=>$user,
            'token'=>$token
        ],200);
    }
    public function login(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'email'=>'required | email',
            'password'=>'required'
        ]);
        if($validate->fails())
        {
            return $this->errorResponse(403,$validate->messages());
        }
        $user=User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password , $user->password ))
        {
            return $this->errorResponse(404,'User not found!');
        }
        $token=$user->createToken('myApp')->plainTextToken;

        return $this->successResponse([
            'user'=>$user,
            'token'=>$token
        ],200);
    }

    public function logout()
    {
      auth()->user()->tokens()->delete();
      return $this->successResponse('Logged out',200);
    }
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
