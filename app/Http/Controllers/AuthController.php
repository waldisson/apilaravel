<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Endereco;

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
            'password_confirm' => 'required|same:password',
            'endereco' => 'required',
            'bairro' => 'required',
            'telefone' => 'required',
            'cep' => 'required'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $endereco = $request->input('endereco');
        $telefone = $request->input('telefone');
        $cep = $request->input('cep');
        $bairro = $request->input('bairro');


        $hash = password_hash($password, PASSWORD_DEFAULT);

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->save();

        if ($newUser) {
            $newEndereco = new Endereco();
            $newEndereco->user_id = $newUser->id;
            $newEndereco->endereco = $endereco;
            $newEndereco->telefone = $telefone;
            $newEndereco->cep = $cep;
            $newEndereco->bairro = $bairro;
            $newEndereco->save();
        }

        $token = auth()->attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (!$token) {
            $array['error'] = 'Ocorreu um erro.';
            return $array;
        }

        $array['token'] = $token;

        return $array;
    }
}
