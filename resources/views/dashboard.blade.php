<style>
    * {
        box-sizing: border-box;

    }
    #cursor {
        display: inline-block;
        margin-left: 2px;
        animation: blink 1s steps(2, start) infinite;
    }

    @keyframes blink {
        to {
            visibility: hidden;
        }
    }

    .div_tambah_transaksi {
        margin-left: 1rem;
    }
    .tambah_transaksi {
        background-color: #2563eb;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 600;
    }
    .tambah_transaksi:hover {
        background-color: #1e40af;
    }

    .list_action {
        display: flex;
        justify-content: space-evenly;
        height: 52px;
        width: 100%;
    }
    .list-action:hover {
        background-color: #000000ff;
    }
    .list_action_item {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .list_action_item:hover {
        background-color: #e5e7eb;
    }
</style>

<x-app-layout>

    <div class="py-6">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- SALDO -->
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-500">Saldo Saat Ini</p>
                <h1 class="text-3xl font-bold">
                    Rp {{ number_format($balance, 0, ',', '.') }}
                </h1>
            </div>

            <!-- MASKOT -->
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded">
                <p class="font-semibold">🐸 DompetQu Says:</p>
                <div class="font-mono">
                    <span id="maskot-text"></span><span id="cursor">|</span>
                </div>
            </div>

            <!-- TOMBOL -->
            <div class="div_tambah_transaksi">
                <a href="{{ route('transactions.create') }}"
                   class="tambah_transaksi">
                    + Tambah Transaksi
                </a>
            </div>

            <!-- LIST TRANSAKSI -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-4">Transaksi Terbaru</h3>

                @forelse ($transactions as $t)
                    <div class="border-b">

                        <!-- ROW NORMAL -->
                        <div id="row-{{ $t->id }}"
                             class="flex justify-between py-2 cursor-pointer"
                             onclick="openAction({{ $t->id }})">

                            <div>
                                <p class="font-medium">{{ $t->description }}</p>
                                <small class="text-gray-500">{{ $t->date }}</small>
                            </div>

                            <div class="{{ $t->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $t->type === 'income' ? '+' : '-' }}
                                Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- ROW ACTION -->
                        <div id="action-{{ $t->id }}"
                             class="hidden bg-gray-100">

                            <!-- <div class="grid grid-cols-3 h-14 place-items-center text-xl"> -->
                            <div class="list_action">
                                <a href="{{ route('transactions.edit', $t->id) }}"
                                   class="list_action_item">
                                   <span style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                                    ✏️
                                   </span>
                                </a>

                                <form method="POST"
                                      action="{{ route('transactions.destroy', $t->id) }}"
                                      class="list_action_item">
                                    @csrf
                                    @method('DELETE')

                                    <button style="width: 100%; height: 100%;"
                                            onclick="return confirm('Yakin hapus transaksi ini?')">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <p class="text-gray-500">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        let activeRow = null;

        function openAction(id) {
            if (activeRow && activeRow !== id) {
                document.getElementById('row-' + activeRow).classList.remove('hidden');
                document.getElementById('action-' + activeRow).classList.add('hidden');
            }

            document.getElementById('row-' + id).classList.add('hidden');
            document.getElementById('action-' + id).classList.remove('hidden');

            activeRow = id;
        }

        document.addEventListener('click', (e) => {
            if (!activeRow) return;

            const row = document.getElementById('row-' + activeRow);
            const action = document.getElementById('action-' + activeRow);

            if (!row.contains(e.target) && !action.contains(e.target)) {
                row.classList.remove('hidden');
                action.classList.add('hidden');
                activeRow = null;
            }
        });

    // MASKOT LOGIC
    const balance = {{ $balance }};
    
    let mode = 'aman';
    if (balance < 100000) mode = 'miskin';
    else if (balance > 300000) mode = 'sultan';
    
    const texts = {
        miskin: [
            "WARNING‼️ Dompet lo kurus, bree.",
            "Jajan dulu apa nabung? Dua-duanya ga bisa awokawok 😹",
            "Santai… Masih ada indomie 😜"
        ],
        aman: [
            "Saldo menipiz, jangan sok sultan 😏",
            "Masih bisa nafas, jangan foya-foya ✋",
            "Keep it steady."
        ],
        sultan: [
            "Wih, cair nih 🥶",
            "Santai bos, saldo lo aman.",
            "Inget gue ga, saudara lo yang terpisah sejak lama 👀",
            "Pinjem dulu seratus 🤭",
            "Inpo malming? 🫦"
        ]
    };
    
    const el = document.getElementById('maskot-text');
    const cursor = document.getElementById('cursor');
    
    if (mode === 'miskin') {
        el.style.color = '#dc2626';
        cursor.style.color = '#000000ff';
    }
    
    let index = 0;
    
    const TYPE_SPEED = 50;
    const ERASE_SPEED = 10;
    const HOLD_AFTER_TYPE = 5000;
    const HOLD_AFTER_ERASE = 500;
    
    function typeText(text, cb) {
        const chars = Array.from(text);
        let i = 0;
        el.textContent = "";
        
        function typing() {
            if (i < chars.length) {
                el.textContent += chars[i];
                i++;
                setTimeout(typing, TYPE_SPEED);
            } else {
                setTimeout(cb, HOLD_AFTER_TYPE);
            }
        }
        
        typing();
    }
    
    function eraseText(cb) {
        function erasing() {
            if (el.textContent.length > 0) {
                el.textContent = el.textContent.slice(0, -1);
                setTimeout(erasing, ERASE_SPEED);
            } else {
                setTimeout(cb, HOLD_AFTER_ERASE);
            }
        }
        
        erasing();
    }
    
    function loop() {
        const text = texts[mode][index];
        
        typeText(text, () => {
            eraseText(() => {
                index = (index + 1) % texts[mode].length;
                loop();
            });
        });
    }
    
    loop();
</script>

</x-app-layout>