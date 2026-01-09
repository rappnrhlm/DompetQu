<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Transaksi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

            <form method="POST"
                  action="{{ route('transactions.update', $transaction->id) }}"
                  class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block">Jenis</label>
                    <select name="type" class="w-full border rounded p-2">
                        <option value="income" @selected($transaction->type === 'income')>
                            🟢 Pemasukan
                        </option>
                        <option value="expense" @selected($transaction->type === 'expense')>
                            🔴 Pengeluaran
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block">Deskripsi</label>
                    <input type="text"
                           name="description"
                           value="{{ $transaction->description }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block">Jumlah</label>
                    <input type="number"
                           name="amount"
                           value="{{ $transaction->amount }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block">Tanggal</label>
                    <input type="date"
                           name="date"
                           value="{{ $transaction->date }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="flex gap-2">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">
                        Update
                    </button>

                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 border rounded">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
