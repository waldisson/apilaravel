<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Endereco;
use App\Models\User;


class EnderecoController extends Controller
{
    public function address(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'endereco' => 'required',
            'bairro' => 'required',
            'telefone' => 'required',
            'cep' => 'required'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        $endereco = $request->input('endereco');
        $telefone = $request->input('telefone');
        $cep = $request->input('cep');
        $bairro = $request->input('bairro');

        $newEndereco = new Endereco();
        $newEndereco->user_id = Auth::user()->id;
        $newEndereco->endereco = $endereco;
        $newEndereco->telefone = $telefone;
        $newEndereco->cep = $cep;
        $newEndereco->bairro = $bairro;
        $newEndereco->save();



     return $array;
    }

    public function removeAddress($id) {
        $array = ['error' => ''];

        $user = Auth::user();

        //$teste = User::with('endereco')->where('id', $user->id)->first();

        //dd($teste->endereco);

        if(!$id){
            $array ['error'] = 'O ID é obrigatório';
            return $array;
        }

        $endereco_exclusao = Endereco::find($id);

        if(is_null($endereco_exclusao)){
            $array ['error'] = 'Enderedeço inexistente!';
        }else{
            if($endereco_exclusao->user_id === $user->id){
                $endereco_exclusao->delete();
                $array['success'] = 'Endereço excluído com sucesso';
            } else {
                $array ['error'] = 'Este enderedeço não pertence ao seu usuário!';
            }
        }

        return $array;
    }
    
}
