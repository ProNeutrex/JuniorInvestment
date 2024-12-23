<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Models\Coins;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\NotifyTrait;
use App\Models\CoinsConfig;
use App\Models\Notification;

class CoinController extends Controller
{

    public function index()
    {
        // Compras do usuário 
        $user = Auth::user();
        $conf = CoinsConfig::find(1);
        $coins = Coins::where('usuario', $user->id)->orderBy('id', 'desc')->paginate(10);
        return view('frontend::coin.index', compact('coins', 'conf'));
    }
    // Comprar Coin 
    public function buy(Request $request)
    {
        $input = $request->all();
        $conf = CoinsConfig::find(1);

        if ($conf->status == 0) {
            notify()->error('Desculpe, a venda de Coins está suspensa no momento', 'Error');
            return redirect()->route('user.coin');
        }

        $user = Auth::user();
        // Consultar saldo de compras do usuário
        $coins = $input['coins'];
        $valor = $coins * $conf->valor;
        //Insufficient Balance validation
        if ($input['wallet'] == 'main' && $user->balance < $valor) {
            notify()->error('Saldo insuciente', 'Error');

            return redirect()->route('user.coin');
        } elseif ($input['wallet'] == 'profit' && $user->profit_balance < $valor) {
            notify()->error('Seu saldo é insuficiente, faça uma recarga', 'Error');

            return redirect()->route('user.coin');
        }
        // Atualziar saldo  
        if ($input['wallet'] == 'main') {
            $user->decrement('balance', $valor);
        } elseif ($input['wallet'] == 'profit') {
            $user->decrement('profit_balance', $valor);
        }
        // Creditar compra
        $coins = new Coins();
        $coins->quantia = $input['coins'];
        $coins->valor = $valor;
        $coins->carteira = $input['carteira'];
        $coins->usuario = $user->id;
        $coins->save();
        // Cadastrar notificação
        $notification = new Notification();
        $notification->icon = 'user-plus';
        $notification->user_id = $user->id;
        $notification->for = 'admin';
        $notification->title = 'Compra de Coins';
        $notification->notice = 'O usuário ' . $user->name . ' comprou ' . $input['coins'] . ' Coins';
        $notification->action_url = route('admin.coins.index');
        $notification->save();

        // retornar com notificação 
        notify()->success('Compra realizada com sucesso', 'Success');
        return redirect()->route('user.coin');
    }
}
