@extends('layout.app')
@section('title', 'Menu Items')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 id="token" class="m-0 text-dark">Menu Items </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Menu Items</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <a href="{{ route('menu-item.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Category</th>
                        <th>Jenis Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="w-25">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menuItems as $item)
                        <tr>
                            <td>{{ strtoupper($item->menu->name_212102 ?? 'Tidak Ditemukan') }}</td>
                            <td>{{ strtoupper($item->name_212102) }}</td>
                            <td>Rp. {{ number_format($item->price_212102, 2, ',', '.') }}</td>
                            <td>{{ $item->status_212102 == 0 ? 'Aktif' : 'Tidak Aktif' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a class="btn btn-success btn-sm" href="{{ route('menu-item.edit', $item->id_212102) }}">Edit</a>
                                    <button class="btn btn-danger btn-sm delete-data" data-id="{{ $item->id_212102 }}">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="5">Tidak ada data!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $menuItems->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection

@section('js-source')
<script src="{{ asset('src/main.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".delete-data").on("click", function() {
            const confirmDelete = confirm('Yakin hapus data ini?');
            const id = $(this).data("id");
            if (confirmDelete) {
                $.ajax({
                    method: 'DELETE',
                    url: '/admin/menu-items/' + id,
                    data: { id },
                    success: function(res) {
                        window.location.reload();
                    },
                    error: function(err) {
                        alert('Gagal menghapus data.');
                    }
                });
            }
        });
    });
</script>
@endsection
