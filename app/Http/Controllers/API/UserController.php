<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;

use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __invoke(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'username'      => 'required|unique:users',
            'phone'      => 'nullable|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = User::create([
            'name'      => $request->name,
            'username'      => $request->username,
            'phone'      => $request->phone,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        //return response JSON user is created
        if ($user) {
            return ResponseFormatter::success([
                'success' => true,
                'user'    => $user,
            ], 201);
        }

        //return JSON process insert failed
        return ResponseFormatter::error([
            'success' => false,
        ], 409);
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Data Profile user berhasil diambil');
    }

    public function updateUser(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'username'      => 'required|unique:users',
            'phone'      => 'nullable|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();

        $user = Auth::user();

        $name       = $request->get('name');
        $username   = $request->get('username');
        $phone      = $request->get('phone');
        $email      = $request->get('email');
        $password   = bcrypt($request->get('password'));

        /*
    Ensure the user has entered a favorite coffee
  */
        if ($name != '') {
            $user->name    = $name;
        }

        /*
    Ensure the user has entered some flavor notes
  */
        if ($username != '') {
            $user->username       = $username;
        }

        /*
    Ensure the user has submitted a profile visibility update
  */
        if ($phone != '') {
            $user->phone = $phone;
        }

        /*
    Ensure the user has entered something for city.
  */
        if ($email != '') {
            $user->email   = $email;
        }

        /*
    Ensure the user has entered something for state
  */
        if ($password != '') {
            $user->password  = $password;
        }

        $user->save();


        return ResponseFormatter::success($user, 'Profile Updated');
    }
}