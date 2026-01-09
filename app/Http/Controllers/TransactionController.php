<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Auth::user()
            ->transactions()
            ->latest()
            ->get();

        $balance = Auth::user()
            ->transactions()
            ->selectRaw("
                SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as balance
            ")
            ->value('balance') ?? 0;

        return view('dashboard', compact('transactions', 'balance'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'description' => 'required|string',
            'amount' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('dashboard');
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:income,expense',
            'description' => 'required|string',
            'amount' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $transaction->update($request->only([
            'type', 'description', 'amount', 'date'
        ]));

        return redirect()->route('dashboard');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('dashboard');
    }
}
