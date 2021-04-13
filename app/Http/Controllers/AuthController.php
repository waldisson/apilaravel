<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;


class AuthController extends Controller
{
    public function unauthorized()
    {
        return response()->json([
            'error' => 'Não autorizado'
        ], 401);
    }

    public function register(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
       
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->save();

       

        //enviando para login

        $token = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);
        
        //verificado se houve erro ao logar

        if (!$token) {
            $array['error'] = 'Ocorreu um erro.';
            return $array;
        }

        $array['token'] = $token;

        //associando os dados ao usuário
        
        $user = auth()->user();
        $array['user'] = $user;
        

        return $array;
    }

    public function login(Request $request) {
        $array = ['error' => ''];
        
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);
        if(!$validator->fails()) {
            $email = $request->input('email');
            $password = $request->input('password');

            $token = Auth::attempt([
                'email' => $email,
                'password' => $password
            ]);
            
            //verificado se houve erro ao logar
    
            if (!$token) {
                $array['error'] = 'E-mail e/ou Senha estão incorretos!';
                return $array;
            }
    
            $array['token'] = $token;
    
            //associando os dados ao usuário
            
            $user = auth()->user();
            $array['user'] = $user;
        }else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }


        return $array;
    }

    public function validateToken() {
        $array = ['error' => ''];

        $user = auth()->user();
        $array['user'] = $user;

        return $array;
    }

    public function logout() {
        $array = ['error' => ''];
        auth()->logout();

        return $array;
    }
}
