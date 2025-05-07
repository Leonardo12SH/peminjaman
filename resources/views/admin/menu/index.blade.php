@extends('layout.app')
@section('title', 'Menu')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 id="token" class="m-0 text-dark">Menu List Category</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Menu</li>
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
            <a href="{{route('menu.create')}}" class="btn btn-primary mb-3">Tambah Data</a>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Category</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="w-25">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                    <tr>
                        <th scope="row">{{strtoupper($menu->name_212102)}}</th>
                        <td>{{$menu->status_212102 == 0 ? 'Aktif' : 'Tidak Aktif' }}</td>
                        <td>
                            <div class="row">
                            <a class="btn btn-success" href="{{ route('menu.edit', $menu->id_212102) }}">Edit</a>
                            <button class="btn btn-danger edit-data" data-id="{{ $menu->id_212102 }}">Hapus</button>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="3">Tidak ada data!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{
                    $menus->links('pagination::bootstrap-4')
                }}
            </div>
        </div>
        <!-- /.row -->
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('js-source')
<script src="{{ asset('src/main.js') }}"></script>
<script src="{{ asset('assets//plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // var aa = test(10);
    $(".edit-data").on("click", function() {
        const confirmDelete = confirm('Yakin delete?');
        let dataId = $(this).attr("data-id");
        if (confirmDelete) {
            $.ajax({
                method: 'DELETE',
                url: '/admin/menu/' + dataId,
                data: {
                    id: dataId
                },
                success: (res) => {
                    window.location.reload()
                }
            })
        }
    });
})
</script>
@endsection