@extends('customer.layout.app')

@section('title', 'Transaksi')

@section('content')
<div class="card shadow">
    <div class="card-header"><h5 class="m-0">Transaksi</h5></div>

    <div class="card-body"> {{-- Pindahkan pesan ke dalam card-body untuk konsistensi --}}

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Pesan Error Umum (dari try-catch atau redirect with error manual) --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Pesan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops! Ada beberapa masalah dengan input Anda:</strong>
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form id="formTransaksi_212102" action="{{ route('transaction.store') }}" method="POST">
            @csrf
            {{-- Baris Pertama: Item, Tanggal Mulai, Jam Mulai --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="item_select_212102">Pilih Item</label>
                    <select id="item_select_212102" name="item_id_212102" class="form-control @error('item_id_212102') is-invalid @enderror" required>
                        <option value="" data-harga="0">-- Pilih Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id_212102 }}" data-harga="{{ $item->price_212102 }}" {{ old('item_id_212102') == $item->id_212102 ? 'selected' : '' }}>
                                {{ $item->name_212102 }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id_212102')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="tanggal_mulai_input_212102">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai_input_212102" name="tanggal_mulai_212102" value="{{ old('tanggal_mulai_212102') }}" class="form-control @error('tanggal_mulai_212102') is-invalid @enderror" required>
                    @error('tanggal_mulai_212102')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="jam_mulai_input_212102">Jam Mulai (format 00:00 - 23:59)</label>
                    <input type="time" id="jam_mulai_input_212102" name="jam_mulai_212102" value="{{ old('jam_mulai_212102') }}" class="form-control @error('jam_mulai_212102') is-invalid @enderror" required>
                    @error('jam_mulai_212102')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Baris Kedua: Tanggal Selesai, Jam Selesai, Catatan --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="tanggal_selesai_input_212102">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai_input_212102" name="tanggal_selesai_212102" value="{{ old('tanggal_selesai_212102') }}" class="form-control @error('tanggal_selesai_212102') is-invalid @enderror" required>
                    @error('tanggal_selesai_212102')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="jam_selesai_input_212102">Jam Selesai (format 00:00 - 23:59)</label>
                    <input type="time" id="jam_selesai_input_212102" name="jam_selesai_212102" value="{{ old('jam_selesai_212102') }}" class="form-control @error('jam_selesai_212102') is-invalid @enderror" required>
                    @error('jam_selesai_212102')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="noted_input_212102">Catatan (Opsional)</label>
                    <textarea class="form-control @error('noted_212102') is-invalid @enderror" id="noted_input_212102" name="noted_212102" rows="2">{{ old('noted_212102') }}</textarea>
                    @error('noted_212102')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Detail Perhitungan --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <p><strong>Harga per jam:</strong> Rp <span id="harga_per_jam_display_212102">-</span></p>
                    <p><strong>Total Jam:</strong> <span id="total_jam_display_212102">-</span> jam</p>
                    <p><strong>Total Harga:</strong> Rp <span id="total_harga_display_212102">0</span></p>
                </div>
            </div>

            {{-- Hidden inputs untuk dikirim ke backend --}}
            {{-- Pastikan atribut 'name' di sini sesuai dengan yang diharapkan oleh Controller dan aturan validasi --}}
            {{-- Atribut 'id' digunakan oleh JavaScript --}}
            <input type="hidden" name="price" id="price_per_jam" value="{{ old('price') }}">
            <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price') }}">
            <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time') }}">
            <input type="hidden" name="end_time" id="end_time" value="{{ old('end_time') }}">

            <button type="submit" class="btn btn-primary">Submit Transaksi</button>
        </form>
    </div>
</div>
@endsection

@section('js-source')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const itemSelectElement = document.getElementById("item_select_212102");
    const tanggalMulaiInputElement = document.getElementById("tanggal_mulai_input_212102");
    const jamMulaiInputElement = document.getElementById("jam_mulai_input_212102");
    const tanggalSelesaiInputElement = document.getElementById("tanggal_selesai_input_212102");
    const jamSelesaiInputElement = document.getElementById("jam_selesai_input_212102");

    const hargaPerJamDisplayElement = document.getElementById("harga_per_jam_display_212102");
    const totalJamDisplayElement = document.getElementById("total_jam_display_212102");
    const totalHargaDisplayElement = document.getElementById("total_harga_display_212102");

    // ID untuk hidden fields (sesuai HTML Anda)
    const pricePerHourHiddenElement = document.getElementById("price_per_jam");
    const totalPriceHiddenElement = document.getElementById("total_price");
    const startDateTimeHiddenElement = document.getElementById("start_time");
    const endDateTimeHiddenElement = document.getElementById("end_time");

    function resetDisplayAndHiddenValues(message = "-") {
        // console.log("TRACE: Mereset tampilan. Pesan:", message); // Uncomment untuk debug
        hargaPerJamDisplayElement.textContent = "-";
        totalJamDisplayElement.textContent = message;
        totalHargaDisplayElement.textContent = "0";
        if (pricePerHourHiddenElement) pricePerHourHiddenElement.value = "";
        if (totalPriceHiddenElement) totalPriceHiddenElement.value = "";
        if (startDateTimeHiddenElement) startDateTimeHiddenElement.value = "";
        if (endDateTimeHiddenElement) endDateTimeHiddenElement.value = "";
    }

    function calculateTotal() {
        // console.log("TRACE: --- calculateTotal() dipicu ---"); // Uncomment untuk debug

        const tanggalMulaiValue = tanggalMulaiInputElement.value;
        const jamMulaiValue = jamMulaiInputElement.value;
        const tanggalSelesaiValue = tanggalSelesaiInputElement.value;
        const jamSelesaiValue = jamSelesaiInputElement.value;
        
        const selectedOption = itemSelectElement.options[itemSelectElement.selectedIndex];
        const hargaPerJam = selectedOption && selectedOption.value !== "" ? parseInt(selectedOption.getAttribute("data-harga"), 10) : 0;

        // console.log("TRACE: Input Values:", { /* ... */ }); // Uncomment untuk debug

        if (!tanggalMulaiValue || !jamMulaiValue || !tanggalSelesaiValue || !jamSelesaiValue || !(hargaPerJam > 0)) {
            resetDisplayAndHiddenValues("Lengkapi semua input");
            // console.log("TRACE: Input tidak lengkap atau harga item tidak valid."); // Uncomment untuk debug
            return;
        }

        let startDateString = `${tanggalMulaiValue}T${jamMulaiValue}`;
        let endDateString = `${tanggalSelesaiValue}T${jamSelesaiValue}`;
        // console.log("TRACE: String untuk startDate:", startDateString); // Uncomment untuk debug
        // console.log("TRACE: String untuk endDate:", endDateString); // Uncomment untuk debug

        let startDate = new Date(startDateString);
        let endDate = new Date(endDateString);

        // console.log("TRACE: Parsed startDate:", startDate.toString()); // Uncomment untuk debug
        // console.log("TRACE: Parsed endDate:", endDate.toString()); // Uncomment untuk debug


        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
            // console.error("TRACE: Format tanggal atau jam tidak valid saat parsing."); // Uncomment untuk debug
            resetDisplayAndHiddenValues("Format waktu salah");
            return;
        }
        
        if (endDate.getTime() <= startDate.getTime()) {
            // console.warn("TRACE: Waktu selesai sebelum atau sama dengan waktu mulai."); // Uncomment untuk debug
            resetDisplayAndHiddenValues("Waktu selesai tidak valid");
            return;
        }

        const diffInMilliseconds = endDate.getTime() - startDate.getTime();
        // console.log("TRACE: Selisih Waktu (ms):", diffInMilliseconds); // Uncomment untuk debug

        const totalJam = diffInMilliseconds / 3600000;
        // console.log("TRACE: Total Jam Kalkulasi:", totalJam); // Uncomment untuk debug

        if (totalJam <= 0) { 
            //  console.warn("TRACE: Total jam 0 atau negatif (seharusnya tidak terjadi)."); // Uncomment untuk debug
             resetDisplayAndHiddenValues("Durasi tidak valid (internal)");
             return;
        }

        const totalHarga = hargaPerJam * totalJam;
        // console.log("TRACE: Total Harga Kalkulasi:", totalHarga); // Uncomment untuk debug

        hargaPerJamDisplayElement.textContent = hargaPerJam.toLocaleString('id-ID');
        totalJamDisplayElement.textContent = totalJam.toFixed(2);
        totalHargaDisplayElement.textContent = totalHarga.toLocaleString('id-ID');

        if (pricePerHourHiddenElement) pricePerHourHiddenElement.value = hargaPerJam;
        if (totalPriceHiddenElement) totalPriceHiddenElement.value = totalHarga;
        if (startDateTimeHiddenElement) startDateTimeHiddenElement.value = startDate.toISOString();
        if (endDateTimeHiddenElement) endDateTimeHiddenElement.value = endDate.toISOString();
        
        // console.log("TRACE: Nilai Hidden Fields Diupdate."); // Uncomment untuk debug
        // console.log("TRACE: --- calculateTotal() selesai ---"); // Uncomment untuk debug
    }

    itemSelectElement.addEventListener("change", calculateTotal);
    tanggalMulaiInputElement.addEventListener("change", calculateTotal);
    jamMulaiInputElement.addEventListener("change", calculateTotal);
    tanggalSelesaiInputElement.addEventListener("change", calculateTotal);
    jamSelesaiInputElement.addEventListener("change", calculateTotal);

    // Panggil calculateTotal saat halaman dimuat untuk mengisi nilai jika ada old input
    // Ini penting agar kalkulasi JS berjalan jika form direpopulate setelah validasi gagal
    calculateTotal();

});
</script>
@endsection