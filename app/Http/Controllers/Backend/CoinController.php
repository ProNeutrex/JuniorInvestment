<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Coins;
use App\Models\CoinsConfig;
use App\Models\User;

class CoinController extends Controller
{
    public function index()
    {
        $page_title = 'Coins';
        $coins = Coins::join('users', 'coins.usuario', '=', 'users.id')
        ->select('coins.*', 'users.username')
            ->orderBy('coins.id', 'desc')
            ->paginate(10);
        return view('backend.coins.index', compact('page_title', 'coins'));
    }
    // Aprovar 
    public function aprovar($id)
    {
        $coins = Coins::find($id);
        $coins->status = 1;
        $coins->save();
        notify()->success(__('Atualizado com sucesso'));
        return back();
    }
    // Editar uma aposta
    public function editar()
    {
        $page_title = 'Editar Aposta';
        $ap = CoinsConfig::find(1);
        return view('backend.coins.editar', compact('page_title', 'ap'));
    }
    // Deletar 
    public function deletar($id)
    {
        $coins = Coins::find($id);
        if ($coins) {
            $coins->delete();
            notify()->success(__('Deletado com sucesso'));
        } else {
            notify()->error(__('Registro não encontrado'));
        }
        return back();
    }
    
    // Atualizar uma aposta
    public function atualizar(Request $request)
    {
        $request->validate([
            'valor' => 'required',
            'nome' => 'required',
            'status' => 'required',
        ]);

        $aposta = CoinsConfig::find(1);
        $aposta->valor = $request->valor;
        $aposta->nome = $request->nome;
        $aposta->status = $request->status;
        $aposta->save();
        notify()->success(__('Configurado com sucesso'));
        return redirect()->route('admin.coins.editar');
    }
}
