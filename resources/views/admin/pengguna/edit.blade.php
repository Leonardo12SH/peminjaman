@extends('layout.app')
@section('title', 'Edit Data Pengguna')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Pengguna {{ Auth::user()->name_212102 }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Pengguna</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger w-100">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-lg-12">
                <form action="{{ route('pengguna.update', $user->id_212102) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" name="name_212102" id="name" value="{{ $user->name_212102 }}" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">No Telepon</label>
                        <input type="text" class="form-control" name="telephone_212102" id="telephone" value="{{ $user->telephone_212102 }}" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role_212102) }}" readonly>
                    </div>

                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@section('js-source')
<script src="{{ asset('src/main.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        console.log("Edit User Ready");
    });
</script>
@endsection
