@extends('customer.layout.app')

@section('title', 'Transaksi')

@section('content')
<div class="card shadow">
    <div class="card-header"><h5 class="m-0">Transaksi</h5></div>

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul></div>
    @endif

    <div class="card-body">
        <form id="formTransaksi_212102" action="{{ route('transaction.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="item_212102">Pilih Item</label>
                    <select id="item_212102" name="item_id_212102" class="form-control" required>
                        <option value="">-- Pilih Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id_212102 }}" data-harga="{{ $item->price_212102 }}">
                                {{ $item->name_212102 }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="jam">Jam Mulai</label>
                    <input type="time" id="jam" name="jam" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="jam_selesai">Jam Selesai</label>
                    <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" required>
                </div>
                <div class="col-md-9">
                    <label for="note_212102">Catatan (Opsional)</label>
                    <textarea class="form-control" name="noted_212102" rows="2"></textarea>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <p><strong>Harga per jam:</strong> Rp <span id="harga_212102">-</span></p>
                    <p><strong>Total Jam:</strong> <span id="total_jam_212102">-</span></p>
                    <p><strong>Total Harga:</strong> Rp <span id="total_212102">0</span></p>
                </div>
            </div>

            <input type="hidden" name="price" id="price_per_jam">
            <input type="hidden" name="total_price" id="total_price">
            <input type="hidden" name="start_time" id="start_time">
            <input type="hidden" name="end_time" id="end_time">

            <button type="submit" class="btn btn-primary">Submit Transaksi</button>
        </form>
    </div>
</div>
@endsection

@section('js-source')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const itemSelect = document.getElementById("item_212102");
    const hargaEl = document.getElementById("harga_212102");
    const totalJamEl = document.getElementById("total_jam_212102");
    const totalHargaEl = document.getElementById("total_212102");
    const priceInput = document.getElementById("price_per_jam");
    const totalInput = document.getElementById("total_price");
    const startTimeInput = document.getElementById("start_time");
    const endTimeInput = document.getElementById("end_time");

    function calculateTotal() {
        const jam = document.getElementById("jam").value;
        const jamSelesai = document.getElementById("jam_selesai").value;
        const tanggal = document.getElementById("tanggal").value;
        const selected = itemSelect.options[itemSelect.selectedIndex];
        const harga = selected ? parseInt(selected.getAttribute("data-harga")) : 0;

        if (jam && jamSelesai && harga && tanggal) {
            const start = new Date(`${tanggal}T${jam}`);
            const end = new Date(`${tanggal}T${jamSelesai}`);
            const totalJam = (end - start) / 3600000;

            const totalHarga = harga * totalJam;

            hargaEl.textContent = harga.toLocaleString();
            totalJamEl.textContent = totalJam;
            totalHargaEl.textContent = totalHarga.toLocaleString();

            priceInput.value = harga;
            totalInput.value = totalHarga;
            startTimeInput.value = start.toISOString();
            endTimeInput.value = end.toISOString();
        }
    }

    document.getElementById("jam").addEventListener("change", calculateTotal);
    document.getElementById("jam_selesai").addEventListener("change", calculateTotal);
    document.getElementById("item_212102").addEventListener("change", calculateTotal);
});
</script>
@endsection
