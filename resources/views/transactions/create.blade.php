<style>
    .save {
        background-color: #2563eb;
    }
    .save:hover {
        background-color: #1e40af;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Tambah Transaksi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block">Jenis</label>
                    <select name="type" class="w-full border rounded p-2">
                        <option value="income">🟢 Pemasukan</option>
                        <option value="expense">🔴 Pengeluaran</option>
                    </select>
                </div>

                <div>
                    <label class="block">Deskripsi</label>
                    <input type="text" name="description" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block">Jumlah</label>
                    <input type="number" name="amount" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block">Tanggal</label>
                    <input type="date" name="date"
                           value="{{ date('Y-m-d') }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="flex gap-2">
                    <button class="save text-white px-4 py-2 rounded">
                        Simpan
                    </button>

                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
