<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Holding;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function consultar(Request $request)
{
    $request->validate([
        'codigo' => 'required|string|max:30'
    ]);

    $codigo = strtoupper($request->input('codigo'));

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('BRAPI_TOKEN'),
        'User-Agent' => 'FinanceApp'
    ])->get("https://brapi.dev/api/quote/{$codigo}");

    if (!$response->successful()) {
        return back()->with('erro', "Código '{$codigo}' inválido ou não encontrado.");
    }

    $data = $response->json();

    if (
        isset($data['error']) && $data['error'] === true ||
        empty($data['results']) ||
        !isset($data['results'][0]['regularMarketPrice'])
    ) {
        return back()->with('erro', "Código '{$codigo}' não encontrado.");
    }

    $acao = $data['results'][0];
    $mensagem = "O valor atual da ação do {$acao['longName']} é de R$" . number_format($acao['regularMarketPrice'], 2, ',', '.');
    return back()->with('mensagem', $mensagem);
}

public function comprar(Request $request)
{
    $request->validate([
        'codigo' => 'required|string|max:30',
        'quantidade' => 'required|integer|min:1'
    ]);

    $codigo = strtoupper($request->input('codigo'));
    $quantidade = (int)$request->input('quantidade');

    $user = Auth::user();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('BRAPI_TOKEN'),
        'User-Agent' => 'FinanceApp'
    ])->get("https://brapi.dev/api/quote/{$codigo}");

    if (!$response->successful()) {
        return back()->with('erro', "Código '{$codigo}' inválido ou não encontrado.");
    }

    $data = $response->json();

    if (
        isset($data['error']) && $data['error'] === true ||
        empty($data['results']) ||
        !isset($data['results'][0]) ||
        !isset($data['results'][0]['regularMarketPrice']) ||
        !is_numeric($data['results'][0]['regularMarketPrice'])
    ) {
        return back()->with('erro', "Código '{$codigo}' não encontrado.");
    }
    

    $preco = $data['results'][0]['regularMarketPrice'];
    $total = $preco * $quantidade;

    if ($user->balance < $total) {
        return back()->with('erro', "Saldo insuficiente para essa compra. Você precisa de R$" . number_format($total, 2, ',', '.'));
    }

    $user->balance -= $total;
    $user->save();

    $holding = Holding::firstOrNew([
        'user_id' => $user->id,
        'symbol' => $codigo
    ]);

    $holding->quantity += $quantidade;
    $holding->save();

    Transaction::create([
        'user_id' => $user->id,
        'symbol' => $codigo,
        'quantity' => $quantidade,
        'price' => $preco,
        'transacted_at' => now(),
    ]);

    return back()->with('mensagem', "Compra de {$quantidade}x {$codigo} realizada com sucesso!");
}


public function vender(Request $request)
{
    $request->validate([
        'codigo' => 'required|string|max:30',
        'quantidade' => 'required|integer|min:1'
    ]);

    $codigo = strtoupper($request->input('codigo'));
    $quantidade = (int)$request->input('quantidade');

    $user = Auth::user();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('BRAPI_TOKEN'),
        'User-Agent' => 'FinanceApp'
    ])->get("https://brapi.dev/api/quote/{$codigo}");

    if (!$response->successful()) {
        return back()->with('erro', "Código '{$codigo}' inválido ou não encontrado.");
    }

    $data = $response->json();

    if (
        !isset($data['results']) ||
        !is_array($data['results']) ||
        count($data['results']) === 0 ||
        !isset($data['results'][0]['regularMarketPrice']) ||
        !is_numeric($data['results'][0]['regularMarketPrice'])
    ) {
        return back()->with('erro', "Código '{$codigo}' não encontrado.");
    }

    
    $holding = Holding::where('user_id', $user->id)->where('symbol', $codigo)->first();

    if (!$holding) {
        return back()->with('erro', "Você não possui ações de {$codigo}.");
    }

    if ($holding->quantity < $quantidade) {
        return back()->with('erro', "Você não tem quantidade suficiente de {$codigo} para vender.");
    }

    $preco = $data['results'][0]['regularMarketPrice'];
    $total = $preco * $quantidade;

    $holding->quantity -= $quantidade;
    $holding->save();

    if ($holding->quantity == 0) {
        $holding->delete();
    }

    $user->balance += $total;
    $user->save();

    Transaction::create([
        'user_id' => $user->id,
        'symbol' => $codigo,
        'quantity' => -$quantidade,
        'price' => $preco,
        'transacted_at' => now(),
    ]);

    return back()->with('mensagem', "Venda de {$quantidade}x {$codigo} realizada com sucesso!");
}

    public function carteira()
{
    $user = Auth::user();
    $holdings = Holding::where('user_id', $user->id)->get();
    $totalCarteira = 0;
    $acoes = [];

    foreach ($holdings as $holding) {
        $response = $this->consultarAcao($holding->symbol);

        if ($response->successful()) {
            $data = $response->json();
            $acao = $data['results'][0] ?? null;

            if ($acao && isset($acao['regularMarketPrice'])) {
                $valorAtual = $acao['regularMarketPrice'];
                $quantidade = $holding->quantity;
                $valorTotal = $valorAtual * $quantidade;
                $totalCarteira += $valorTotal;

                $acoes[] = [
                    'codigo' => $holding->symbol,
                    'nome' => $acao['longName'] ?? $holding->symbol,
                    'preco' => $valorAtual,
                    'quantidade' => $quantidade,
                    'total' => $valorTotal,
                ];
            }
        }
    }

    return view('carteira', [
        'acoes' => $acoes,
        'saldo' => $user->balance,
        'totalCarteira' => $totalCarteira
    ]);
}

    public function historico()
{
    $user = Auth::user();

    $transacoes = Transaction::where('user_id', $user->id)
        ->orderByDesc('transacted_at')
        ->get();

    return view('historico', compact('transacoes'));
}

    
    private function consultarAcao($codigo)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . env('BRAPI_TOKEN'),
            'User-Agent' => 'FinanceApp'
        ])->get("https://brapi.dev/api/quote/{$codigo}");
    }
}
