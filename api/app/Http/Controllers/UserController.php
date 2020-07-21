<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|unique:users',
            'email'         => 'required|email|',
            'password'      => 'required',
            'c_password'    => 'required'
        ]);
        
        if($validator->fail()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return response()->json([
            'user' => $user
        ]);
         
    }

    public function login()
    {
        $status = 401;
        $response = ['error' => 'Unauthorized'];

        if(Auth::attempt($request->only(['email', 'password']))){
            $status = 200;
            $response = [
                'user' => Auth::user(),
                'token'=> Auth::user()->createToken('plsite')->accessToken
            ];
        }

        return response()->json($response, $status);
    }
    
    public function update()
    {
        $validator = validator::make($request->all(), [
            'name'      => 'required|name:unique',
            'email'     => 'required|email|',
            'password'  => 'required|'
        ]);

        if($validator->fail()){
            return response()->json([
                'errors'   => $validator->errors
            ]);
        }

        $data = User::find($id);
        $data->name = $request['name'];
        $data->email = $request['email'];
        $data->save();

        return response()->json([
            'user'      => $data,
            'essage'    => $data ? 'User updated' : 'Error Updating Users'
        ]);
    }

    public function destroy()
    {
        //
    }
}
