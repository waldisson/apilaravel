<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Endereco;
use App\Models\User;


class EnderecoController extends Controller
{
    //Post
    public function createAddress(Request $request)
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

        $newEndereco = new Endereco();
        $newEndereco->user_id = Auth::user()->id;
        $newEndereco->endereco = $request->input('endereco');
        $newEndereco->telefone = $request->input('telefone');
        $newEndereco->cep = $request->input('cep');
        $newEndereco->bairro = $request->input('bairro');
        $newEndereco->save();

        $array['endereco'] = $newEndereco;

        return $array;
    }
    //Get
    public function getAddress($id)
    {
        $array = ['error' => ''];

        $endereco = Endereco::where('user_id',  Auth::user()->id)->get();

        //se não rodar coloca(if(empty($endereco)){ ... }
        if ($endereco->isEmpty()) {
            $array['error'] = 'Endereco nao encontrado!';
            return $array;
        }

        $array['endereco'] = $endereco;

        return $array;
    }


    //Alterar
    public function putAddress($id, Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'endereco' => 'min:3',
            'bairro' => 'min:1',
            'telefone' => 'min:8',
            'cep' => 'min:5',

        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        $endereco = $request->input('endereco');
        $telefone = $request->input('telefone');
        $cep = $request->input('cep');
        $bairro = $request->input('bairro');

        $endereco_atualizar = Endereco::find($id);

        if ($endereco_atualizar) {
            if ($endereco) {
                $endereco_atualizar->endereco = $endereco;
            }
            if ($telefone) {
                $endereco_atualizar->telefone = $telefone;
            }
            if ($cep) {
                $endereco_atualizar->cep = $cep;
            }
            if ($bairro) {
                $endereco_atualizar->bairro = $bairro;
            }

            $endereco_atualizar->save();
        } else {
            $array['error'] = "Endereço {$id} não existe/Não pode ser atualizado!";
        }

        return $array;
    }


    //Delete
    public function removeAddress($id)
    {
        $array = ['error' => ''];

        $user = Auth::user();

        User::with('endereco')->where('id', $user->id)->first();

        if (!$id) {
            $array['error'] = 'O ID é obrigatório';
            return $array;
        }

        $endereco_exclusao = Endereco::find($id);

        if (is_null($endereco_exclusao)) {
            $array['error'] = 'Enderedeço inexistente!';
        } else {
            if ($endereco_exclusao->user_id === $user->id) {
                $endereco_exclusao->delete();
                $array['success'] = 'Endereço excluído com sucesso';
            } else {
                $array['error'] = 'Este enderedeço não pertence ao seu usuário!';
            }
        }

        return $array;
    }
}
