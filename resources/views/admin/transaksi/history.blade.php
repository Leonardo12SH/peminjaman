@extends('layout.app')
@section('title', 'History')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 id="token" class="m-0 text-dark">Welcome {{ Auth::user()->name }}</h1> -->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nama</th>
                            {{-- <th scope="col">Nama Sewa</th> --}}
                            <th scope="col">Tanggal</th>
                            <th scope="col">Harga Sewa</th>
                            <th scope="col">Total Jam</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $h)
                            <tr>
                                <td>{{ $h->user->name_212102 }}</td>
                                {{-- <td> --}}
                                    {{-- Check if transaksi_details is not empty and then access the first element --}}
                                    {{-- {{ !empty($h->transaksi_details) && isset($h->transaksi_details[0]) ? $h->transaksi_details[0]->menuitem->name_212102 : 'N/A' }} --}}
                                {{-- </td> --}}
                                <td>{{ $h->start_time }}</td>
                                <td>Rp. {{ number_format($h->price, '2', ',') }}</td>
                                <td>{{ is_array($jam = json_decode($h->jam)) ? count($jam) : 0 }} Jam</td>
                                <td>Rp. {{ number_format($h->total_price, '2', ',') }}</td>
                                <td>
                                    {{ $h->start_time < date('Y-m-d H:i:s') && $h->status == 0 ? 'Kadaluarsa' : 
                                    ($h->status == 0 ? 'Menunggu' : 
                                    ($h->status == 1 ? 'Diterima' : 'Gagal')) }}
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="7">Tidak ada data!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    {{ $history->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- /.row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

@section('js-source')
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
@endsection
